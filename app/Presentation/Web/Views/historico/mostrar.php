<?php
/**
 * @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel $sujeito
 * @var \PsycheAI\Presentation\Web\ViewModels\LinhaDoTempoItemViewModel[] $itens
 * @var \PsycheAI\Presentation\Web\ViewModels\ConsolidacaoViewModel $consolidacao
 * @var int $pagina
 * @var int $porPagina
 * @var int $total
 * @var int $totalDePaginas
 * @var array<string, string> $filtros
 * @var string $rota
 */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\CardComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;

$tiposDisponiveis = [
    '' => 'Todos',
    'sessao' => 'Sessão',
    'discurso' => 'Discurso',
    'evento' => 'Evento Discursivo',
    'memoria' => 'Memória Longitudinal',
];

$tipoSelecionado = $filtros['tipo'] ?? '';

$linkComPagina = static function (int $novaPagina) use ($rota, $filtros): string {
    $query = array_merge($filtros, ['pagina' => (string) $novaPagina]);

    return $rota . '?' . http_build_query($query);
};

$linhas = array_map(
    static fn ($item) => [
        'tipo' => $item->rotulo(),
        'timestamp' => $item->timestamp,
        'resumo' => $item->resumo(),
        'acoes' => $item->rotaDetalhe() !== null
            ? ButtonComponent::link('Ver', $item->rotaDetalhe(), 'secundario')
            : '',
    ],
    $itens
);
?>
<section class="pagina-historico">
    <h2><?= Html::e($sujeito->nome) ?></h2>

    <div class="cartoes-consolidacao">
        <?= CardComponent::render('Sessões', $consolidacao->quantidadeDeSessoes) ?>
        <?= CardComponent::render('Discursos', $consolidacao->quantidadeDeDiscursos) ?>
        <?= CardComponent::render('Eventos Discursivos', $consolidacao->quantidadeDeEventosDiscursivos) ?>
        <?= CardComponent::render('Memórias', $consolidacao->quantidadeDeMemorias) ?>
    </div>

    <form class="formulario formulario-filtro" action="<?= Html::e($rota) ?>" method="GET">
        <div class="campo-formulario">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo">
                <?php foreach ($tiposDisponiveis as $valor => $rotulo): ?>
                    <option value="<?= Html::e($valor) ?>" <?= $valor === $tipoSelecionado ? 'selected' : '' ?>>
                        <?= Html::e($rotulo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo-formulario">
            <label for="de">De</label>
            <input type="date" id="de" name="de" value="<?= Html::e($filtros['de'] ?? '') ?>">
        </div>
        <div class="campo-formulario">
            <label for="ate">Até</label>
            <input type="date" id="ate" name="ate" value="<?= Html::e($filtros['ate'] ?? '') ?>">
        </div>
        <div class="campo-formulario">
            <label for="q">Pesquisar</label>
            <input type="text" id="q" name="q" value="<?= Html::e($filtros['q'] ?? '') ?>">
        </div>
        <?= ButtonComponent::submit('Filtrar') ?>
    </form>

    <?= TableComponent::render(
        ['tipo' => 'Tipo', 'timestamp' => 'Data/Hora', 'resumo' => 'Resumo', 'acoes' => 'Ações'],
        $linhas,
        'Nenhum item encontrado na linha do tempo deste sujeito.',
        ['acoes']
    ) ?>

    <nav class="paginacao">
        <?php if ($pagina > 1): ?>
            <?= ButtonComponent::link('« Anterior', $linkComPagina($pagina - 1), 'secundario') ?>
        <?php endif; ?>
        <span class="paginacao-info">
            Página <?= Html::e((string) $pagina) ?> de <?= Html::e((string) max(1, $totalDePaginas)) ?>
            (<?= Html::e((string) $total) ?> <?= $total === 1 ? 'item' : 'itens' ?>)
        </span>
        <?php if ($pagina < $totalDePaginas): ?>
            <?= ButtonComponent::link('Próxima »', $linkComPagina($pagina + 1), 'secundario') ?>
        <?php endif; ?>
    </nav>

    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Ver Observações', '/sujeitos/' . $sujeito->id . '/observacoes', 'secundario') ?>
        <?= ButtonComponent::link('Voltar ao Sujeito', '/sujeitos/' . $sujeito->id, 'secundario') ?>
    </div>
</section>
