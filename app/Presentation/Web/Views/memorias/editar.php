<?php
/**
 * @var string $id
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
    <p>Reconstrói a memória longitudinal a partir das sessões atuais do sujeito informado.</p>
    <?= FormComponent::render(
        '/memorias/' . $id,
        'POST',
        [
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
