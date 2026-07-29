<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

/**
 * Formulário genérico dirigido por dados. Cada campo é um array com as
 * chaves "nome", "rotulo", "tipo" (opcional, padrão "text"), "valor"
 * (opcional) e "erro" (opcional, mensagem de validação já traduzida).
 */
final class FormComponent
{
    /**
     * @param array<int, array{nome: string, rotulo: string, tipo?: string, valor?: string, erro?: ?string}> $campos
     */
    public static function render(string $acao, string $metodo, array $campos, string $rotuloSubmit = 'Salvar'): string
    {
        $camposHtml = '';

        foreach ($campos as $campo) {
            $tipo = $campo['tipo'] ?? 'text';
            $valor = $campo['valor'] ?? '';
            $erro = $campo['erro'] ?? null;

            $camposHtml .= sprintf(
                '<div class="campo-formulario%s"><label for="%s">%s</label>'
                    . '<input type="%s" id="%s" name="%s" value="%s">%s</div>',
                $erro !== null ? ' campo-com-erro' : '',
                Html::e($campo['nome']),
                Html::e($campo['rotulo']),
                Html::e($tipo),
                Html::e($campo['nome']),
                Html::e($campo['nome']),
                Html::e($valor),
                $erro !== null ? sprintf('<span class="mensagem-erro">%s</span>', Html::e($erro)) : ''
            );
        }

        return sprintf(
            '<form class="formulario" action="%s" method="%s">%s%s</form>',
            Html::e($acao),
            Html::e($metodo),
            $camposHtml,
            ButtonComponent::submit($rotuloSubmit)
        );
    }
}
