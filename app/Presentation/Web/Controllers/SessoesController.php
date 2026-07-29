<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\ViewModels\SessaoViewModel;

final class SessoesController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'sessoes';
    }

    protected function rota(): string
    {
        return '/sessoes';
    }

    protected function view(): string
    {
        return 'sessoes/index';
    }

    protected function tituloPagina(): string
    {
        return 'Sessões';
    }

    protected function chaveViewModel(): string
    {
        return 'sessoes';
    }

    protected function mapearViewModels(array $dados): array
    {
        return SessaoViewModel::fromArrayList($dados);
    }
}
