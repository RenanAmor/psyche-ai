<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Components\LoadingIndicatorComponent;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;

/**
 * Ceremonial comum às cinco páginas de listagem (Sujeitos, Sessões,
 * Discursos, Memórias, Eventos Discursivos): pedir os dados ao Cliente
 * HTTP, tratar a falha exibindo o erro correspondente, mapear para
 * ViewModels e renderizar a página dentro do layout principal.
 */
abstract class AbstractResourceController
{
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
        protected readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    abstract protected function recurso(): string;

    abstract protected function rota(): string;

    abstract protected function view(): string;

    abstract protected function tituloPagina(): string;

    abstract protected function chaveViewModel(): string;

    /**
     * @param array<int, array<string, mixed>> $dados
     * @return array<int, object>
     */
    abstract protected function mapearViewModels(array $dados): array;

    public function index(Request $request): Response
    {
        if ($request->query('estado') === 'carregando') {
            return new Response(
                $this->viewRenderer->renderComLayout(
                    'carregando',
                    ['rotulo' => sprintf('Carregando %s...', $this->tituloPagina())],
                    $this->tituloPagina(),
                    $this->rota()
                )
            );
        }

        $resposta = $this->httpClient->get($this->recurso());

        if (!$resposta->sucesso) {
            /** @var \PsycheAI\Presentation\Web\Errors\ErrorViewModel $erro */
            $erro = $resposta->erro;

            return ErrorController::renderizar($erro, $this->viewRenderer, $this->rota());
        }

        $itens = $this->mapearViewModels($resposta->dados);

        $html = $this->viewRenderer->renderComLayout(
            $this->view(),
            [$this->chaveViewModel() => $itens],
            $this->tituloPagina(),
            $this->rota()
        );

        return Response::ok($html);
    }

    /**
     * @param array<string, mixed> $dados
     */
    protected function mapearViewModelUnico(array $dados): object
    {
        return $this->mapearViewModels([$dados])[0];
    }

    protected function erroDe(ApiResponse $resposta): ErrorViewModel
    {
        /** @var ErrorViewModel $erro */
        $erro = $resposta->erro;

        return $erro;
    }
}
