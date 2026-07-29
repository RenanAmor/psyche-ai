<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel;

final class MemoriasController extends AbstractCrudResourceController
{
    protected function recurso(): string
    {
        return 'memories';
    }

    protected function rota(): string
    {
        return '/memorias';
    }

    protected function view(): string
    {
        return 'memorias/index';
    }

    protected function viewMostrar(): string
    {
        return 'memorias/mostrar';
    }

    protected function tituloPagina(): string
    {
        return 'Memórias';
    }

    protected function chaveViewModel(): string
    {
        return 'memorias';
    }

    protected function chaveViewModelUnico(): string
    {
        return 'memoria';
    }

    protected function mapearViewModels(array $dados): array
    {
        return MemoriaViewModel::fromArrayList($dados);
    }

    public function novo(Request $request): Response
    {
        $html = $this->viewRenderer->renderComLayout(
            'memorias/novo',
            ['erros' => [], 'valores' => []],
            'Nova Memória',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function store(Request $request): Response
    {
        $id = trim((string) $request->input('id', ''));
        $sujeitoId = trim((string) $request->input('sujeitoId', ''));

        $erros = [];

        if ($id === '') {
            $erros['id'] = 'O campo id é obrigatório.';
        }

        if ($sujeitoId === '') {
            $erros['sujeitoId'] = 'O campo sujeitoId é obrigatório.';
        }

        if ($erros !== []) {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'memorias/novo',
                ['erros' => $erros, 'valores' => ['id' => $id, 'sujeitoId' => $sujeitoId]],
                'Nova Memória',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->post($this->recurso(), ['id' => $id, 'sujeitoId' => $sujeitoId]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'memorias/novo',
                    [
                        'erros' => ['id' => $this->erroDe($resposta)->mensagem],
                        'valores' => ['id' => $id, 'sujeitoId' => $sujeitoId],
                    ],
                    'Nova Memória',
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

        $html = $this->viewRenderer->renderComLayout(
            'memorias/editar',
            ['id' => $id, 'erros' => [], 'valores' => []],
            'Editar Memória',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function atualizar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $sujeitoId = trim((string) $request->input('sujeitoId', ''));

        if ($sujeitoId === '') {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'memorias/editar',
                ['id' => $id, 'erros' => ['sujeitoId' => 'O campo sujeitoId é obrigatório.'], 'valores' => ['sujeitoId' => $sujeitoId]],
                'Editar Memória',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->put($this->recurso() . '/' . $id, ['sujeitoId' => $sujeitoId]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'memorias/editar',
                    ['id' => $id, 'erros' => ['sujeitoId' => $this->erroDe($resposta)->mensagem], 'valores' => ['sujeitoId' => $sujeitoId]],
                    'Editar Memória',
                    $this->rota()
                ));
            }

            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }
}
