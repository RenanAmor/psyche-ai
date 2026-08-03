<?php
/** @var \PsycheAI\Presentation\Web\ViewModels\InscricaoListaDeEsperaViewModel[] $inscricoes */

use PsycheAI\Presentation\Web\Components\TableComponent;

$linhas = array_map(
    static fn ($inscricao) => [
        'nome' => $inscricao->nome,
        'email' => $inscricao->email,
        'profissao' => $inscricao->profissao,
        'instituicao' => $inscricao->instituicao,
        'paisEstado' => $inscricao->paisEstado,
        'motivoInteresse' => $inscricao->motivoInteresse,
        'criadoEm' => $inscricao->criadoEm,
    ],
    $inscricoes
);
?>
<section class="pagina-lista">
    <?= TableComponent::render(
        [
            'nome' => 'Nome',
            'email' => 'E-mail',
            'profissao' => 'Profissão',
            'instituicao' => 'Instituição',
            'paisEstado' => 'País/Estado',
            'motivoInteresse' => 'Motivo do interesse',
            'criadoEm' => 'Inscrito em',
        ],
        $linhas,
        'Nenhuma inscrição na lista de espera até o momento.'
    ) ?>
</section>
