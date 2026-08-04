<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\EventoDiscursivoDTO;
use PsycheAI\Application\DTOs\MensagemEnviadaDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoCommand;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoHandler;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoCommand;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Orquestra o caso de uso "enviar mensagem" (Sprint 12): registra a
 * mensagem do usuário e a resposta automática do sistema como dois
 * EventoDiscursivo dentro do único Discurso da Sessao (criado sob
 * demanda no primeiro envio), cada um com seu Locutor declarado
 * explicitamente (Sujeito/Sistema) — desde a Videochamada Embutida, a
 * paridade da Posicao deixou de ser a fonte da verdade sobre quem falou.
 *
 * Depende de RespostaAutomaticaInterface (Infrastructure/Contracts) só
 * pela interface: a implementação concreta injetada é quem decide o
 * texto da resposta, mantendo esta Application Service alheia a
 * qualquer futura substituição pelos motores Freud/Lacan.
 */
final class MensagemApplicationService implements ApplicationServiceInterface
{
    private const CONTEUDO_PADRAO_DISCURSO = 'Conversa';

    public function __construct(
        private readonly SessaoRepository $sessaoRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly RespostaAutomaticaInterface $respostaAutomatica,
        private readonly RegistrarDiscursoHandler $registrarDiscurso = new RegistrarDiscursoHandler(),
        private readonly RegistrarEventoDiscursivoHandler $registrarEvento = new RegistrarEventoDiscursivoHandler()
    ) {
    }

    public function enviar(string $sessaoId, string $conteudo): MensagemEnviadaDTO
    {
        $sessao = $this->sessaoRepository->findById($sessaoId);

        if ($sessao === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $discurso = $this->discursoDaConversa($sessao);
        $proximaPosicao = count($discurso->eventos());

        $mensagemUsuario = $this->registrarEvento
            ->handle(new RegistrarEventoDiscursivoCommand(
                $discurso,
                $this->uuidGenerator->generate(),
                $conteudo,
                $proximaPosicao,
                Locutor::Sujeito
            ))
            ->evento();

        $sujeitoId = $this->sessaoRepository->sujeitoIdDaSessao($sessaoId) ?? '';
        $textoResposta = $this->respostaAutomatica->responder($conteudo, $sujeitoId, $sessaoId);

        $respostaSistema = $this->registrarEvento
            ->handle(new RegistrarEventoDiscursivoCommand(
                $discurso,
                $this->uuidGenerator->generate(),
                $textoResposta,
                $proximaPosicao + 1,
                Locutor::Sistema
            ))
            ->evento();

        $this->sessaoRepository->save($sessao);

        return new MensagemEnviadaDTO(
            EventoDiscursivoDTO::fromEntity($mensagemUsuario, $discurso->id()->valor(), $sessaoId),
            EventoDiscursivoDTO::fromEntity($respostaSistema, $discurso->id()->valor(), $sessaoId)
        );
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
