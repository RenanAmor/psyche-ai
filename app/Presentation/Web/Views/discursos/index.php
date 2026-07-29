<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel[] $discursos */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($discurso) => [
        'id' => $discurso->id,
        'conteudo' => $discurso->conteudo,
        'quantidadeDeEventos' => $discurso->quantidadeDeEventos,
    ],
    $discursos
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        ['id' => 'ID', 'conteudo' => 'Conteúdo', 'quantidadeDeEventos' => 'Eventos'],
        $linhas,
        'Nenhum discurso registrado.'
    ) ?>
</section>
