<?php
/**
 * @var string $tituloPagina
 * @var string $conteudo
 *
 * Layout exclusivo da superfície do Sujeito (Sprint 32, Interface de Voz
 * da ECO) — sem NavigationMenu/sidebar, sem cabeçalho do Analista. O
 * Sujeito nunca recebe um layout que inclua partials/sidebar.php: é assim
 * que "Sujeitos/Sessões/Discursos/Memórias/Eventos Discursivos" ficam
 * fora da navegação do Sujeito, sem precisar filtrar itens em tempo de
 * execução. $rotaAtiva/$itensNavegacao continuam sendo passados por
 * ViewRenderer::renderComLayout() mas não são usados aqui.
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
<body class="corpo-eco">
<div class="layout-eco">
    <header class="eco-cabecalho" aria-hidden="true">
        <span class="eco-marca">ECO</span>
    </header>
    <main class="eco-conteudo">
        <?= $conteudo ?>
    </main>
</div>
</body>
</html>
