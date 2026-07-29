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
        '/memorias',
        'POST',
        [
            [
                'nome' => 'id',
                'rotulo' => 'ID',
                'valor' => $valores['id'] ?? '',
                'erro' => $erros['id'] ?? null,
            ],
            [
                'nome' => 'sujeitoId',
                'rotulo' => 'Sujeito (ID)',
                'valor' => $valores['sujeitoId'] ?? '',
                'erro' => $erros['sujeitoId'] ?? null,
            ],
        ],
        'Salvar'
    ) ?>
</section>
