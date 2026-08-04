<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel[] $sessoes */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = array_map(
    static fn ($sessao) => [
        'id' => $sessao->id,
        'data' => $sessao->data,
        'quantidadeDeDiscursos' => $sessao->quantidadeDeDiscursos,
        'acoes' => ButtonComponent::link('Ver', '/sessoes/' . $sessao->id, 'secundario')
            . ' ' . ButtonComponent::link('Editar', '/sessoes/' . $sessao->id . '/editar', 'secundario')
            . ' ' . sprintf(
                '<form class="formulario-exclusao formulario-exclusao-linha" action="%s" method="POST">%s</form>',
                Html::e(BasePath::url('/sessoes/' . $sessao->id . '/excluir')),
                ButtonComponent::submit('Excluir', 'perigo')
            ),
    ],
    $sessoes
);
?>
<section class="pagina-lista">
    <div class="pagina-lista-acoes">
        <?= ButtonComponent::link('Nova Sessão', '/sessoes/novo') ?>
    </div>
    <?= TableComponent::render(
        ['id' => 'ID', 'data' => 'Data', 'quantidadeDeDiscursos' => 'Discursos', 'acoes' => 'Ações'],
        $linhas,
        'Nenhuma sessão registrada.',
        ['acoes']
    ) ?>
</section>
