<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel $discurso */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = [[
    'id' => $discurso->id,
    'conteudo' => $discurso->conteudo,
    'quantidadeDeEventos' => $discurso->quantidadeDeEventos,
]];
?>
<section class="pagina-detalhe">
    <?= TableComponent::render(
        ['id' => 'ID', 'conteudo' => 'Conteúdo', 'quantidadeDeEventos' => 'Eventos'],
        $linhas
    ) ?>
    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Editar', '/discursos/' . $discurso->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e('/discursos/' . $discurso->id . '/excluir') ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/discursos', 'secundario') ?>
    </div>
</section>
