<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel $memoria */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = [[
    'id' => $memoria->id,
    'quantidadeDeSessoes' => $memoria->quantidadeDeSessoes,
]];
?>
<section class="pagina-detalhe">
    <?= TableComponent::render(
        ['id' => 'ID', 'quantidadeDeSessoes' => 'Sessões'],
        $linhas
    ) ?>
    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Editar', '/memorias/' . $memoria->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e(BasePath::url('/memorias/' . $memoria->id . '/excluir')) ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/memorias', 'secundario') ?>
    </div>
</section>
