<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\MensagemApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\AI\RespostaFixaService;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class MensagemApplicationServiceTest extends SQLiteTestCase
{
    private MensagemApplicationService $service;
    private SQLiteSessaoRepository $sessaoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $this->sessaoRepository = new SQLiteSessaoRepository($this->pdo);

        $this->service = new MensagemApplicationService(
            $this->sessaoRepository,
            new RandomUuidGenerator(),
            new RespostaFixaService()
        );

        (new SujeitoApplicationService($sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');
        (new SessaoApplicationService($this->sessaoRepository, $sujeitoRepository))
            ->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testEnviarCriaUmDiscursoNaPrimeiraMensagemERegistraDoisEventos(): void
    {
        $resultado = $this->service->enviar('sessao-1', 'Estou me sentindo ansioso hoje.');

        $this->assertSame('Estou me sentindo ansioso hoje.', $resultado->mensagemUsuario->conteudo);
        $this->assertSame(0, $resultado->mensagemUsuario->posicao);

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resultado->respostaSistema->conteudo);
        $this->assertSame(1, $resultado->respostaSistema->posicao);

        $sessaoRecuperada = $this->sessaoRepository->findById('sessao-1');
        $this->assertCount(1, $sessaoRecuperada->discursos());
        $this->assertCount(2, $sessaoRecuperada->discursos()[0]->eventos());
    }

    public function testEnviarReutilizaOMesmoDiscursoEContinuaAPosicaoEmMensagensSeguintes(): void
    {
        $this->service->enviar('sessao-1', 'Primeira mensagem.');
        $resultado = $this->service->enviar('sessao-1', 'Segunda mensagem.');

        $this->assertSame(2, $resultado->mensagemUsuario->posicao);
        $this->assertSame(3, $resultado->respostaSistema->posicao);

        $sessaoRecuperada = $this->sessaoRepository->findById('sessao-1');
        $this->assertCount(1, $sessaoRecuperada->discursos());
        $this->assertCount(4, $sessaoRecuperada->discursos()[0]->eventos());
    }

    public function testEnviarLancaExcecaoQuandoASessaoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->enviar('sessao-inexistente', 'Olá');
    }

    public function testEnviarLancaExcecaoQuandoOConteudoEVazio(): void
    {
        $this->expectException(ComandoInvalidoException::class);

        $this->service->enviar('sessao-1', '   ');
    }

    public function testMensagemUsuarioERespostaSistemaCarregamOMesmoDiscursoESessao(): void
    {
        $resultado = $this->service->enviar('sessao-1', 'Olá');

        $this->assertSame($resultado->mensagemUsuario->discursoId, $resultado->respostaSistema->discursoId);
        $this->assertSame('sessao-1', $resultado->mensagemUsuario->sessaoId);
        $this->assertSame('sessao-1', $resultado->respostaSistema->sessaoId);
    }

    public function testEnviarRepassaOSujeitoIdDaSessaoParaARespostaAutomatica(): void
    {
        $espiao = new class implements RespostaAutomaticaInterface {
            public ?string $sujeitoIdRecebido = null;
            public ?string $sessaoIdRecebido = null;

            public function responder(string $mensagemUsuario, string $sujeitoId = '', string $sessaoId = ''): string
            {
                $this->sujeitoIdRecebido = $sujeitoId;
                $this->sessaoIdRecebido = $sessaoId;

                return 'resposta do espião';
            }
        };

        $service = new MensagemApplicationService($this->sessaoRepository, new RandomUuidGenerator(), $espiao);
        $service->enviar('sessao-1', 'Olá');

        $this->assertSame('sujeito-1', $espiao->sujeitoIdRecebido);
        $this->assertSame('sessao-1', $espiao->sessaoIdRecebido);
    }
}
