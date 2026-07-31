<?php
/**
 * @var string $rotaAtiva
 * @var \PsycheAI\Presentation\Web\Navigation\NavigationItem[] $itensNavegacao
 */

use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<nav class="barra-lateral" aria-label="Navegação principal">
    <ul class="barra-lateral-lista">
        <?php foreach ($itensNavegacao as $item): ?>
            <li class="barra-lateral-item<?= $item->ativo($rotaAtiva) ? ' ativo' : '' ?>">
                <a href="<?= Html::e(BasePath::url($item->rota)) ?>" data-icone="<?= Html::e($item->icone) ?>"
                   <?= $item->ativo($rotaAtiva) ? 'aria-current="page"' : '' ?>>
                    <?= Html::e($item->rotulo) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
