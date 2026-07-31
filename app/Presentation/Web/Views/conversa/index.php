<?php
/**
 * @var string $areaConversaHtml
 * @var string $valorConteudo
 */

use PsycheAI\Presentation\Web\Components\FormComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<section class="pagina-conversa">
    <p class="conversa-conta-links">
        <a href="<?= Html::e(BasePath::url('/conversa/cadastro')) ?>">Criar conta</a> ·
        <a href="<?= Html::e(BasePath::url('/conversa/entrar')) ?>">Entrar</a>
    </p>
    <div id="conversa-area"><?= $areaConversaHtml ?></div>

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
    var area = document.getElementById('conversa-area');
    var form = document.querySelector('.pagina-conversa form');
    var campo = document.getElementById('conteudo');

    function rolarParaOFim() {
        var historico = document.getElementById('historico-mensagens');
        if (historico) {
            historico.scrollTop = historico.scrollHeight;
        }
    }

    rolarParaOFim();

    // Sem fetch() (ou sem form/JS), o formulário continua funcionando
    // normalmente via POST /conversa/enviar com recarregamento de página
    // — o comportamento abaixo é só um aprimoramento progressivo.
    if (!form || !area || !window.fetch) {
        return;
    }

    var usarEnvioNativoDaProximaVez = false;

    form.addEventListener('submit', function (evento) {
        if (usarEnvioNativoDaProximaVez) {
            return;
        }

        evento.preventDefault();

        var corpo = new URLSearchParams();
        corpo.set('conteudo', campo ? campo.value : '');

        fetch(<?= json_encode(BasePath::url('/conversa/mensagens'), JSON_THROW_ON_ERROR) ?>, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: corpo.toString()
        })
            .then(function (resposta) { return resposta.json(); })
            .then(function (dados) {
                area.innerHTML = dados.html;
                rolarParaOFim();

                if (!campo) {
                    return;
                }

                campo.value = dados.sucesso ? '' : (dados.valorConteudo || '');
            })
            .catch(function () {
                // Falha de rede/JS: reenvia pelo caminho clássico
                // (POST /conversa/enviar), que não depende de fetch().
                usarEnvioNativoDaProximaVez = true;
                form.submit();
            });
    });
})();
</script>
