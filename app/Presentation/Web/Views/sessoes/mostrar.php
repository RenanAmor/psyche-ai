<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SessaoViewModel $sessao */
/** @var array{conteudo: string, atualizadoEm: ?string} $anotacao */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = [[
    'id' => $sessao->id,
    'data' => $sessao->data,
    'quantidadeDeDiscursos' => $sessao->quantidadeDeDiscursos,
]];
?>
<section class="pagina-detalhe">
    <?= TableComponent::render(
        ['id' => 'ID', 'data' => 'Data', 'quantidadeDeDiscursos' => 'Discursos'],
        $linhas
    ) ?>
    <div class="pagina-detalhe-gravacao">
        <?php /* Sprint 22: se a sessão nunca foi gravada em áudio, o navegador
                 apenas falha ao carregar a fonte — sem checagem prévia de
                 existência, para não buscar o áudio duas vezes (uma para
                 checar, outra para tocar). O texto já transcrito continua
                 disponível nas telas de Discursos/Eventos Discursivos. */ ?>
        <audio controls preload="none" src="<?= Html::e(BasePath::url('/sessoes/' . $sessao->id . '/audio')) ?>"></audio>
    </div>

    <div class="campo-formulario" id="anotacoes-bloco">
        <label for="anotacoes-texto">Anotações da sessão</label>
        <textarea id="anotacoes-texto" rows="8"><?= Html::e($anotacao['conteudo']) ?></textarea>
        <p class="texto-auxiliar" id="anotacoes-status" aria-live="polite"></p>
    </div>

    <div class="pagina-detalhe-acoes">
        <?= ButtonComponent::link('Iniciar Videochamada', '/sessoes/' . $sessao->id . '/videochamada') ?>
        <?= ButtonComponent::link('Editar', '/sessoes/' . $sessao->id . '/editar') ?>
        <form class="formulario-exclusao" action="<?= Html::e(BasePath::url('/sessoes/' . $sessao->id . '/excluir')) ?>" method="POST">
            <?= ButtonComponent::submit('Excluir', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/sessoes', 'secundario') ?>
    </div>
</section>
<script>
    (function () {
        var textarea = document.getElementById('anotacoes-texto');
        var status = document.getElementById('anotacoes-status');

        if (!window.fetch || !window.localStorage || !textarea || !status) {
            return;
        }

        var sessaoId = <?= json_encode($sessao->id, JSON_THROW_ON_ERROR) ?>;
        var urlSalvar = <?= json_encode(BasePath::url('/sessoes/' . $sessao->id . '/anotacoes'), JSON_THROW_ON_ERROR) ?>;
        var chaveRascunho = 'psyche_anotacao_' + sessaoId;
        var ultimoConteudoSalvo = textarea.value;
        var timerDebounce = null;
        var DEBOUNCE_MS = 1500;

        var rascunho = window.localStorage.getItem(chaveRascunho);
        if (rascunho !== null && rascunho !== textarea.value) {
            textarea.value = rascunho;
            agendarSalvamento();
        }

        function formatarHora(data) {
            var duasCasas = function (n) { return n < 10 ? '0' + n : String(n); };
            return duasCasas(data.getHours()) + ':' + duasCasas(data.getMinutes());
        }

        function agendarSalvamento() {
            status.textContent = 'Digitando…';
            window.localStorage.setItem(chaveRascunho, textarea.value);

            if (timerDebounce) {
                window.clearTimeout(timerDebounce);
            }

            timerDebounce = window.setTimeout(salvar, DEBOUNCE_MS);
        }

        function salvar() {
            var conteudo = textarea.value;
            status.textContent = 'Salvando…';

            fetch(urlSalvar, {
                method: 'POST',
                body: new URLSearchParams({ conteudo: conteudo })
            })
                .then(function (resposta) { return resposta.json(); })
                .then(function (dados) {
                    if (!dados.sucesso) {
                        throw new Error('falha ao salvar');
                    }

                    ultimoConteudoSalvo = conteudo;
                    window.localStorage.removeItem(chaveRascunho);
                    status.textContent = 'Salvo às ' + formatarHora(new Date());
                })
                .catch(function () {
                    status.textContent = 'Não foi possível salvar agora. Tentando de novo…';
                    timerDebounce = window.setTimeout(salvar, DEBOUNCE_MS);
                });
        }

        textarea.addEventListener('input', agendarSalvamento);

        window.addEventListener('beforeunload', function (evento) {
            if (textarea.value !== ultimoConteudoSalvo) {
                evento.preventDefault();
                evento.returnValue = '';
            }
        });
    })();
</script>
