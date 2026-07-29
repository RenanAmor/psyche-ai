<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class ModalComponent
{
    public static function render(string $id, string $titulo, string $corpo, string $rotuloConfirmar = 'Confirmar'): string
    {
        return sprintf(
            '<div class="modal" id="%s" hidden><div class="modal-conteudo">'
                . '<h2 class="modal-titulo">%s</h2><div class="modal-corpo">%s</div>'
                . '<div class="modal-acoes">%s%s</div></div></div>',
            Html::e($id),
            Html::e($titulo),
            Html::e($corpo),
            ButtonComponent::submit($rotuloConfirmar, 'perigo'),
            sprintf('<button type="button" class="botao botao-secundario" data-fecha-modal="%s">Cancelar</button>', Html::e($id))
        );
    }
}
