<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\GravacaoAudioDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Exceptions\TranscricaoFalhouException;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoCommand;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoHandler;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoCommand;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoHandler;
use PsycheAI\Application\UseCases\RegistrarGravacaoAudio\RegistrarGravacaoAudioCommand;
use PsycheAI\Application\UseCases\RegistrarGravacaoAudio\RegistrarGravacaoAudioHandler;
use PsycheAI\Application\UseCases\TranscreverGravacaoAudio\TranscreverGravacaoAudioCommand;
use PsycheAI\Application\UseCases\TranscreverGravacaoAudio\TranscreverGravacaoAudioHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Repositories\GravacaoAudioRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Infrastructure\Contracts\StorageInterface;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use RuntimeException;
use Throwable;

/**
 * Orquestra a captura de áudio da sessão (Sprint 22): recebe a gravação
 * contínua bruta e, depois, o pipeline assíncrono de transcrição que a
 * divide em EventoDiscursivo — mesmo tratamento que uma mensagem
 * digitada já recebe (MensagemApplicationService), preservando o áudio
 * original intacto (StorageInterface) para que o analista possa ouvi-lo.
 */
final class GravacaoAudioApplicationService implements ApplicationServiceInterface
{
    private const CONTEUDO_PADRAO_DISCURSO = 'Conversa';
    private const EXTENSAO_ARQUIVO = 'webm';

    public function __construct(
        private readonly GravacaoAudioRepository $gravacaoAudioRepository,
        private readonly SessaoRepository $sessaoRepository,
        private readonly StorageInterface $storage,
        private readonly TranscriptionInterface $transcricao,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly RegistrarGravacaoAudioHandler $registrarGravacao = new RegistrarGravacaoAudioHandler(),
        private readonly RegistrarDiscursoHandler $registrarDiscurso = new RegistrarDiscursoHandler(),
        private readonly TranscreverGravacaoAudioHandler $transcreverGravacao = new TranscreverGravacaoAudioHandler(),
        private readonly RegistrarEventoDiscursivoHandler $registrarEvento = new RegistrarEventoDiscursivoHandler()
    ) {
    }

    public function registrar(string $sessaoId, string $audioBinario, Locutor $locutor = Locutor::Sujeito): GravacaoAudioDTO
    {
        if ($this->sessaoRepository->findById($sessaoId) === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $id = $this->uuidGenerator->generate();
        $caminho = sprintf('sessoes/%s/%s.%s', $sessaoId, $id, self::EXTENSAO_ARQUIVO);

        $this->storage->put($caminho, $audioBinario);

        $gravacao = $this->registrarGravacao
            ->handle(new RegistrarGravacaoAudioCommand($id, $sessaoId, $caminho, $locutor))
            ->gravacao();

        $this->gravacaoAudioRepository->save($gravacao);

        return GravacaoAudioDTO::fromEntity($gravacao);
    }

    /**
     * Chamado pelo worker assíncrono (bin/transcrever-gravacoes.php) para
     * cada GravacaoAudio pendente. Em caso de falha (leitura do áudio ou
     * do provedor de transcrição), marca a gravação como Falha e relança
     * como TranscricaoFalhouException, para que o worker siga para a
     * próxima gravação em vez de interromper o lote inteiro.
     */
    public function transcrever(string $gravacaoId): GravacaoAudioDTO
    {
        $gravacao = $this->gravacaoAudioRepository->findById($gravacaoId);

        if ($gravacao === null) {
            throw RecursoNaoEncontradoException::paraId('GravacaoAudio', $gravacaoId);
        }

        try {
            $segmentos = $this->transcreverAudio($gravacao);

            $sessao = $this->sessaoRepository->findById($gravacao->sessaoId());

            if ($sessao === null) {
                throw new RuntimeException(sprintf('Sessao "%s" não encontrada.', $gravacao->sessaoId()));
            }

            $discurso = $this->discursoDaConversa($sessao);

            $this->transcreverGravacao->handle(new TranscreverGravacaoAudioCommand($discurso, $gravacao, $segmentos));

            $this->sessaoRepository->save($sessao);
            $this->gravacaoAudioRepository->save($gravacao);

            return GravacaoAudioDTO::fromEntity($gravacao);
        } catch (Throwable $erro) {
            $gravacao->marcarFalha();
            $this->gravacaoAudioRepository->save($gravacao);

            throw TranscricaoFalhouException::paraGravacao($gravacaoId, $erro);
        }
    }

    /**
     * Exposto para o worker assíncrono (bin/transcrever-gravacoes.php)
     * saber quais gravações ainda precisam ser processadas, sem que o
     * script CLI acesse o repositório de Domínio diretamente — mesma
     * disciplina de qualquer outro consumidor de Application Service.
     *
     * @return GravacaoAudioDTO[]
     */
    public function listarPendentes(): array
    {
        return array_map(
            GravacaoAudioDTO::fromEntity(...),
            $this->gravacaoAudioRepository->findPendentesDeTranscricao()
        );
    }

    public function buscarPorSessao(string $sessaoId): ?GravacaoAudioDTO
    {
        $gravacoes = $this->gravacaoAudioRepository->findBySessaoId($sessaoId);
        $ultima = $gravacoes[count($gravacoes) - 1] ?? null;

        return $ultima === null ? null : GravacaoAudioDTO::fromEntity($ultima);
    }

    /**
     * Transcreve um único turno de fala (Sprint 32, Interface de Voz da
     * ECO) e devolve só o texto — ao contrário de registrar()/transcrever(),
     * não persiste nada (nenhum GravacaoAudio, Discurso ou EventoDiscursivo).
     * Existe para que a Presentation Web tenha um caminho síncrono
     * voz→texto reaproveitando o mesmo TranscriptionInterface (Whisper) já
     * usado pelo pipeline assíncrono, sem duplicar turnos: o texto
     * devolvido aqui segue para MensagemApplicationService::enviar() (via
     * POST /sessions/{id}/messages), que é quem de fato grava o turno do
     * Sujeito e gera a resposta socrática — o mesmo caminho que uma
     * mensagem digitada já percorre.
     */
    public function transcreverTexto(string $audioBinario): string
    {
        $arquivoTemporario = tempnam(sys_get_temp_dir(), 'psyche-audio-');
        file_put_contents($arquivoTemporario, $audioBinario);

        try {
            $resultado = $this->transcricao->transcribe($arquivoTemporario);
        } finally {
            unlink($arquivoTemporario);
        }

        if ($resultado->segments !== []) {
            return trim(implode(' ', array_map(
                static fn (array $segmento): string => trim($segmento['text']),
                $resultado->segments
            )));
        }

        return trim($resultado->text);
    }

    /**
     * Exposto para a camada HTTP servir a reprodução do áudio original ao
     * analista (Regra 2: o áudio bruto nunca é editado, só lido).
     */
    public function bytesDoArquivo(string $gravacaoId): string
    {
        $gravacao = $this->gravacaoAudioRepository->findById($gravacaoId);

        if ($gravacao === null) {
            throw RecursoNaoEncontradoException::paraId('GravacaoAudio', $gravacaoId);
        }

        return $this->storage->get($gravacao->caminhoArmazenamento());
    }

    /**
     * Processa as trilhas de áudio de uma videochamada encerrada (uma por
     * participante, Sprint da Videochamada Embutida) — transcreve cada
     * trilha separadamente (cada uma tem um único locutor, determinístico)
     * e depois FUNDE os segmentos de todas as trilhas por horário absoluto
     * (offsetInicioSegundos da trilha + inicio do segmento dentro dela)
     * antes de registrar os EventoDiscursivo. Sem esse merge cronológico,
     * o resultado seria "toda fala do Analista antes de toda fala do
     * Sujeito" em vez da ordem real da conversa. Chamado pelo worker
     * assíncrono (bin/processar-chamadas-de-video.php), nunca por uma
     * rota HTTP síncrona.
     *
     * @param array<int, array{locutor: Locutor, bytes: string, offsetInicioSegundos: float}> $trilhas
     */
    public function processarTrilhasDeChamada(string $sessaoId, array $trilhas): void
    {
        $sessao = $this->sessaoRepository->findById($sessaoId);

        if ($sessao === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $segmentosFundidos = [];

        foreach ($trilhas as $trilha) {
            $id = $this->uuidGenerator->generate();
            $caminho = sprintf('sessoes/%s/%s.%s', $sessaoId, $id, self::EXTENSAO_ARQUIVO);
            $this->storage->put($caminho, $trilha['bytes']);

            $gravacao = $this->registrarGravacao
                ->handle(new RegistrarGravacaoAudioCommand($id, $sessaoId, $caminho, $trilha['locutor']))
                ->gravacao();

            foreach ($this->transcreverAudioComTempos($gravacao) as $segmento) {
                $segmentosFundidos[] = [
                    'id' => $segmento['id'],
                    'texto' => $segmento['texto'],
                    'locutor' => $trilha['locutor'],
                    'inicioAbsoluto' => $trilha['offsetInicioSegundos'] + $segmento['inicio'],
                ];
            }

            $gravacao->marcarTranscrita();
            $this->gravacaoAudioRepository->save($gravacao);
        }

        usort($segmentosFundidos, static fn (array $a, array $b): int => $a['inicioAbsoluto'] <=> $b['inicioAbsoluto']);

        $discurso = $this->discursoDaConversa($sessao);
        $posicaoInicial = count($discurso->eventos());

        foreach (array_values($segmentosFundidos) as $indice => $segmento) {
            $this->registrarEvento->handle(new RegistrarEventoDiscursivoCommand(
                $discurso,
                $segmento['id'],
                $segmento['texto'],
                $posicaoInicial + $indice,
                $segmento['locutor']
            ));
        }

        $this->sessaoRepository->save($sessao);
    }

    /**
     * @return array<int, array{id: string, texto: string}>
     */
    private function transcreverAudio(GravacaoAudio $gravacao): array
    {
        $bytes = $this->storage->get($gravacao->caminhoArmazenamento());

        $arquivoTemporario = tempnam(sys_get_temp_dir(), 'psyche-audio-');
        file_put_contents($arquivoTemporario, $bytes);

        try {
            $resultado = $this->transcricao->transcribe($arquivoTemporario);
        } finally {
            unlink($arquivoTemporario);
        }

        if ($resultado->segments !== []) {
            return array_map(
                fn (array $segmento): array => ['id' => $this->uuidGenerator->generate(), 'texto' => $segmento['text']],
                array_values(array_filter($resultado->segments, static fn (array $s): bool => trim($s['text']) !== ''))
            );
        }

        if (trim($resultado->text) !== '') {
            return [['id' => $this->uuidGenerator->generate(), 'texto' => $resultado->text]];
        }

        return [];
    }

    /**
     * Variante de transcreverAudio() que preserva o `inicio` de cada
     * segmento dentro da trilha — necessário para o merge cronológico de
     * processarTrilhasDeChamada(), que soma esse valor ao offset de início
     * da trilha na chamada.
     *
     * @return array<int, array{id: string, texto: string, inicio: float}>
     */
    private function transcreverAudioComTempos(GravacaoAudio $gravacao): array
    {
        $bytes = $this->storage->get($gravacao->caminhoArmazenamento());

        $arquivoTemporario = tempnam(sys_get_temp_dir(), 'psyche-audio-');
        file_put_contents($arquivoTemporario, $bytes);

        try {
            $resultado = $this->transcricao->transcribe($arquivoTemporario);
        } finally {
            unlink($arquivoTemporario);
        }

        if ($resultado->segments !== []) {
            return array_map(
                fn (array $segmento): array => [
                    'id' => $this->uuidGenerator->generate(),
                    'texto' => $segmento['text'],
                    'inicio' => (float) $segmento['inicio'],
                ],
                array_values(array_filter($resultado->segments, static fn (array $s): bool => trim($s['text']) !== ''))
            );
        }

        if (trim($resultado->text) !== '') {
            return [['id' => $this->uuidGenerator->generate(), 'texto' => $resultado->text, 'inicio' => 0.0]];
        }

        return [];
    }

    private function discursoDaConversa(Sessao $sessao): Discurso
    {
        $existente = $sessao->discursos()[0] ?? null;

        if ($existente !== null) {
            return $existente;
        }

        return $this->registrarDiscurso
            ->handle(new RegistrarDiscursoCommand($sessao, $this->uuidGenerator->generate(), self::CONTEUDO_PADRAO_DISCURSO))
            ->discurso();
    }
}
