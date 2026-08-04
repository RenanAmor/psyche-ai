<?php
/** @var string $sessaoId */
/** @var string $salaUrl */
/** @var string $tokenAnalista */
/** @var string $linkMagico */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Http\BasePath;
?>
<section class="pagina-detalhe">
    <div class="campo-formulario">
        <label for="link-magico">Link para o Sujeito entrar (sem login)</label>
        <input type="text" id="link-magico" readonly value="<?= Html::e($linkMagico) ?>">
        <button type="button" id="botao-copiar-link" class="botao botao-secundario">Copiar link</button>
    </div>

    <div id="chamada-container" class="chamada-video-container"></div>

    <div class="pagina-detalhe-acoes">
        <form action="<?= Html::e(BasePath::url('/sessoes/' . $sessaoId . '/videochamada/encerrar')) ?>" method="POST">
            <?= ButtonComponent::submit('Encerrar chamada', 'perigo') ?>
        </form>
        <?= ButtonComponent::link('Voltar', '/sessoes/' . $sessaoId, 'secundario') ?>
    </div>
</section>
<script src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
    (function () {
        var container = document.getElementById('chamada-container');
        var botaoCopiar = document.getElementById('botao-copiar-link');
        var campoLink = document.getElementById('link-magico');

        if (botaoCopiar && campoLink && navigator.clipboard) {
            botaoCopiar.addEventListener('click', function () {
                navigator.clipboard.writeText(campoLink.value).then(function () {
                    botaoCopiar.textContent = 'Copiado!';
                    window.setTimeout(function () { botaoCopiar.textContent = 'Copiar link'; }, 2000);
                });
            });
        }

        if (!window.DailyIframe || !container) {
            return;
        }

        var salaUrl = <?= json_encode($salaUrl, JSON_THROW_ON_ERROR) ?>;
        var tokenAnalista = <?= json_encode($tokenAnalista, JSON_THROW_ON_ERROR) ?>;

        var chamada = window.DailyIframe.createFrame(container, {
            showLeaveButton: true,
            iframeStyle: { width: '100%', height: '480px', border: '0' }
        });

        chamada.join({ url: salaUrl, token: tokenAnalista });
    })();
</script>
