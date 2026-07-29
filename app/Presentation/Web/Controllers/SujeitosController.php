<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel;

final class SujeitosController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'sujeitos';
    }

    protected function rota(): string
    {
        return '/sujeitos';
    }

    protected function view(): string
    {
        return 'sujeitos/index';
    }

    protected function tituloPagina(): string
    {
        return 'Sujeitos';
    }

    protected function chaveViewModel(): string
    {
        return 'sujeitos';
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
        $nome = trim((string) $request->input('nome', ''));

        if ($nome === '') {
            $html = $this->viewRenderer->renderComLayout(
                'sujeitos/novo',
                ['erros' => ['nome' => 'O campo nome é obrigatório.'], 'valores' => ['nome' => $nome]],
                'Novo Sujeito',
                $this->rota()
            );

            return Response::erroValidacao($html);
        }

        $this->httpClient->post($this->recurso(), ['nome' => $nome]);

        return $this->index($request->comRouteParams([]));
    }
}
