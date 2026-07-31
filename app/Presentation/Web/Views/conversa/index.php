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

    <div id="conversa-gravacao" class="conversa-gravacao" hidden>
        <button type="button" id="conversa-gravacao-botao">Gravar</button>
        <span id="conversa-gravacao-status"></span>
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
                tocarUltimaRespostaDoSistema();

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

    // Sprint 24 (Voz de Saída): um único <audio> reaproveitado por toda a
    // tela — tanto pelo botão "🔊" manual de cada bolha (progressive
    // enhancement do link <a href> que já funciona sem JS) quanto pela
    // resposta mais recente do sistema, tocada automaticamente após o
    // envio de uma mensagem.
    var player = new Audio();

    function tocarAudio(url) {
        player.pause();
        player.src = url;
        player.play().catch(function () {
            // Autoplay bloqueado pelo navegador: a pessoa ainda pode ouvir
            // clicando manualmente no botão "🔊" da mensagem.
        });
    }

    function tocarUltimaRespostaDoSistema() {
        var bolhas = area.querySelectorAll('.mensagem-sistema .mensagem-ouvir');
        var ultima = bolhas[bolhas.length - 1];

        if (ultima) {
            tocarAudio(ultima.getAttribute('data-audio-url'));
        }
    }

    area.addEventListener('click', function (evento) {
        var botao = evento.target.closest('.mensagem-ouvir');

        if (!botao) {
            return;
        }

        evento.preventDefault();
        tocarAudio(botao.getAttribute('data-audio-url'));
    });
})();
</script>
<script>
(function () {
    // Sprint 22 (Captura de Áudio da Sessão): grava a sessão inteira como
    // um único áudio contínuo, do clique em "Gravar" até "Encerrar e
    // enviar" — convive com o textarea acima (nenhum dos dois substitui o
    // outro). Sem microfone/MediaRecorder no navegador, a seção inteira
    // permanece oculta (hidden por padrão no HTML) e a conversa continua
    // funcionando só por texto.
    var secao = document.getElementById('conversa-gravacao');
    var botao = document.getElementById('conversa-gravacao-botao');
    var status = document.getElementById('conversa-gravacao-status');

    if (!secao || !botao || !status || !window.fetch || !navigator.mediaDevices || !window.MediaRecorder) {
        return;
    }

    secao.hidden = false;

    var mediaRecorder = null;
    var pedacos = [];

    function definirStatus(texto) {
        status.textContent = texto;
    }

    function iniciarGravacao() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function (fluxo) {
                pedacos = [];
                mediaRecorder = new MediaRecorder(fluxo);

                mediaRecorder.ondataavailable = function (evento) {
                    if (evento.data && evento.data.size > 0) {
                        pedacos.push(evento.data);
                    }
                };

                mediaRecorder.onstop = function () {
                    fluxo.getTracks().forEach(function (faixa) { faixa.stop(); });
                    enviarGravacao(new Blob(pedacos, { type: mediaRecorder.mimeType || 'audio/webm' }));
                };

                mediaRecorder.start();
                botao.textContent = 'Encerrar e enviar gravação';
                definirStatus('Gravando...');
            })
            .catch(function () {
                definirStatus('Não foi possível acessar o microfone.');
            });
    }

    function enviarGravacao(blob) {
        botao.disabled = true;
        definirStatus('Enviando gravação...');

        fetch(<?= json_encode(BasePath::url('/conversa/audio'), JSON_THROW_ON_ERROR) ?>, {
            method: 'POST',
            body: blob
        })
            .then(function (resposta) { return resposta.json(); })
            .then(function (dados) {
                definirStatus(dados.sucesso ? 'Gravação enviada.' : (dados.alerta || 'Falha ao enviar a gravação.'));
            })
            .catch(function () {
                definirStatus('Falha ao enviar a gravação.');
            })
            .finally(function () {
                botao.disabled = false;
                botao.textContent = 'Gravar';
                mediaRecorder = null;
            });
    }

    botao.addEventListener('click', function () {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            return;
        }

        iniciarGravacao();
    });
})();
</script>
