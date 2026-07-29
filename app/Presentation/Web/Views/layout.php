<?php
/**
 * @var string $tituloPagina
 * @var string $rotaAtiva
 * @var \PsycheAI\Presentation\Web\Navigation\NavigationItem[] $itensNavegacao
 * @var string $conteudo
 */

use PsycheAI\Presentation\Web\Components\Html;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title><?= Html::e($tituloPagina) ?> — Psyche AI</title>
</head>
<body>
<div class="layout-principal">
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="layout-corpo">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <main class="area-conteudo">
            <?= $conteudo ?>
        </main>
    </div>
</div>
</body>
</html>
