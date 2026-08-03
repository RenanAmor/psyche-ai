<?php
/**
 * @var ?string $erro
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;
?>
<div class="pagina-entrada-eco">
    <section class="pagina-formulario">
        <h2>Antes de começar</h2>

        <p>Você está prestes a falar com a ECO. Antes disso, algumas informações importantes:</p>

        <p><strong>O que é gravado:</strong> o áudio da sua fala e a transcrição gerada a partir dele. Nada é interpretado, diagnosticado ou avaliado clinicamente a partir do que você diz.</p>

        <p><strong>Por que gravamos:</strong> a ECO é um instrumento de pesquisa, não um chatbot. Ela sustenta o espaço da associação livre — a técnica clássica da psicanálise — para observar como a fala se organiza ao longo do tempo (o que se repete, onde aparecem pausas, quando um tema retorna). É por isso que o projeto entra em questões teóricas de Freud e Lacan: a teoria orienta o que observar, não conclusões sobre você.</p>

        <p><strong>Estágio atual:</strong> esta é uma fase de piloto técnico, com colaboradores próximos do projeto — não uma pesquisa formalmente aprovada por Comitê de Ética (CEP/CONEP). Se isso mudar no futuro, você será consultado(a) novamente antes de qualquer novo uso dos seus dados.</p>

        <p><strong>Seus direitos:</strong> sua participação é voluntária. Você pode encerrar uma sessão a qualquer momento, e pode pedir a exclusão dos seus dados quando quiser, escrevendo para <a href="mailto:contato@investimentos369.com">contato@investimentos369.com</a>.</p>

        <?php if ($erro !== null): ?>
            <?= AlertComponent::render($erro, 'erro') ?>
        <?php endif; ?>

        <?= FormComponent::render(
            '/conversa/consentimento',
            'POST',
            [
                [
                    'nome' => 'aceitouExplicacao',
                    'rotulo' => 'Li e entendi as informações acima. Aceito participar.',
                    'tipo' => 'checkbox',
                ],
            ],
            'Continuar para a ECO'
        ) ?>
    </section>
</div>
