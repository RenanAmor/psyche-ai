<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel[] $discursos */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($discurso) => [
        'id' => $discurso->id,
        'conteudo' => $discurso->conteudo,
        'quantidadeDeEventos' => $discurso->quantidadeDeEventos,
        'acoes' => ButtonComponent::link('Ver', '/discursos/' . $discurso->id, 'secundario')
            . ' ' . ButtonComponent::link('Editar', '/discursos/' . $discurso->id . '/editar', 'secundario'),
    ],
    $discursos
);
?>
<section class="pagina-lista">
    <div class="pagina-lista-acoes">
        <?= ButtonComponent::link('Novo Discurso', '/discursos/novo') ?>
    </div>
    <?= TableComponent::render(
        ['id' => 'ID', 'conteudo' => 'Conteúdo', 'quantidadeDeEventos' => 'Eventos', 'acoes' => 'Ações'],
        $linhas,
        'Nenhum discurso registrado.',
        ['acoes']
    ) ?>
</section>
