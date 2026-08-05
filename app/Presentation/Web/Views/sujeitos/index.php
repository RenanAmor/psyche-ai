<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel[] $sujeitos */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\Html;
use PsycheAI\Presentation\Web\Components\TableComponent;
use PsycheAI\Presentation\Web\Http\BasePath;

$linhas = array_map(
    static fn ($sujeito) => [
        'id' => $sujeito->id,
        'nome' => $sujeito->nome,
        'quantidadeDeSessoes' => $sujeito->quantidadeDeSessoes,
        'acoes' => ButtonComponent::link('Ver', '/sujeitos/' . $sujeito->id, 'secundario')
            . ' ' . ButtonComponent::link('Editar', '/sujeitos/' . $sujeito->id . '/editar', 'secundario')
            . ' ' . sprintf(
                '<form class="formulario-exclusao formulario-exclusao-linha" action="%s" method="POST">%s</form>',
                Html::e(BasePath::url('/sujeitos/' . $sujeito->id . '/excluir')),
                ButtonComponent::submit('Excluir', 'perigo')
            ),
    ],
    $sujeitos
);
?>
<section class="pagina-lista">
    <div class="pagina-lista-acoes">
        <?= ButtonComponent::link('Novo Sujeito', '/sujeitos/novo') ?>
    </div>
    <?= TableComponent::render(
        ['id' => 'ID', 'nome' => 'Nome', 'quantidadeDeSessoes' => 'Sessões', 'acoes' => 'Ações'],
        $linhas,
        'Nenhum sujeito cadastrado.',
        ['acoes']
    ) ?>
</section>
