<?php
/**
 * @var string $tituloPagina
 * @var string $rotaAtiva
 * @var \PsycheAI\Presentation\Web\Navigation\NavigationItem[] $itensNavegacao
 * @var string $conteudo
 */

use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::e($tituloPagina) ?> — Psyche AI</title>
    <link rel="stylesheet" href="<?= Html::e(BasePath::url('/assets/css/estilo.css')) ?>">
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
