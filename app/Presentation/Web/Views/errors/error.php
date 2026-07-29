<?php
/** @var \PsycheAI\Presentation\Web\Errors\ErrorViewModel $erro */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\Html;

$tipoParaAlerta = [
    'comunicacao' => 'erro',
    'nao_encontrado' => 'aviso',
    'validacao' => 'erro',
    'interno' => 'erro',
];
?>
<section class="pagina-erro" data-tipo-erro="<?= Html::e($erro->tipo->value) ?>">
    <h2><?= Html::e($erro->titulo) ?></h2>
    <?= AlertComponent::render($erro->mensagem, $tipoParaAlerta[$erro->tipo->value] ?? 'erro') ?>
    <?php if ($erro->detalhes !== []): ?>
        <ul class="lista-erros">
            <?php foreach ($erro->detalhes as $campo => $mensagem): ?>
                <li><strong><?= Html::e((string) $campo) ?>:</strong> <?= Html::e($mensagem) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
