<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\DashboardViewModel $dashboard */

use PsycheAI\Presentation\Web\Components\CardComponent;
?>
<section class="grade-cartoes">
    <?= CardComponent::render('Sujeitos', $dashboard->totalSujeitos, 'Total cadastrado', 'user') ?>
    <?= CardComponent::render('Sessões', $dashboard->totalSessoes, 'Total registrado', 'calendar') ?>
    <?= CardComponent::render('Discursos', $dashboard->totalDiscursos, 'Total registrado', 'message') ?>
    <?= CardComponent::render('Memórias', $dashboard->totalMemorias, 'Longitudinais construídas', 'archive') ?>
    <?= CardComponent::render('Eventos Discursivos', $dashboard->totalEventosDiscursivos, 'Total registrado', 'activity') ?>
</section>
