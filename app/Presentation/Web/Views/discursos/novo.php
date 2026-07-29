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
        '/discursos',
        'POST',
        [
            [
                'nome' => 'id',
                'rotulo' => 'ID',
                'valor' => $valores['id'] ?? '',
                'erro' => $erros['id'] ?? null,
            ],
            [
                'nome' => 'sessaoId',
                'rotulo' => 'Sessão (ID)',
                'valor' => $valores['sessaoId'] ?? '',
                'erro' => $erros['sessaoId'] ?? null,
            ],
            [
                'nome' => 'conteudo',
                'rotulo' => 'Conteúdo',
                'tipo' => 'textarea',
                'valor' => $valores['conteudo'] ?? '',
                'erro' => $erros['conteudo'] ?? null,
            ],
        ],
        'Salvar'
    ) ?>
</section>
