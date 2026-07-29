<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Ceremonial comum às quatro páginas com CRUD completo (Sujeitos,
 * Sessões, Discursos, Memórias): "mostrar" e "excluir" são idênticas em
 * todas elas — buscar/remover pelo id da rota, tratar a falha e
 * renderizar. "novo"/"store"/"editar"/"atualizar" ficam em cada
 * Controller concreto, pois os campos de formulário divergem por
 * recurso. EventosDiscursivos não estende esta classe: permanece
 * somente para consulta, sem estas ações.
 */
abstract class AbstractCrudResourceController extends AbstractResourceController
{
    abstract protected function viewMostrar(): string;

    abstract protected function chaveViewModelUnico(): string;

    public function mostrar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->get($this->recurso() . '/' . $id);

        if (!$resposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        $item = $this->mapearViewModelUnico($resposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            $this->viewMostrar(),
            [$this->chaveViewModelUnico() => $item],
            $this->tituloPagina(),
            $this->rota()
        );

        return Response::ok($html);
    }

    public function excluir(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->delete($this->recurso() . '/' . $id);

        if (!$resposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }

    /**
     * Verdadeiro quando a falha da API é de validação/requisição
     * inválida (400/409/422) — casos em que o formulário deve ser
     * reexibido com a mensagem, em vez de substituir a página inteira
     * pela tela de erro.
     */
    protected function ehFalhaDeFormulario(ApiResponse $resposta): bool
    {
        return $resposta->erro?->tipo === ErrorType::VALIDACAO;
    }
}
