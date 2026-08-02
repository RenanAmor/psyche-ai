<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\GravacaoAudioDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Exceptions\TranscricaoFalhouException;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoCommand;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoHandler;
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
        private readonly TranscreverGravacaoAudioHandler $transcreverGravacao = new TranscreverGravacaoAudioHandler()
    ) {
    }

    public function registrar(string $sessaoId, string $audioBinario): GravacaoAudioDTO
    {
        if ($this->sessaoRepository->findById($sessaoId) === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $id = $this->uuidGenerator->generate();
        $caminho = sprintf('sessoes/%s/%s.%s', $sessaoId, $id, self::EXTENSAO_ARQUIVO);

        $this->storage->put($caminho, $audioBinario);

        $gravacao = $this->registrarGravacao
            ->handle(new RegistrarGravacaoAudioCommand($id, $sessaoId, $caminho))
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
