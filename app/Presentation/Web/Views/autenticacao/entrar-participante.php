<?php
/**
 * @var ?string $erro
 * @var ?string $mensagemInscricao
 * @var ?string $tipoInscricao
 */

use PsycheAI\Presentation\Web\Components\AlertComponent;
use PsycheAI\Presentation\Web\Components\FormComponent;

$abrirListaDeEspera = ($mensagemInscricao ?? null) !== null;
?>
<div class="pagina-entrada-eco">
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

    <details class="lista-espera-toggle" id="lista-espera"<?= $abrirListaDeEspera ? ' open' : '' ?>>
        <summary>Ainda não tem conta? Entrar na lista de espera</summary>
        <section class="pagina-formulario">
            <p>O acesso à ECO é liberado aos poucos, sem autocadastro. Deixe seu e-mail na lista de espera e avisamos assim que houver vaga.</p>
            <?php if ($abrirListaDeEspera): ?>
                <?= AlertComponent::render($mensagemInscricao, $tipoInscricao ?? 'info') ?>
            <?php endif; ?>
            <?= FormComponent::render(
                '/lista-espera',
                'POST',
                [
                    ['nome' => 'nome', 'rotulo' => 'Nome', 'tipo' => 'text'],
                    ['nome' => 'email', 'rotulo' => 'E-mail', 'tipo' => 'email'],
                    ['nome' => 'profissao', 'rotulo' => 'Profissão (opcional)', 'tipo' => 'text'],
                    ['nome' => 'instituicao', 'rotulo' => 'Instituição (opcional)', 'tipo' => 'text'],
                    ['nome' => 'paisEstado', 'rotulo' => 'País/Estado', 'tipo' => 'text'],
                    ['nome' => 'motivoInteresse', 'rotulo' => 'Motivo do interesse', 'tipo' => 'textarea'],
                    [
                        'nome' => 'aceitouPoliticaPrivacidade',
                        'rotulo' => 'Li e concordo com a Política de Privacidade no tratamento dos meus dados para fins de contato sobre a pesquisa.',
                        'tipo' => 'checkbox',
                    ],
                    [
                        'nome' => 'aceitouTermoConsentimento',
                        'rotulo' => 'Estou ciente de que este é um registro de interesse preliminar e que, antes de qualquer participação efetiva na pesquisa, apresentarei consentimento por meio do Termo de Consentimento Livre e Esclarecido (TCLE) completo.',
                        'tipo' => 'checkbox',
                    ],
                ],
                'Entrar na lista de espera'
            ) ?>
            <p>Dúvidas? Escreva para <a href="mailto:contato@investimentos369.com">contato@investimentos369.com</a>.</p>
        </section>
    </details>
</div>
