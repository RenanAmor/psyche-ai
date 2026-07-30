<?php
/**
 * @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel $sujeito
 * @var \PsycheAI\Presentation\Web\ViewModels\RecorrenciaViewModel[] $recorrencias
 * @var \PsycheAI\Presentation\Web\ViewModels\ObservacaoViewModel[] $observacoes
 */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;

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

    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Voltar ao Histórico', '/sujeitos/' . $sujeito->id . '/historico', 'secundario') ?>
        <?= ButtonComponent::link('Voltar ao Sujeito', '/sujeitos/' . $sujeito->id, 'secundario') ?>
    </div>
</section>
