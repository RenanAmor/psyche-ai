<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\CircuitoRecorrenciaViewModel;
use PsycheAI\Presentation\Web\ViewModels\ObservacaoViewModel;
use PsycheAI\Presentation\Web\ViewModels\RecorrenciaViewModel;
use PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel;

/**
 * Tela do Discourse Engine (Sprint 14) + Motor Freud (Sprint 15) + Motor
 * Lacan (Sprint 16): mostra o Sujeito e as Recorrências/Observações
 * recalculadas a cada consulta pela API (GET /subjects/{id},
 * GET /subjects/{id}/observations?vocabulario=lacan) lado a lado com o
 * rótulo lacaniano — absorve o que seria um "Observador Clínico" próprio,
 * já que essa combinação é o suficiente, sem nenhuma hipótese,
 * interpretação ou diagnóstico além do que se repete no discurso
 * registrado. Não estende AbstractResourceController/
 * AbstractCrudResourceController pelo mesmo motivo de
 * HistoricoSujeitoController: o ceremonial genérico de listagem/CRUD não
 * se aplica a uma página que combina duas respostas de uma vez.
 *
 * Desde a revisão pós-Sprint 16, a mesma tela é estendida (não
 * bifurcada) com o circuito/trajeto de cada Recorrencia
 * (GET /subjects/{id}/observations/circuito?vocabulario=lacan) via
 * CircuitoTrajetoComponent — "mapear a pulsão, todo o caminho".
 */
final class ObservacoesSujeitoController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function mostrar(Request $request): Response
    {
        $sujeitoId = (string) $request->routeParam('id', '');
        $rota = '/sujeitos/' . $sujeitoId . '/observacoes';

        $sujeitoResposta = $this->httpClient->get('subjects/' . $sujeitoId);

        if (!$sujeitoResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($sujeitoResposta), $this->viewRenderer, $rota);
        }

        $observacoesResposta = $this->httpClient->get(
            'subjects/' . $sujeitoId . '/observations',
            ['vocabulario' => 'lacan']
        );

        if (!$observacoesResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($observacoesResposta), $this->viewRenderer, $rota);
        }

        $circuitoResposta = $this->httpClient->get(
            'subjects/' . $sujeitoId . '/observations/circuito',
            ['vocabulario' => 'lacan']
        );

        if (!$circuitoResposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($circuitoResposta), $this->viewRenderer, $rota);
        }

        $sujeito = SujeitoViewModel::fromArray($sujeitoResposta->dados);

        /** @var array<string, mixed> $dadosObservacoes */
        $dadosObservacoes = $observacoesResposta->dados;

        $recorrencias = RecorrenciaViewModel::fromArrayList(
            is_array($dadosObservacoes['recorrencias'] ?? null) ? $dadosObservacoes['recorrencias'] : []
        );
        $observacoes = ObservacaoViewModel::fromArrayList(
            is_array($dadosObservacoes['observacoes'] ?? null) ? $dadosObservacoes['observacoes'] : []
        );

        /** @var array<string, mixed> $dadosCircuito */
        $dadosCircuito = $circuitoResposta->dados;

        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList(
            is_array($dadosCircuito['circuitos'] ?? null) ? $dadosCircuito['circuitos'] : []
        );

        $html = $this->viewRenderer->renderComLayout(
            'observacoes/mostrar',
            [
                'sujeito' => $sujeito,
                'recorrencias' => $recorrencias,
                'observacoes' => $observacoes,
                'circuitos' => $circuitos,
            ],
            sprintf('Observações de %s', $sujeito->nome),
            $rota
        );

        return Response::ok($html);
    }

    private function erroDe(ApiResponse $resposta): ErrorViewModel
    {
        /** @var ErrorViewModel $erro */
        $erro = $resposta->erro;

        return $erro;
    }
}
