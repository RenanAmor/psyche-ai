<?php
/**
 * @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel $sujeito
 * @var \PsycheAI\Presentation\Web\ViewModels\RecorrenciaViewModel[] $recorrencias
 * @var \PsycheAI\Presentation\Web\ViewModels\ObservacaoViewModel[] $observacoes
 * @var \PsycheAI\Presentation\Web\ViewModels\CircuitoRecorrenciaViewModel[] $circuitos
 */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\CircuitoTrajetoComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhasRecorrencias = array_map(
    static fn ($recorrencia) => [
        'descricao' => $recorrencia->descricao,
        'frequencia' => (string) $recorrencia->frequencia,
        'rotuloLacaniano' => $recorrencia->rotuloLacaniano ?? '',
    ],
    $recorrencias
);

$linhasObservacoes = array_map(
    static fn ($observacao) => ['texto' => $observacao->texto],
    $observacoes
);
?>
<section class="pagina-observacoes">
    <h2><?= Html::e($sujeito->nome) ?></h2>
    <p class="texto-auxiliar">
        Discourse Engine: o que se repete no discurso registrado deste sujeito, sem
        hipótese, interpretação ou diagnóstico — apenas atenção flutuante sobre a
        recorrência (Motor Freud) e sua reclassificação em vocabulário lacaniano
        (Motor Lacan), sempre como estrutura candidata — nunca um significante
        confirmado.
    </p>

    <h3>Recorrências</h3>
    <?= TableComponent::render(
        ['descricao' => 'Descrição', 'frequencia' => 'Frequência', 'rotuloLacaniano' => 'Leitura Lacaniana'],
        $linhasRecorrencias,
        'Nenhuma recorrência encontrada no discurso deste sujeito.'
    ) ?>

    <h3>Observações</h3>
    <?= TableComponent::render(
        ['texto' => 'Texto'],
        $linhasObservacoes,
        'Nenhuma observação gerada para este sujeito.'
    ) ?>

    <h3>Circuito / Trajeto</h3>
    <p class="texto-auxiliar">
        Quando/onde cada recorrência reaparece ao longo do tempo — o
        trajeto de sessões em que o mesmo tema retorna, sem hipótese sobre
        o que esse retorno significaria.
    </p>
    <?= CircuitoTrajetoComponent::render(
        $circuitos,
        'Nenhum circuito encontrado no discurso deste sujeito.'
    ) ?>

    <?php if ($circuitos !== []): ?>
    <h3>Circuito / Trajeto — visualização gráfica</h3>
    <p class="texto-auxiliar">
        Mesmo trajeto acima, como grafo: nós são Sessões, arestas ligam
        ocorrências consecutivas de uma Recorrência. Se o grafo não
        carregar, a lista acima continua sendo a fonte de dado.
    </p>
    <div
        id="grafo-circuito"
        data-endpoint="<?= Html::e(BasePath::url('/sujeitos/' . $sujeito->id . '/observacoes/grafo-circuito')) ?>"
    ></div>
    <p class="legenda-grafo-circuito">
        Tracejado = leitura lacaniana, estrutura candidata — nunca uma
        interpretação confirmada (Regra 10).
    </p>
    <?php endif; ?>

    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Voltar ao Histórico', '/sujeitos/' . $sujeito->id . '/historico', 'secundario') ?>
        <?= ButtonComponent::link('Voltar ao Sujeito', '/sujeitos/' . $sujeito->id, 'secundario') ?>
    </div>
</section>
<?php if ($circuitos !== []): ?>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="<?= Html::e(BasePath::url('/assets/js/grafo-circuito.js')) ?>" defer></script>
<?php endif; ?>
