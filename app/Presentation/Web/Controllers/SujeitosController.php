<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel;

final class SujeitosController extends AbstractCrudResourceController
{
    protected function recurso(): string
    {
        return 'subjects';
    }

    protected function rota(): string
    {
        return '/sujeitos';
    }

    protected function view(): string
    {
        return 'sujeitos/index';
    }

    protected function viewMostrar(): string
    {
        return 'sujeitos/mostrar';
    }

    protected function tituloPagina(): string
    {
        return 'Sujeitos';
    }

    protected function chaveViewModel(): string
    {
        return 'sujeitos';
    }

    protected function chaveViewModelUnico(): string
    {
        return 'sujeito';
    }

    protected function mapearViewModels(array $dados): array
    {
        return SujeitoViewModel::fromArrayList($dados);
    }

    /**
     * Exibe o formulário de cadastro. "erros" e "valores" só chegam
     * preenchidos quando store() re-renderiza a página após uma
     * validação básica de entrada (nunca uma regra de negócio) falhar.
     */
    public function novo(Request $request): Response
    {
        $html = $this->viewRenderer->renderComLayout(
            'sujeitos/novo',
            ['erros' => [], 'valores' => []],
            'Novo Sujeito',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function store(Request $request): Response
    {
        $id = trim((string) $request->input('id', ''));
        $nome = trim((string) $request->input('nome', ''));

        $erros = [];

        if ($id === '') {
            $erros['id'] = 'O campo id é obrigatório.';
        }

        if ($nome === '') {
            $erros['nome'] = 'O campo nome é obrigatório.';
        }

        if ($erros !== []) {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'sujeitos/novo',
                ['erros' => $erros, 'valores' => ['id' => $id, 'nome' => $nome]],
                'Novo Sujeito',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->post($this->recurso(), ['id' => $id, 'nome' => $nome]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'sujeitos/novo',
                    ['erros' => ['id' => $this->erroDe($resposta)->mensagem], 'valores' => ['id' => $id, 'nome' => $nome]],
                    'Novo Sujeito',
                    $this->rota()
                ));
            }

            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }

    public function editar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->get($this->recurso() . '/' . $id);

        if (!$resposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        $sujeito = $this->mapearViewModelUnico($resposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            'sujeitos/editar',
            ['id' => $sujeito->id, 'erros' => [], 'valores' => ['nome' => $sujeito->nome]],
            'Editar Sujeito',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function atualizar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $nome = trim((string) $request->input('nome', ''));

        if ($nome === '') {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'sujeitos/editar',
                ['id' => $id, 'erros' => ['nome' => 'O campo nome é obrigatório.'], 'valores' => ['nome' => $nome]],
                'Editar Sujeito',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->put($this->recurso() . '/' . $id, ['nome' => $nome]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'sujeitos/editar',
                    ['id' => $id, 'erros' => ['nome' => $this->erroDe($resposta)->mensagem], 'valores' => ['nome' => $nome]],
                    'Editar Sujeito',
                    $this->rota()
                ));
            }

            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }
}
