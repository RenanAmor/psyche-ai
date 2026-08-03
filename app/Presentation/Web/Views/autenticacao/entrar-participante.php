<?php
/**
 * @var ?string $erro
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;
?>
<section class="pagina-formulario">
    <h2>Entrar na ECO</h2>
    <?php if ($erro !== null): ?>
        <?= AlertComponent::render($erro, 'erro') ?>
    <?php endif; ?>
    <?= FormComponent::render(
        '/participante/entrar',
        'POST',
        [
            ['nome' => 'email', 'rotulo' => 'E-mail', 'tipo' => 'email'],
            ['nome' => 'senha', 'rotulo' => 'Senha', 'tipo' => 'password'],
        ],
        'Entrar'
    ) ?>
</section>
