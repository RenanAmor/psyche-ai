<?php
/**
 * @var string $tituloPagina
 * @var string $rotaAtiva
 * @var \PsycheAI\Presentation\Web\Navigation\NavigationItem[] $itensNavegacao
 * @var string $conteudo
 */

use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\AssetVersion;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::e($tituloPagina) ?> — Psyche AI</title>
    <link rel="stylesheet" href="<?= Html::e(AssetVersion::url('/assets/css/estilo.2.css')) ?>">
</head>
<body class="corpo-analista">
<div class="layout-principal">
    <a href="https://investimentos369.com/public/index.php?page=lab-dashboard" class="voltar-laboratorio">← Voltar ao Laboratório 369</a>
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
