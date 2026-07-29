<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel[] $eventos */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($evento) => [
        'id' => $evento->id,
        'conteudo' => $evento->conteudo,
        'posicao' => $evento->posicao,
    ],
    $eventos
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        ['id' => 'ID', 'conteudo' => 'Conteúdo', 'posicao' => 'Posição'],
        $linhas,
        'Nenhum evento discursivo registrado.'
    ) ?>
</section>
