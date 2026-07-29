<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel;

final class EventosDiscursivosController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'eventos-discursivos';
    }

    protected function rota(): string
    {
        return '/eventos-discursivos';
    }

    protected function view(): string
    {
        return 'eventos-discursivos/index';
    }

    protected function tituloPagina(): string
    {
        return 'Eventos Discursivos';
    }

    protected function chaveViewModel(): string
    {
        return 'eventos';
    }

    protected function mapearViewModels(array $dados): array
    {
        return EventoDiscursivoViewModel::fromArrayList($dados);
    }
}
