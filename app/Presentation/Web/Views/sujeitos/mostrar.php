<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel $sujeito */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = [[
    'id' => $sujeito->id,
    'nome' => $sujeito->nome,
    'quantidadeDeSessoes' => $sujeito->quantidadeDeSessoes,
]];
?>
<section class="pagina-detalhe">
    <?= TableComponent::render(
        ['id' => 'ID', 'nome' => 'Nome', 'quantidadeDeSessoes' => 'Sessões'],
        $linhas
    ) ?>
    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Ver Histórico', '/sujeitos/' . $sujeito->id . '/historico', 'secundario') ?>
        <?= ButtonComponent::link('Editar', '/sujeitos/' . $sujeito->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e('/sujeitos/' . $sujeito->id . '/excluir') ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/sujeitos', 'secundario') ?>
    </div>
</section>
