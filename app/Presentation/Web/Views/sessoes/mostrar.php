<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel $sessao */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

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
    <div class="pagina-detalhe-gravacao">
        <?php /* Sprint 22: se a sessão nunca foi gravada em áudio, o navegador
                 apenas falha ao carregar a fonte — sem checagem prévia de
                 existência, para não buscar o áudio duas vezes (uma para
                 checar, outra para tocar). O texto já transcrito continua
                 disponível nas telas de Discursos/Eventos Discursivos. */ ?>
        <audio controls preload="none" src="<?= Html::e(BasePath::url('/sessoes/' . $sessao->id . '/audio')) ?>"></audio>
    </div>
    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Editar', '/sessoes/' . $sessao->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e('/sessoes/' . $sessao->id . '/excluir') ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/sessoes', 'secundario') ?>
    </div>
</section>
