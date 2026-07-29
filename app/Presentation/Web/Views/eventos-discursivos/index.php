<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel[] $eventos */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($evento) => [
        'id' => $evento->id,
        'sessaoId' => $evento->sessaoId,
        'discursoId' => $evento->discursoId,
        'posicao' => $evento->posicao,
        'conteudo' => $evento->conteudo,
        'criadoEm' => $evento->criadoEm,
    ],
    $eventos
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        [
            'id' => 'ID',
            'sessaoId' => 'Sessão',
            'discursoId' => 'Discurso',
            'posicao' => 'Ordem',
            'conteudo' => 'Conteúdo',
            'criadoEm' => 'Criado em',
        ],
        $linhas,
        'Nenhum evento discursivo registrado.'
    ) ?>
</section>
