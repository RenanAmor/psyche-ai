<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\SessaoViewModel;

final class SessoesController extends AbstractCrudResourceController
{
    protected function recurso(): string
    {
        return 'sessions';
    }

    protected function rota(): string
    {
        return '/sessoes';
    }

    protected function view(): string
    {
        return 'sessoes/index';
    }

    protected function viewMostrar(): string
    {
        return 'sessoes/mostrar';
    }

    protected function tituloPagina(): string
    {
        return 'Sessões';
    }

    protected function chaveViewModel(): string
    {
        return 'sessoes';
    }

    protected function chaveViewModelUnico(): string
    {
        return 'sessao';
    }

    protected function mapearViewModels(array $dados): array
    {
        return SessaoViewModel::fromArrayList($dados);
    }

    /**
     * Sobrescreve o mostrar() genérico para também trazer a anotação do
     * analista (autosave) junto com os dados da sessão — mesma disciplina
     * de audio(), que também busca um recurso adicional à parte do CRUD
     * genérico de AbstractCrudResourceController.
     */
    public function mostrar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->get($this->recurso() . '/' . $id);

        if (!$resposta->sucesso) {
            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        $sessao = $this->mapearViewModelUnico($resposta->dados);

        $respostaAnotacao = $this->httpClient->get($this->recurso() . '/' . $id . '/annotation');
        $anotacao = $respostaAnotacao->sucesso ? $respostaAnotacao->dados : ['conteudo' => '', 'atualizadoEm' => null];

        $html = $this->viewRenderer->renderComLayout(
            $this->viewMostrar(),
            [$this->chaveViewModelUnico() => $sessao, 'anotacao' => $anotacao],
            $this->tituloPagina(),
            $this->rota()
        );

        return Response::ok($html);
    }

    public function novo(Request $request): Response
    {
        $html = $this->viewRenderer->renderComLayout(
            'sessoes/novo',
            ['erros' => [], 'valores' => []],
            'Nova Sessão',
            $this->rota()
        );

        return Response::ok($html);
    }

    public function store(Request $request): Response
    {
        $id = trim((string) $request->input('id', ''));
        $sujeitoId = trim((string) $request->input('sujeitoId', ''));
        $data = trim((string) $request->input('data', ''));

        $erros = [];

        if ($id === '') {
            $erros['id'] = 'O campo id é obrigatório.';
        }

        if ($sujeitoId === '') {
            $erros['sujeitoId'] = 'O campo sujeitoId é obrigatório.';
        }

        if ($data === '') {
            $erros['data'] = 'O campo data é obrigatório.';
        }

        if ($erros !== []) {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'sessoes/novo',
                ['erros' => $erros, 'valores' => ['id' => $id, 'sujeitoId' => $sujeitoId, 'data' => $data]],
                'Nova Sessão',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->post($this->recurso(), ['id' => $id, 'sujeitoId' => $sujeitoId, 'data' => $data]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'sessoes/novo',
                    [
                        'erros' => ['id' => $this->erroDe($resposta)->mensagem],
                        'valores' => ['id' => $id, 'sujeitoId' => $sujeitoId, 'data' => $data],
                    ],
                    'Nova Sessão',
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

        $sessao = $this->mapearViewModelUnico($resposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            'sessoes/editar',
            ['id' => $sessao->id, 'erros' => [], 'valores' => ['data' => $sessao->data]],
            'Editar Sessão',
            $this->rota()
        );

        return Response::ok($html);
    }

    /**
     * Reproduz a gravação de áudio original da sessão (Sprint 22) para o
     * analista validar o que a transcrição escreveu — nunca o texto, só
     * os mesmos bytes que o Sujeito gravou (Regra 2, preservação
     * integral). A Web nunca fala com StorageInterface direto: repassa
     * via HttpClientInterface::getBinario(), mesma disciplina do resto da
     * camada.
     */
    public function audio(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->getBinario($this->recurso() . '/' . $id . '/audio');

        if (!$resposta->sucesso) {
            return Response::naoEncontrado('Nenhuma gravação de áudio encontrada para esta sessão.');
        }

        return new Response($resposta->bytes, 200, ['Content-Type' => $resposta->contentType ?? 'audio/webm']);
    }

    public function atualizar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $data = trim((string) $request->input('data', ''));

        if ($data === '') {
            return Response::erroValidacao($this->viewRenderer->renderComLayout(
                'sessoes/editar',
                ['id' => $id, 'erros' => ['data' => 'O campo data é obrigatório.'], 'valores' => ['data' => $data]],
                'Editar Sessão',
                $this->rota()
            ));
        }

        $resposta = $this->httpClient->put($this->recurso() . '/' . $id, ['data' => $data]);

        if (!$resposta->sucesso) {
            if ($this->ehFalhaDeFormulario($resposta)) {
                return Response::erroValidacao($this->viewRenderer->renderComLayout(
                    'sessoes/editar',
                    ['id' => $id, 'erros' => ['data' => $this->erroDe($resposta)->mensagem], 'valores' => ['data' => $data]],
                    'Editar Sessão',
                    $this->rota()
                ));
            }

            return ErrorController::renderizar($this->erroDe($resposta), $this->viewRenderer, $this->rota());
        }

        return $this->index($request->comRouteParams([]));
    }
}
