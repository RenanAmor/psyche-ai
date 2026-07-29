<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel;

final class MemoriasController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'memorias';
    }

    protected function rota(): string
    {
        return '/memorias';
    }

    protected function view(): string
    {
        return 'memorias/index';
    }

    protected function tituloPagina(): string
    {
        return 'Memórias';
    }

    protected function chaveViewModel(): string
    {
        return 'memorias';
    }

    protected function mapearViewModels(array $dados): array
    {
        return MemoriaViewModel::fromArrayList($dados);
    }
}
