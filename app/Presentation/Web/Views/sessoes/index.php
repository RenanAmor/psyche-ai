<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel[] $sessoes */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($sessao) => [
        'id' => $sessao->id,
        'data' => $sessao->data,
        'quantidadeDeDiscursos' => $sessao->quantidadeDeDiscursos,
        'acoes' => ButtonComponent::link('Ver', '/sessoes/' . $sessao->id, 'secundario')
            . ' ' . ButtonComponent::link('Editar', '/sessoes/' . $sessao->id . '/editar', 'secundario'),
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
