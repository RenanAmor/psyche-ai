<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use DateInterval;
use DateTimeImmutable;
use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\ChamadaSessaoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\RegistrarChamadaSessao\RegistrarChamadaSessaoCommand;
use PsycheAI\Application\UseCases\RegistrarChamadaSessao\RegistrarChamadaSessaoHandler;
use PsycheAI\Domain\Entities\ChamadaSessao;
use PsycheAI\Domain\Repositories\ChamadaSessaoRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use PsycheAI\Infrastructure\Contracts\VideoConferenceInterface;

/**
 * Orquestra a videochamada embutida (Daily.co): cria/reaproveita uma sala
 * por Sessao e emite o link mágico (token opaco, persistido) que o
 * analista envia ao Sujeito para entrar sem login/conta. O meeting token
 * do Daily (JWT de curta duração, com o `userId` que identifica o locutor
 * de cada trilha gravada) nunca é persistido — é gerado sob demanda a
 * cada entrada na sala, por analista() ou entrarComToken().
 */
final class ChamadaDeVideoApplicationService implements ApplicationServiceInterface
{
    private const USER_ID_ANALISTA = 'analista';
    private const NOME_ANALISTA = 'Analista';
    private const NOME_SUJEITO = 'Sujeito';

    public function __construct(
        private readonly ChamadaSessaoRepository $chamadaRepository,
        private readonly SessaoRepository $sessaoRepository,
        private readonly VideoConferenceInterface $videoConference,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly GravacaoAudioApplicationService $gravacoesAudio,
        private readonly int $ttlHoras = 24,
        private readonly RegistrarChamadaSessaoHandler $registrarChamada = new RegistrarChamadaSessaoHandler()
    ) {
    }

    /**
     * @return array{chamada: ChamadaSessaoDTO, tokenAnalista: string, tokenAcesso: string}
     */
    public function iniciar(string $sessaoId): array
    {
        if ($this->sessaoRepository->findById($sessaoId) === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $agora = new DateTimeImmutable();
        $chamada = $this->chamadaAtivaOuNova($sessaoId, $agora);

        $tokenAnalista = $this->videoConference->criarTokenDeAcesso(
            $chamada->salaProvedorId(),
            self::USER_ID_ANALISTA,
            self::NOME_ANALISTA,
            true
        );

        return [
            'chamada' => ChamadaSessaoDTO::fromEntity($chamada),
            'tokenAnalista' => $tokenAnalista,
            // tokenAcesso (link mágico) só é devolvido aqui, ao analista que
            // acabou de iniciar a chamada — nunca fica no DTO/response
            // genérico de ChamadaSessao, que outros endpoints também expõem.
            'tokenAcesso' => $chamada->tokenAcesso(),
        ];
    }

    /**
     * @return array{salaUrl: string, tokenSujeito: string}
     */
    public function entrarComToken(string $token): array
    {
        $chamada = $this->chamadaRepository->findByTokenAcesso($token);

        if ($chamada === null || !$chamada->tokenValido(new DateTimeImmutable())) {
            throw RecursoNaoEncontradoException::paraId('ChamadaSessao', $token);
        }

        $sujeitoId = $this->sessaoRepository->sujeitoIdDaSessao($chamada->sessaoId()) ?? 'desconhecido';

        $tokenSujeito = $this->videoConference->criarTokenDeAcesso(
            $chamada->salaProvedorId(),
            'sujeito:' . $sujeitoId,
            self::NOME_SUJEITO,
            false
        );

        return [
            'salaUrl' => $chamada->salaUrl(),
            'tokenSujeito' => $tokenSujeito,
        ];
    }

    public function encerrar(string $sessaoId): void
    {
        $chamada = $this->chamadaRepository->findBySessaoId($sessaoId);

        if ($chamada === null) {
            throw RecursoNaoEncontradoException::paraId('ChamadaSessao', $sessaoId);
        }

        $chamada->encerrar(new DateTimeImmutable());
        $this->chamadaRepository->save($chamada);

        $this->videoConference->encerrarSala($chamada->salaProvedorId());
    }

    /**
     * Exposto para o worker assíncrono (bin/processar-chamadas-de-video.php)
     * — mesma disciplina de GravacaoAudioApplicationService::listarPendentes().
     *
     * @return ChamadaSessaoDTO[]
     */
    public function listarEncerradasNaoProcessadas(): array
    {
        return array_map(
            ChamadaSessaoDTO::fromEntity(...),
            $this->chamadaRepository->findEncerradasNaoProcessadas()
        );
    }

    /**
     * Busca as trilhas finalizadas no Daily.co, baixa cada uma e delega o
     * merge cronológico + transcrição para
     * GravacaoAudioApplicationService::processarTrilhasDeChamada() — só
     * marca a ChamadaSessao como processada se pelo menos uma gravação
     * "finished" foi encontrada, para o worker tentar de novo no próximo
     * ciclo caso o Daily ainda não tenha terminado de processar a gravação.
     */
    public function processarGravacoes(string $sessaoId): void
    {
        $chamada = $this->chamadaRepository->findBySessaoId($sessaoId);

        if ($chamada === null) {
            throw RecursoNaoEncontradoException::paraId('ChamadaSessao', $sessaoId);
        }

        $gravacoesFinalizadas = $this->videoConference->buscarGravacoesFinalizadas($chamada->salaProvedorId());

        if ($gravacoesFinalizadas === []) {
            return;
        }

        $trilhas = [];

        foreach ($gravacoesFinalizadas as $gravacao) {
            foreach ($gravacao->tracks as $track) {
                $trilhas[] = [
                    'locutor' => str_starts_with($track->userId, 'sujeito') ? Locutor::Sujeito : Locutor::Analista,
                    'bytes' => $this->videoConference->baixarTrilha($track),
                    'offsetInicioSegundos' => $track->offsetInicioSegundos,
                ];
            }
        }

        if ($trilhas !== []) {
            $this->gravacoesAudio->processarTrilhasDeChamada($sessaoId, $trilhas);
        }

        $chamada->marcarProcessada(new DateTimeImmutable());
        $this->chamadaRepository->save($chamada);
    }

    private function chamadaAtivaOuNova(string $sessaoId, DateTimeImmutable $agora): ChamadaSessao
    {
        $existente = $this->chamadaRepository->findBySessaoId($sessaoId);

        if ($existente !== null && $existente->tokenValido($agora)) {
            return $existente;
        }

        $nomeSala = sprintf('psycheai-%s-%s', substr($sessaoId, 0, 8), bin2hex(random_bytes(4)));
        $sala = $this->videoConference->criarSala($nomeSala);

        $chamada = $this->registrarChamada
            ->handle(new RegistrarChamadaSessaoCommand(
                id: $existente?->id()->valor() ?? $this->uuidGenerator->generate(),
                sessaoId: $sessaoId,
                salaProvedorId: $sala->nomeSala,
                salaUrl: $sala->url,
                tokenAcesso: bin2hex(random_bytes(24)),
                criadaEm: $agora,
                expiraEm: $agora->add(new DateInterval('PT' . $this->ttlHoras . 'H'))
            ))
            ->chamada();

        $this->chamadaRepository->save($chamada);

        return $chamada;
    }
}
