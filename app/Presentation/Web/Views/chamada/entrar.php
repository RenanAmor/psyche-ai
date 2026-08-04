<?php
/** @var string $salaUrl */
/** @var string $tokenSujeito */
?>
<section class="pagina-eco">
    <div id="chamada-container" class="chamada-video-container"></div>
    <p id="chamada-erro" class="texto-auxiliar" hidden>Não foi possível entrar na chamada. Verifique sua conexão e recarregue a página.</p>
</section>
<script src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
    (function () {
        var container = document.getElementById('chamada-container');
        var erro = document.getElementById('chamada-erro');

        if (!window.DailyIframe || !container) {
            if (erro) {
                erro.hidden = false;
            }
            return;
        }

        var salaUrl = <?= json_encode($salaUrl, JSON_THROW_ON_ERROR) ?>;
        var tokenSujeito = <?= json_encode($tokenSujeito, JSON_THROW_ON_ERROR) ?>;

        var chamada = window.DailyIframe.createFrame(container, {
            showLeaveButton: true,
            iframeStyle: { width: '100%', height: '100%', border: '0' }
        });

        chamada.join({ url: salaUrl, token: tokenSujeito });
    })();
</script>
