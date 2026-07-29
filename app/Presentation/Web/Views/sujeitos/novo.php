<?php
/**
 * @var array<string, string> $valores
 * @var array<string, string> $erros
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;

$temErros = $erros !== [];
?>
<section class="pagina-formulario">
    <?php if ($temErros): ?>
        <?= AlertComponent::render('Corrija os campos indicados antes de continuar.', 'erro') ?>
    <?php endif; ?>
    <?= FormComponent::render(
        '/sujeitos',
        'POST',
        [
            [
                'nome' => 'nome',
                'rotulo' => 'Nome',
                'valor' => $valores['nome'] ?? '',
                'erro' => $erros['nome'] ?? null,
            ],
        ],
        'Salvar'
    ) ?>
</section>
