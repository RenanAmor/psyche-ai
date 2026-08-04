<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel;

final class EventosDiscursivosController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'events';
    }

    protected function rota(): string
    {
        return '/eventos-discursivos';
    }

    protected function view(): string
    {
        return 'eventos-discursivos/index';
    }

    protected function tituloPagina(): string
    {
        return 'Eventos Discursivos';
    }

    protected function chaveViewModel(): string
    {
        return 'eventos';
    }

    protected function mapearViewModels(array $dados): array
    {
        return EventoDiscursivoViewModel::fromArrayList($dados);
    }

    /**
     * Sobrescreve o index() genérico para aceitar um filtro por Sessão
     * (?sessaoId=...) — GET /events não suporta filtro no servidor, então
     * o filtro é aplicado aqui, sobre a lista completa já trazida (volume
     * baixo, ambiente de um único analista).
     */
    public function index(Request $request): Response
    {
        $filtroSessaoId = trim((string) $request->query('sessaoId', ''));

        $resposta = $this->httpClient->get($this->recurso());

        if (!$resposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        $dados = $resposta->dados;

        if ($filtroSessaoId !== '') {
            $dados = array_values(array_filter(
                $dados,
                static fn (array $evento): bool => ($evento['sessaoId'] ?? null) === $filtroSessaoId
            ));
        }

        $html = $this->viewRenderer->renderComLayout(
            $this->view(),
            [$this->chaveViewModel() => $this->mapearViewModels($dados), 'filtroSessaoId' => $filtroSessaoId],
            $this->tituloPagina(),
            $this->rota()
        );

        return Response::ok($html);
    }
}
