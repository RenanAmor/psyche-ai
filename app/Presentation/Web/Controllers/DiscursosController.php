<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel;

final class DiscursosController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'discursos';
    }

    protected function rota(): string
    {
        return '/discursos';
    }

    protected function view(): string
    {
        return 'discursos/index';
    }

    protected function tituloPagina(): string
    {
        return 'Discursos';
    }

    protected function chaveViewModel(): string
    {
        return 'discursos';
    }

    protected function mapearViewModels(array $dados): array
    {
        return DiscursoViewModel::fromArrayList($dados);
    }
}
