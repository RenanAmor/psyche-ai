<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel[] $memorias */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = array_map(
    static fn ($memoria) => [
        'id' => $memoria->id,
        'quantidadeDeSessoes' => $memoria->quantidadeDeSessoes,
        'acoes' => ButtonComponent::link('Ver', '/memorias/' . $memoria->id, 'secundario')
            . ' ' . ButtonComponent::link('Editar', '/memorias/' . $memoria->id . '/editar', 'secundario')
            . ' ' . sprintf(
                '<form class="formulario-exclusao formulario-exclusao-linha" action="%s" method="POST">%s</form>',
                Html::e(BasePath::url('/memorias/' . $memoria->id . '/excluir')),
                ButtonComponent::submit('Excluir', 'perigo')
            ),
    ],
    $memorias
);
?>
<section class="pagina-lista">
    <div class="pagina-lista-acoes">
        <?= ButtonComponent::link('Nova Memória', '/memorias/novo') ?>
    </div>
    <?= TableComponent::render(
        ['id' => 'ID', 'quantidadeDeSessoes' => 'Sessões', 'acoes' => 'Ações'],
        $linhas,
        'Nenhuma memória longitudinal construída.',
        ['acoes']
    ) ?>
</section>
