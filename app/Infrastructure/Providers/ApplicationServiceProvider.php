<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Providers;

use PDO;
use PsycheAI\Application\Services\AnalistaApplicationService;
use PsycheAI\Application\Services\ConsolidacaoApplicationService;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\GravacaoAudioApplicationService;
use PsycheAI\Application\Services\LinhaDoTempoApplicationService;
use PsycheAI\Application\Services\MemoriaApplicationService;
use PsycheAI\Application\Services\MensagemApplicationService;
use PsycheAI\Application\Services\ObservacaoApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaHandler;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaHandler;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Infrastructure\AI\AnthropicLLMService;
use PsycheAI\Infrastructure\AI\ClassificadorFreudianoLLM;
use PsycheAI\Infrastructure\AI\GeradorDePerguntaSocraticaLLM;
use PsycheAI\Infrastructure\AI\OpenAIWhisperTranscriptionService;
use PsycheAI\Infrastructure\AI\RespostaEcoRecorrenciaService;
use PsycheAI\Infrastructure\AI\RespostaSocraticaService;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;
use PsycheAI\Infrastructure\Contracts\StorageInterface;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Persistence\SQLite\Migrations\MigrationRunner;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteAnalistaRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteGravacaoAudioRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteMemoriaRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Infrastructure\Storage\LocalFilesystemStorage;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;

/**
 * Raiz de composição da aplicação: monta a conexão SQLite, aplica as
 * migrations pendentes e injeta os repositórios concretos (que
 * implementam os contratos de Domínio) nas Application Services.
 *
 * É o único ponto do sistema autorizado a conhecer simultaneamente
 * Application e Infrastructure — as Application Services em si
 * continuam dependendo somente das interfaces de PsycheAI\Domain\Repositories.
 */
final class ApplicationServiceProvider
{
    private function __construct(
        private readonly SujeitoApplicationService $sujeitos,
        private readonly SessaoApplicationService $sessoes,
        private readonly DiscursoApplicationService $discursos,
        private readonly MemoriaApplicationService $memorias,
        private readonly MensagemApplicationService $mensagens,
        private readonly LinhaDoTempoApplicationService $linhaDoTempo,
        private readonly ConsolidacaoApplicationService $consolidacao,
        private readonly ObservacaoApplicationService $observacoes,
        private readonly AnalistaApplicationService $analistas,
        private readonly GravacaoAudioApplicationService $gravacoesAudio,
        private readonly SujeitoRepository $sujeitoRepository
    ) {
    }

    public static function comSQLite(string $databasePath = ':memory:'): self
    {
        $pdo = (new Connection($databasePath))->pdo();

        return self::comPDO($pdo);
    }

    public static function comPDO(
        PDO $pdo,
        ?UuidGeneratorInterface $uuidGenerator = null,
        ?RespostaAutomaticaInterface $respostaAutomatica = null,
        ?ClassificarFormacaoFreudianaHandler $classificarFormacaoFreudiana = null,
        ?StorageInterface $storage = null,
        ?TranscriptionInterface $transcricao = null
    ): self {
        MigrationRunner::comMigrationsPadrao($pdo)->run();

        $sujeitoRepository = new SQLiteSujeitoRepository($pdo);
        $sessaoRepository = new SQLiteSessaoRepository($pdo);
        $discursoRepository = new SQLiteDiscursoRepository($pdo);
        $memoriaRepository = new SQLiteMemoriaRepository($pdo);
        $analistaRepository = new SQLiteAnalistaRepository($pdo);
        $gravacaoAudioRepository = new SQLiteGravacaoAudioRepository($pdo);
        $uuidGenerator ??= new RandomUuidGenerator();
        $classificarFormacaoFreudiana ??= new ClassificarFormacaoFreudianaHandler(
            new ClassificadorFreudianoLLM(new AnthropicLLMService())
        );
        $storage ??= new LocalFilesystemStorage();
        $transcricao ??= new OpenAIWhisperTranscriptionService();
        $respostaAutomatica ??= new RespostaSocraticaService(
            $sujeitoRepository,
            $sessaoRepository,
            new GerarPerguntaSocraticaHandler(new GeradorDePerguntaSocraticaLLM(new AnthropicLLMService())),
            new RespostaEcoRecorrenciaService($sujeitoRepository)
        );

        return new self(
            new SujeitoApplicationService($sujeitoRepository),
            new SessaoApplicationService($sessaoRepository, $sujeitoRepository),
            new DiscursoApplicationService($discursoRepository, $sessaoRepository),
            new MemoriaApplicationService($memoriaRepository),
            new MensagemApplicationService(
                $sessaoRepository,
                $uuidGenerator,
                $respostaAutomatica
            ),
            new LinhaDoTempoApplicationService($sujeitoRepository, $memoriaRepository, $sessaoRepository),
            new ConsolidacaoApplicationService($sujeitoRepository, $memoriaRepository, $sessaoRepository),
            new ObservacaoApplicationService(
                sujeitoRepository: $sujeitoRepository,
                classificarFormacaoFreudiana: $classificarFormacaoFreudiana
            ),
            new AnalistaApplicationService($analistaRepository, $uuidGenerator),
            new GravacaoAudioApplicationService(
                $gravacaoAudioRepository,
                $sessaoRepository,
                $storage,
                $transcricao,
                $uuidGenerator
            ),
            $sujeitoRepository
        );
    }

    public function sujeitos(): SujeitoApplicationService
    {
        return $this->sujeitos;
    }

    public function sessoes(): SessaoApplicationService
    {
        return $this->sessoes;
    }

    public function discursos(): DiscursoApplicationService
    {
        return $this->discursos;
    }

    public function memorias(): MemoriaApplicationService
    {
        return $this->memorias;
    }

    public function mensagens(): MensagemApplicationService
    {
        return $this->mensagens;
    }

    public function linhaDoTempo(): LinhaDoTempoApplicationService
    {
        return $this->linhaDoTempo;
    }

    public function consolidacao(): ConsolidacaoApplicationService
    {
        return $this->consolidacao;
    }

    public function observacoes(): ObservacaoApplicationService
    {
        return $this->observacoes;
    }

    public function analistas(): AnalistaApplicationService
    {
        return $this->analistas;
    }

    public function gravacoesAudio(): GravacaoAudioApplicationService
    {
        return $this->gravacoesAudio;
    }

    /**
     * Exposto para a camada HTTP montar a entidade Sujeito exigida por
     * MemoriaApplicationService::criar()/atualizar() (ver
     * ApplicationServiceProviderTest) — continua sendo o contrato de
     * Domínio, nunca a implementação concreta SQLite.
     */
    public function sujeitoRepository(): SujeitoRepository
    {
        return $this->sujeitoRepository;
    }
}
