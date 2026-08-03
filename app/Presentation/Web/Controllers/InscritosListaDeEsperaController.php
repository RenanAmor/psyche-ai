<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\ViewModels\InscricaoListaDeEsperaViewModel;

/**
 * Tela de leitura do Analista sobre quem se inscreveu na lista de espera
 * da ECO (atrás de `PortaoDeAnalista`, nunca de `PortaoDeParticipante`) —
 * mesmo padrão de `EventosDiscursivosController`: uma única página de
 * listagem, sem criar/editar/excluir.
 */
final class InscritosListaDeEsperaController extends AbstractResourceController
{
    protected function recurso(): string
    {
        return 'waitlist';
    }

    protected function rota(): string
    {
        return '/lista-espera';
    }

    protected function view(): string
    {
        return 'lista-espera/index';
    }

    protected function tituloPagina(): string
    {
        return 'Lista de Espera';
    }

    protected function chaveViewModel(): string
    {
        return 'inscricoes';
    }

    protected function mapearViewModels(array $dados): array
    {
        return InscricaoListaDeEsperaViewModel::fromArrayList($dados);
    }
}
