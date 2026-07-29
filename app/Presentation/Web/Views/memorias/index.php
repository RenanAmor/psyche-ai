<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel[] $memorias */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($memoria) => [
        'id' => $memoria->id,
        'quantidadeDeSessoes' => $memoria->quantidadeDeSessoes,
    ],
    $memorias
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        ['id' => 'ID', 'quantidadeDeSessoes' => 'Sessões'],
        $linhas,
        'Nenhuma memória longitudinal construída.'
    ) ?>
</section>
