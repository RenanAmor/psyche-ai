<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel[] $sessoes */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($sessao) => [
        'id' => $sessao->id,
        'data' => $sessao->data,
        'quantidadeDeDiscursos' => $sessao->quantidadeDeDiscursos,
    ],
    $sessoes
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        ['id' => 'ID', 'data' => 'Data', 'quantidadeDeDiscursos' => 'Discursos'],
        $linhas,
        'Nenhuma sessão registrada.'
    ) ?>
</section>
