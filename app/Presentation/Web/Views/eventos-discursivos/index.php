<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel[] $eventos */
/** @var string $filtroSessaoId */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = array_map(
    static fn ($evento) => [
        'id' => $evento->id,
        'sessaoId' => $evento->sessaoId,
        'discursoId' => $evento->discursoId,
        'posicao' => $evento->posicao,
        'locutor' => $evento->locutor,
        'conteudo' => $evento->conteudo,
        'criadoEm' => $evento->criadoEm,
    ],
    $eventos
);
?>
<section class="pagina-lista">
    <form class="formulario-filtro" action="<?= Html::e(BasePath::url('/eventos-discursivos')) ?>" method="GET">
        <div class="campo-formulario">
            <label for="filtro-sessao-id">Filtrar por Sessão</label>
            <input type="text" id="filtro-sessao-id" name="sessaoId" value="<?= Html::e($filtroSessaoId) ?>" placeholder="ID da sessão">
        </div>
        <?= ButtonComponent::submit('Filtrar', 'secundario') ?>
        <?php if ($filtroSessaoId !== ''): ?>
            <?= ButtonComponent::link('Limpar filtro', '/eventos-discursivos', 'secundario') ?>
        <?php endif; ?>
    </form>
    <?= TableComponent::render(
        [
            'id' => 'ID',
            'sessaoId' => 'Sessão',
            'discursoId' => 'Discurso',
            'posicao' => 'Ordem',
            'locutor' => 'Locutor',
            'conteudo' => 'Conteúdo',
            'criadoEm' => 'Criado em',
        ],
        $linhas,
        'Nenhum evento discursivo registrado.'
    ) ?>
</section>
