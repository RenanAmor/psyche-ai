<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\ConsolidacaoViewModel;
use PsycheAI\Presentation\Web\ViewModels\LinhaDoTempoItemViewModel;
use PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel;

/**
 * Tela de Histórico do Sujeito da Sprint 13: reúne, em uma única
 * página, o Sujeito, sua Linha do Tempo Discursiva (paginada e
 * filtrável por tipo/período/texto) e a Consolidação da sua Memória —
 * as três consultas somente-leitura já expostas pela API
 * (GET /subjects/{id}, GET /subjects/{id}/timeline,
 * GET /subjects/{id}/consolidation). Não estende
 * AbstractResourceController/AbstractCrudResourceController: o
 * ceremonial genérico de listagem/CRUD não se aplica a uma página que
 * combina três respostas de uma vez.
 */
final class HistoricoSujeitoController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function mostrar(Request $request): Response
    {
        $sujeitoId = (string) $request->routeParam('id', '');
        $rota = '/sujeitos/' . $sujeitoId . '/historico';

        $sujeitoResposta = $this->httpClient->get('subjects/' . $sujeitoId);

        if (!$sujeitoResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($sujeitoResposta), $this->viewRenderer, $rota);
        }

        $filtros = $this->filtrosDaRequisicao($request);

        $timelineResposta = $this->httpClient->get('subjects/' . $sujeitoId . '/timeline', $filtros);

        if (!$timelineResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($timelineResposta), $this->viewRenderer, $rota);
        }

        $consolidacaoResposta = $this->httpClient->get('subjects/' . $sujeitoId . '/consolidation');

        if (!$consolidacaoResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($consolidacaoResposta), $this->viewRenderer, $rota);
        }

        /** @var array<string, mixed> $dadosTimeline */
        $dadosTimeline = $timelineResposta->dados;

        $sujeito = SujeitoViewModel::fromArray($sujeitoResposta->dados);
        $itens = LinhaDoTempoItemViewModel::fromArrayList(
            is_array($dadosTimeline['itens'] ?? null) ? $dadosTimeline['itens'] : []
        );
        $consolidacao = ConsolidacaoViewModel::fromArray($consolidacaoResposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            'historico/mostrar',
            [
                'sujeito' => $sujeito,
                'itens' => $itens,
                'consolidacao' => $consolidacao,
                'pagina' => (int) ($dadosTimeline['pagina'] ?? 1),
                'porPagina' => (int) ($dadosTimeline['porPagina'] ?? 0),
                'total' => (int) ($dadosTimeline['total'] ?? 0),
                'totalDePaginas' => (int) ($dadosTimeline['totalDePaginas'] ?? 0),
                'filtros' => $filtros,
                'rota' => $rota,
            ],
            sprintf('Histórico de %s', $sujeito->nome),
            $rota
        );

        return Response::ok($html);
    }

    /**
     * @return array<string, string>
     */
    private function filtrosDaRequisicao(Request $request): array
    {
        $filtros = [];

        foreach (['tipo', 'de', 'ate', 'q'] as $campo) {
            $valor = $request->query($campo);

            if ($valor !== null && $valor !== '') {
                $filtros[$campo] = $valor;
            }
        }

        $pagina = $request->query('pagina');
        $filtros['pagina'] = ($pagina !== null && $pagina !== '') ? $pagina : '1';

        return $filtros;
    }

    private function erroDe(ApiResponse $resposta): ErrorViewModel
    {
        /** @var ErrorViewModel $erro */
        $erro = $resposta->erro;

        return $erro;
    }
}
