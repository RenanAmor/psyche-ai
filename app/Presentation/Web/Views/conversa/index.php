<?php
/**
 * @var \PsycheAI\Presentation\Web\ViewModels\MensagemViewModel[] $mensagens
 * @var ?string $alerta
 * @var string $tipoAlerta
 * @var string $valorConteudo
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($mensagem): array => [
        'autor' => $mensagem->autor,
        'conteudo' => $mensagem->conteudo,
        'criadoEm' => $mensagem->criadoEm,
    ],
    $mensagens
);
?>
<section class="pagina-conversa">
    <?php if ($alerta !== null): ?>
        <?= AlertComponent::render($alerta, $tipoAlerta) ?>
    <?php endif; ?>

    <div id="historico-mensagens" class="historico-mensagens">
        <?= TableComponent::render(
            ['autor' => 'Autor', 'conteudo' => 'Mensagem', 'criadoEm' => 'Quando'],
            $linhas,
            'Nenhuma mensagem ainda. Escreva abaixo para começar.'
        ) ?>
    </div>

    <?= FormComponent::render(
        '/conversa/enviar',
        'POST',
        [[
            'nome' => 'conteudo',
            'rotulo' => 'Mensagem',
            'tipo' => 'textarea',
            'valor' => $valorConteudo,
        ]],
        'Enviar'
    ) ?>
</section>
<script>
(function () {
    var historico = document.getElementById('historico-mensagens');
    if (historico) {
        historico.scrollTop = historico.scrollHeight;
    }
})();
</script>
