<?php
/**
 * @var ?string $erro
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;
?>
<section class="pagina-formulario">
    <h2>Criar conta</h2>
    <p class="texto-auxiliar">
        Sua conversa continua a mesma de antes — criar uma conta só liga
        um e-mail e senha a ela, para você conseguir voltar ao mesmo
        espaço de outro navegador ou aparelho.
    </p>
    <?php if ($erro !== null): ?>
        <?= AlertComponent::render($erro, 'erro') ?>
    <?php endif; ?>
    <?= FormComponent::render(
        '/conversa/cadastro',
        'POST',
        [
            ['nome' => 'email', 'rotulo' => 'E-mail', 'tipo' => 'email'],
            ['nome' => 'senha', 'rotulo' => 'Senha', 'tipo' => 'password'],
        ],
        'Criar conta'
    ) ?>
</section>
