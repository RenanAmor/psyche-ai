<?php
/**
 * @var string $areaConversaHtml
 * @var string $valorConteudo
 *
 * Interface de Voz da ECO (Sprint 32) — substitui a antiga tela de chat
 * por um palco de estados (Pronta/Escutando/Processando/Respondendo/
 * Encerrada), sem caixa de texto nem botão "Enviar" visíveis ao Sujeito.
 * A gravação de um turno é segmentada automaticamente por detecção de
 * silêncio (Web Audio API), com um botão discreto de emergência para
 * concluir a fala manualmente. Cada turno é enviado para
 * POST /conversa/mensagens/audio, que transcreve, segue o mesmo pipeline
 * de uma mensagem digitada (pergunta socrática + áudio de resposta) e
 * devolve `audioRespostaUrl` pronto para tocar — nenhum HTML de bolhas é
 * exibido nesta tela.
 *
 * $areaConversaHtml/$valorConteudo continuam existindo só para o bloco
 * #conversa-dev abaixo — ferramenta de desenvolvimento interna (texto
 * digitado via POST /conversa/enviar), nunca exposta ao Sujeito
 * (permanece com o atributo `hidden`, fora da árvore de acessibilidade).
 */

use PsycheAI\Presentation\Web\Components\FormComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<section class="pagina-eco" id="eco-palco" data-estado="pronta">
    <p class="eco-conta-links">
        <a href="<?= Html::e(BasePath::url('/conversa/cadastro')) ?>">Criar conta</a> ·
        <a href="<?= Html::e(BasePath::url('/conversa/entrar')) ?>">Entrar</a>
    </p>

    <div class="eco-identidade">
        <p class="eco-nome">ECO</p>
        <p class="eco-boas-vindas">Quando quiser, comece a falar.</p>
    </div>

    <div class="eco-indicador" aria-hidden="true">
        <span class="eco-indicador-anel"></span>
    </div>

    <p class="eco-status-texto" id="eco-status-texto" aria-live="polite">Pronta para começar.</p>

    <p class="eco-transcricao" id="eco-transcricao" hidden></p>

    <p class="eco-cronometro" id="eco-cronometro" hidden>00:00</p>

    <div class="eco-controles">
        <button type="button" id="eco-botao-iniciar" class="botao botao-primario">Iniciar sessão</button>
        <button type="button" id="eco-botao-concluir-fala" class="botao botao-secundario" hidden>Concluir minha fala agora</button>
        <button type="button" id="eco-botao-encerrar" class="botao botao-secundario" hidden>Encerrar sessão</button>
    </div>

    <div class="eco-encerrada" id="eco-encerrada" hidden>
        <p>Sessão encerrada.</p>
        <p id="eco-encerrada-duracao"></p>
        <button type="button" id="eco-botao-nova-sessao" class="botao botao-primario">Iniciar nova sessão</button>
    </div>

    <div id="conversa-dev" hidden>
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
    </div>
</section>
<script>
(function () {
    var palco = document.getElementById('eco-palco');
    var statusTexto = document.getElementById('eco-status-texto');
    var transcricaoEl = document.getElementById('eco-transcricao');
    var cronometroEl = document.getElementById('eco-cronometro');
    var botaoIniciar = document.getElementById('eco-botao-iniciar');
    var botaoConcluirFala = document.getElementById('eco-botao-concluir-fala');
    var botaoEncerrar = document.getElementById('eco-botao-encerrar');
    var painelEncerrada = document.getElementById('eco-encerrada');
    var duracaoEncerradaEl = document.getElementById('eco-encerrada-duracao');
    var botaoNovaSessao = document.getElementById('eco-botao-nova-sessao');

    if (!palco || !window.fetch || !navigator.mediaDevices || !window.MediaRecorder) {
        if (statusTexto) {
            statusTexto.textContent = 'Este navegador não tem suporte a voz. Peça ajuda para continuar.';
        }
        return;
    }

    var LIMIAR_VOLUME = 0.02;
    var SILENCIO_MS = 1200;
    var FALA_MINIMA_MS = 400;
    var TURNO_MAXIMO_MS = 30000;

    var fluxo = null;
    var audioContext = null;
    var analyser = null;
    var dadosVolume = null;
    var mediaRecorder = null;
    var pedacos = [];
    var falando = false;
    var inicioFalaEm = 0;
    var silencioDesdeEm = null;
    var turnoIniciadoEm = 0;
    var sessaoAtiva = false;
    var inicioSessaoEm = 0;
    var cronometroIntervalo = null;
    var player = new Audio();

    function definirEstado(estado, texto) {
        palco.dataset.estado = estado;

        if (statusTexto) {
            statusTexto.textContent = texto;
        }
    }

    function formatarDuracao(ms) {
        var segundosTotais = Math.floor(ms / 1000);
        var minutos = Math.floor(segundosTotais / 60);
        var segundos = segundosTotais % 60;

        return (minutos < 10 ? '0' : '') + minutos + ':' + (segundos < 10 ? '0' : '') + segundos;
    }

    function atualizarCronometro() {
        if (cronometroEl) {
            cronometroEl.textContent = formatarDuracao(Date.now() - inicioSessaoEm);
        }
    }

    function iniciarSessao() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function (stream) {
                fluxo = stream;
                audioContext = new (window.AudioContext || window.webkitAudioContext)();

                var fonte = audioContext.createMediaStreamSource(fluxo);
                analyser = audioContext.createAnalyser();
                analyser.fftSize = 512;
                dadosVolume = new Uint8Array(analyser.frequencyBinCount);
                fonte.connect(analyser);

                sessaoAtiva = true;
                inicioSessaoEm = Date.now();
                cronometroEl.hidden = false;
                cronometroIntervalo = setInterval(atualizarCronometro, 1000);
                atualizarCronometro();

                botaoIniciar.hidden = true;
                botaoEncerrar.hidden = false;
                painelEncerrada.hidden = true;

                iniciarEscuta();
                requestAnimationFrame(medirVolume);
            })
            .catch(function () {
                definirEstado('pronta', 'Não foi possível acessar o microfone.');
            });
    }

    function medirVolume() {
        if (!sessaoAtiva) {
            return;
        }

        analyser.getByteTimeDomainData(dadosVolume);

        var soma = 0;
        for (var i = 0; i < dadosVolume.length; i++) {
            var valor = (dadosVolume[i] - 128) / 128;
            soma += valor * valor;
        }
        var rms = Math.sqrt(soma / dadosVolume.length);

        if (palco.dataset.estado === 'escutando') {
            avaliarSilencio(rms);
        }

        requestAnimationFrame(medirVolume);
    }

    function avaliarSilencio(rms) {
        var agora = Date.now();

        if (rms > LIMIAR_VOLUME) {
            if (!falando) {
                falando = true;
                inicioFalaEm = agora;
            }
            silencioDesdeEm = null;
        } else if (falando) {
            if (silencioDesdeEm === null) {
                silencioDesdeEm = agora;
            } else if (agora - silencioDesdeEm >= SILENCIO_MS && agora - inicioFalaEm >= FALA_MINIMA_MS) {
                concluirFala();
                return;
            }
        }

        if (falando && agora - turnoIniciadoEm >= TURNO_MAXIMO_MS) {
            concluirFala();
        }
    }

    function iniciarEscuta() {
        if (!sessaoAtiva) {
            return;
        }

        pedacos = [];
        falando = false;
        silencioDesdeEm = null;
        turnoIniciadoEm = Date.now();

        mediaRecorder = new MediaRecorder(fluxo);

        mediaRecorder.ondataavailable = function (evento) {
            if (evento.data && evento.data.size > 0) {
                pedacos.push(evento.data);
            }
        };

        mediaRecorder.onstop = function () {
            if (pedacos.length === 0) {
                if (sessaoAtiva) {
                    iniciarEscuta();
                }
                return;
            }

            enviarTurno(new Blob(pedacos, { type: mediaRecorder.mimeType || 'audio/webm' }));
        };

        mediaRecorder.start();

        if (transcricaoEl) {
            transcricaoEl.hidden = true;
        }
        botaoConcluirFala.hidden = false;
        definirEstado('escutando', 'Escutando…');
    }

    function concluirFala() {
        if (!mediaRecorder || mediaRecorder.state !== 'recording') {
            return;
        }

        botaoConcluirFala.hidden = true;
        mediaRecorder.stop();
    }

    function enviarTurno(blob) {
        definirEstado('processando', 'Processando…');

        fetch(<?= json_encode(BasePath::url('/conversa/mensagens/audio'), JSON_THROW_ON_ERROR) ?>, {
            method: 'POST',
            body: blob
        })
            .then(function (resposta) { return resposta.json(); })
            .then(function (dados) {
                if (dados.textoTranscrito && transcricaoEl) {
                    transcricaoEl.textContent = dados.textoTranscrito;
                    transcricaoEl.hidden = false;
                }

                if (dados.audioRespostaUrl) {
                    responder(dados.audioRespostaUrl);
                    return;
                }

                if (sessaoAtiva) {
                    iniciarEscuta();
                }
            })
            .catch(function () {
                definirEstado('escutando', 'Falha ao enviar. Continue quando quiser.');

                if (sessaoAtiva) {
                    iniciarEscuta();
                }
            });
    }

    function responder(url) {
        definirEstado('respondendo', 'Respondendo…');
        botaoConcluirFala.hidden = true;

        var seguir = function () {
            if (sessaoAtiva) {
                iniciarEscuta();
            }
        };

        player.pause();
        player.src = url;
        player.onended = seguir;
        player.onerror = seguir;
        player.play().catch(seguir);
    }

    function encerrarSessao() {
        sessaoAtiva = false;

        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.onstop = null;
            mediaRecorder.stop();
        }
        if (fluxo) {
            fluxo.getTracks().forEach(function (faixa) { faixa.stop(); });
        }
        if (audioContext) {
            audioContext.close();
        }
        if (cronometroIntervalo) {
            clearInterval(cronometroIntervalo);
        }

        var duracaoTexto = cronometroEl ? cronometroEl.textContent : '';

        botaoEncerrar.hidden = true;
        botaoConcluirFala.hidden = true;
        if (transcricaoEl) {
            transcricaoEl.hidden = true;
        }

        definirEstado('encerrada', 'Sessão encerrada.');

        if (duracaoEncerradaEl) {
            duracaoEncerradaEl.textContent = 'Duração: ' + duracaoTexto;
        }
        painelEncerrada.hidden = false;
    }

    botaoIniciar.addEventListener('click', iniciarSessao);
    botaoConcluirFala.addEventListener('click', concluirFala);
    botaoEncerrar.addEventListener('click', encerrarSessao);

    if (botaoNovaSessao) {
        botaoNovaSessao.addEventListener('click', function () {
            window.location.reload();
        });
    }
})();
</script>
