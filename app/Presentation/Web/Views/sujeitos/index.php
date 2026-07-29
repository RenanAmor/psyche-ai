<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel[] $sujeitos */

use PsycheAI\Presentation\Web\Components\ButtonComponent;
use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($sujeito) => [
        'id' => $sujeito->id,
        'nome' => $sujeito->nome,
        'quantidadeDeSessoes' => $sujeito->quantidadeDeSessoes,
    ],
    $sujeitos
);
?>
<section class="pagina-lista">
    <div class="pagina-lista-acoes">
        <?= ButtonComponent::link('Novo Sujeito', '/sujeitos/novo') ?>
    </div>
    <?= TableComponent::render(
        ['id' => 'ID', 'nome' => 'Nome', 'quantidadeDeSessoes' => 'Sessões'],
        $linhas,
        'Nenhum sujeito cadastrado.'
    ) ?>
</section>
