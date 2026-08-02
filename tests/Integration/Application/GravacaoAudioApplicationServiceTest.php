<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Exceptions\TranscricaoFalhouException;
use PsycheAI\Application\Services\GravacaoAudioApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteGravacaoAudioRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;
use PsycheAI\Tests\Support\StorageStub;
use PsycheAI\Tests\Support\TranscricaoStub;
use RuntimeException;

final class GravacaoAudioApplicationServiceTest extends SQLiteTestCase
{
    private SessaoApplicationService $sessaoApplicationService;
    private SQLiteSessaoRepository $sessaoRepository;
    private SQLiteGravacaoAudioRepository $gravacaoAudioRepository;
    private StorageStub $storage;
    private int $proximoUuid = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $this->sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $this->gravacaoAudioRepository = new SQLiteGravacaoAudioRepository($this->pdo);
        $this->sessaoApplicationService = new SessaoApplicationService($this->sessaoRepository, $sujeitoRepository);
        $this->storage = new StorageStub();

        (new SujeitoApplicationService($sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');
        $this->sessaoApplicationService->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-07-31 10:00:00'));
    }

    private function novoServico(?TranscriptionInterface $transcricao = null): GravacaoAudioApplicationService
    {
        return new GravacaoAudioApplicationService(
            $this->gravacaoAudioRepository,
            $this->sessaoRepository,
            $this->storage,
            $transcricao ?? new TranscricaoStub(new TranscriptionResultDTO('')),
            $this->uuidGeneratorSequencial()
        );
    }

    private function uuidGeneratorSequencial(): UuidGeneratorInterface
    {
        return new class($this) implements UuidGeneratorInterface {
            public function __construct(private readonly GravacaoAudioApplicationServiceTest $teste)
            {
            }

            public function generate(): string
            {
                return 'uuid-' . $this->teste->proximoUuidEIncrementa();
            }
        };
    }

    public function proximoUuidEIncrementa(): int
    {
        return $this->proximoUuid++;
    }

    public function testRegistrarGravaOAudioNoStorageEPersisteComoPendente(): void
    {
        $servico = $this->novoServico();

        $dto = $servico->registrar('sessao-1', 'bytes-do-audio');

        self::assertSame('sessao-1', $dto->sessaoId);
        self::assertSame('pendente', $dto->status);
        self::assertTrue($this->storage->contemAlgumArquivo());
    }

    public function testRegistrarLancaExcecaoQuandoSessaoNaoExiste(): void
    {
        $servico = $this->novoServico();

        $this->expectException(RecursoNaoEncontradoException::class);

        $servico->registrar('sessao-inexistente', 'bytes');
    }

    public function testTranscreverDivideAGravacaoEmEventosDiscursivos(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(
            text: 'texto corrido, ignorado quando há segments',
            segments: [
                ['text' => 'eu quis dizer', 'inicio' => 0.0, 'fim' => 1.2],
                ['text' => 'quero dizer', 'inicio' => 1.3, 'fim' => 2.5],
            ]
        )));

        $dto = $servico->registrar('sessao-1', 'bytes-do-audio');
        $resultado = $servico->transcrever($dto->id);

        self::assertSame('transcrita', $resultado->status);

        $sessaoRecuperada = $this->sessaoRepository->findById('sessao-1');
        $eventos = $sessaoRecuperada->discursos()[0]->eventos();
        self::assertCount(2, $eventos);
        self::assertSame('eu quis dizer', $eventos[0]->conteudo()->valor());
        self::assertSame('quero dizer', $eventos[1]->conteudo()->valor());
    }

    public function testTranscreverUsaTextoCorridoQuandoNaoHaSegments(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(text: 'fala única sem pausas')));

        $dto = $servico->registrar('sessao-1', 'bytes-do-audio');
        $servico->transcrever($dto->id);

        $eventos = $this->sessaoRepository->findById('sessao-1')->discursos()[0]->eventos();
        self::assertCount(1, $eventos);
        self::assertSame('fala única sem pausas', $eventos[0]->conteudo()->valor());
    }

    public function testTranscreverLancaExcecaoQuandoGravacaoNaoExiste(): void
    {
        $servico = $this->novoServico();

        $this->expectException(RecursoNaoEncontradoException::class);

        $servico->transcrever('inexistente');
    }

    public function testTranscreverMarcaFalhaQuandoProvedorFalha(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(null, new RuntimeException('falha simulada')));

        $dto = $servico->registrar('sessao-1', 'bytes-do-audio');

        $this->expectException(TranscricaoFalhouException::class);

        try {
            $servico->transcrever($dto->id);
        } finally {
            $gravacaoRecuperada = $this->gravacaoAudioRepository->findById($dto->id);
            self::assertSame('falha', $gravacaoRecuperada->status()->value);
        }
    }

    public function testBytesDoArquivoDevolveOConteudoOriginal(): void
    {
        $servico = $this->novoServico();

        $dto = $servico->registrar('sessao-1', 'bytes-originais-do-audio');

        self::assertSame('bytes-originais-do-audio', $servico->bytesDoArquivo($dto->id));
    }

    public function testListarPendentesIgnoraAsJaTranscritas(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(text: 'falado')));
        $transcrita = $servico->registrar('sessao-1', 'primeiro');
        $servico->transcrever($transcrita->id);
        $pendente = $servico->registrar('sessao-1', 'segundo');

        $pendentes = $servico->listarPendentes();

        self::assertCount(1, $pendentes);
        self::assertSame($pendente->id, $pendentes[0]->id);
    }

    public function testBuscarPorSessaoRetornaAUltimaGravacao(): void
    {
        $servico = $this->novoServico();
        $servico->registrar('sessao-1', 'primeiro');
        $ultima = $servico->registrar('sessao-1', 'segundo');

        $encontrada = $servico->buscarPorSessao('sessao-1');

        self::assertSame($ultima->id, $encontrada->id);
    }

    public function testBuscarPorSessaoRetornaNuloQuandoNaoHaGravacao(): void
    {
        $servico = $this->novoServico();

        self::assertNull($servico->buscarPorSessao('sessao-1'));
    }

    public function testTranscreverTextoDevolveOTextoDosSegmentosSemPersistirNada(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(
            text: 'ignorado quando há segments',
            segments: [
                ['text' => 'eu quis dizer', 'inicio' => 0.0, 'fim' => 1.2],
                ['text' => 'quero dizer', 'inicio' => 1.3, 'fim' => 2.5],
            ]
        )));

        $texto = $servico->transcreverTexto('bytes-do-turno');

        self::assertSame('eu quis dizer quero dizer', $texto);
        self::assertNull($servico->buscarPorSessao('sessao-1'));
        self::assertCount(0, $servico->listarPendentes());
    }

    public function testTranscreverTextoUsaTextoCorridoQuandoNaoHaSegments(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(text: '  fala única sem pausas  ')));

        self::assertSame('fala única sem pausas', $servico->transcreverTexto('bytes-do-turno'));
    }

    public function testTranscreverTextoDevolveVazioQuandoNaoHaFalaReconhecida(): void
    {
        $servico = $this->novoServico(new TranscricaoStub(new TranscriptionResultDTO(text: '')));

        self::assertSame('', $servico->transcreverTexto('bytes-de-silencio'));
    }
}

