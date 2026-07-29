<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel $sessao */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = [[
    'id' => $sessao->id,
    'data' => $sessao->data,
    'quantidadeDeDiscursos' => $sessao->quantidadeDeDiscursos,
]];
?>
<section class="pagina-detalhe">
    <?= TableComponent::render(
        ['id' => 'ID', 'data' => 'Data', 'quantidadeDeDiscursos' => 'Discursos'],
        $linhas
    ) ?>
    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Editar', '/sessoes/' . $sessao->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e('/sessoes/' . $sessao->id . '/excluir') ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/sessoes', 'secundario') ?>
    </div>
</section>
