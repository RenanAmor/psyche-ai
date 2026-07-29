<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel;

final class DiscursosController extends AbstractCrudResourceController
{
    protected function recurso(): string
    {
        return 'discourses';
    }

    protected function rota(): string
    {
        return '/discursos';
    }

    protected function view(): string
    {
        return 'discursos/index';
    }

    protected function viewMostrar(): string
    {
        return 'discursos/mostrar';
    }

    protected function tituloPagina(): string
    {
        return 'Discursos';
    }

    protected function chaveViewModel(): string
    {
        return 'discursos';
    }

    protected function chaveViewModelUnico(): string
    {
        return 'discurso';
    }

    protected function mapearViewModels(array $dados): array
    {
        return DiscursoViewModel::fromArrayList($dados);
    }

    public function novo(Request $request): Response
    {
        $html = $this->viewRenderer->renderComLayout(
            'discursos/novo',
            ['erros' => [], 'valores' => []],
            'Novo Discurso',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function store(Request $request): Response
    {
        $id = trim((string) $request->input('id', ''));
        $sessaoId = trim((string) $request->input('sessaoId', ''));
        $conteudo = trim((string) $request->input('conteudo', ''));

        $erros = [];

        if ($id === '') {
            $erros['id'] = 'O campo id é obrigatório.';
        }

        if ($sessaoId === '') {
            $erros['sessaoId'] = 'O campo sessaoId é obrigatório.';
        }

        if ($conteudo === '') {
            $erros['conteudo'] = 'O campo conteudo é obrigatório.';
        }

        if ($erros !== []) {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'discursos/novo',
                ['erros' => $erros, 'valores' => ['id' => $id, 'sessaoId' => $sessaoId, 'conteudo' => $conteudo]],
                'Novo Discurso',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->post($this->recurso(), ['id' => $id, 'sessaoId' => $sessaoId, 'conteudo' => $conteudo]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'discursos/novo',
                    [
                        'erros' => ['id' => $this->erroDe($resposta)->mensagem],
                        'valores' => ['id' => $id, 'sessaoId' => $sessaoId, 'conteudo' => $conteudo],
                    ],
                    'Novo Discurso',
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

        $discurso = $this->mapearViewModelUnico($resposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            'discursos/editar',
            ['id' => $discurso->id, 'erros' => [], 'valores' => ['conteudo' => $discurso->conteudo]],
            'Editar Discurso',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function atualizar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $conteudo = trim((string) $request->input('conteudo', ''));

        if ($conteudo === '') {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'discursos/editar',
                ['id' => $id, 'erros' => ['conteudo' => 'O campo conteudo é obrigatório.'], 'valores' => ['conteudo' => $conteudo]],
                'Editar Discurso',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->put($this->recurso() . '/' . $id, ['conteudo' => $conteudo]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'discursos/editar',
                    ['id' => $id, 'erros' => ['conteudo' => $this->erroDe($resposta)->mensagem], 'valores' => ['conteudo' => $conteudo]],
                    'Editar Discurso',
                    $this->rota()
                ));
            }

            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }
}
