<?php
declare(strict_types=1);

/**
 * Gerador de documentos da Biblioteca Teórica — Sprint "Biblioteca Teórica".
 * Lê os datasets (obras.freud.php, obras.lacan.php, autores.referencias.php,
 * autores.psicanalise.php) e escreve um arquivo Markdown por obra/autor,
 * seguindo rigorosamente o Modelo-de-Documento.md. Script de apoio à
 * catalogação — não faz parte da aplicação PsycheAI, não é código de
 * domínio/aplicação/infraestrutura do produto.
 */

require __DIR__ . '/funcoes.php';

function escreverObra(string $dir, array $o): void
{
    $conteudo = "# {$o['titulo']}\n\n"
        . "- **Autor**: {$o['autor']}\n"
        . "- **Título**: {$o['titulo']}\n"
        . "- **Título original**: {$o['original']}\n"
        . "- **Ano**: {$o['ano']}\n"
        . "- **Idioma**: {$o['idioma']}\n"
        . "- **Tipo**: {$o['tipo']}\n"
        . "- **Área**: {$o['area']}\n"
        . "- **Conceitos**: " . lista($o['conceitos']) . "\n"
        . "- **Autores relacionados**: " . lista($o['autores_relacionados']) . "\n"
        . "- **Obras relacionadas**: " . lista($o['obras_relacionadas']) . "\n"
        . "- **Motores do PsycheAI relacionados**: " . lista($o['motores']) . "\n"
        . "- **Status**: {$o['status']}\n"
        . "- **Observações**: {$o['observacoes']}\n";

    $arquivo = $dir . '/' . slug($o['titulo']) . '.md';
    file_put_contents($arquivo, $conteudo);
}

function escreverAutor(string $dir, array $a): void
{
    $conteudo = "# {$a['nome']}\n\n"
        . "- **Autor**: {$a['nome']}\n"
        . "- **Nascimento / Morte**: {$a['vida']}\n"
        . "- **Nacionalidade**: {$a['nacionalidade']}\n"
        . "- **Área**: {$a['area']}\n"
        . "- **Vínculo com Freud/Lacan**: {$a['vinculo']}\n"
        . "- **Conceitos**: " . lista($a['conceitos']) . "\n"
        . "- **Autores relacionados**: " . lista($a['autores_relacionados']) . "\n"
        . "- **Obras relacionadas**: " . lista($a['obras_relacionadas']) . "\n"
        . "- **Motores do PsycheAI relacionados**: " . lista($a['motores']) . "\n"
        . "- **Status**: {$a['status']}\n"
        . "- **Observações**: {$a['observacoes']}\n";

    $arquivo = $dir . '/' . slug($a['nome']) . '.md';
    file_put_contents($arquivo, $conteudo);
}

function escreverConceito(string $dir, array $c): void
{
    $ac = $c['aplicacao_computacional'];
    $rc = $c['representacao_computacional'];
    $vs = $rc['visao_sujeito'];
    $va = $rc['visao_analista'];
    $conteudo = "# {$c['conceito']}\n\n"
        . "## Metadados\n\n"
        . "- **Autor**: {$c['autor']}\n"
        . "- **Conceito**: {$c['conceito']}\n"
        . "- **Obra de origem**: {$c['obra_origem']}\n"
        . "- **Ano**: {$c['ano']}\n"
        . "- **Idioma**: {$c['idioma']}\n"
        . "- **Área**: {$c['area']}\n"
        . "- **Conceitos relacionados**: " . lista($c['conceitos_relacionados']) . "\n"
        . "- **Autores relacionados**: " . lista($c['autores_relacionados']) . "\n"
        . "- **Obras relacionadas**: " . lista($c['obras_relacionadas']) . "\n"
        . "- **Status**: {$c['status']}\n"
        . "- **Observações**: {$c['observacoes']}\n\n"
        . "## Aplicação Computacional\n\n"
        . "- **Objetivo computacional**: {$ac['objetivo_computacional']}\n"
        . "- **Fundamentação científica**: {$ac['fundamentacao_cientifica']}\n"
        . "- **Dados necessários**: " . lista($ac['dados_necessarios']) . "\n"
        . "- **Dados opcionais**: " . lista($ac['dados_opcionais']) . "\n"
        . "- **Eventos que podem originá-lo**: " . lista($ac['eventos_que_podem_origina_lo']) . "\n"
        . "- **Relações com outros conceitos**: {$ac['relacoes_com_outros_conceitos']}\n"
        . "- **Componentes do PsycheAI que utilizam este conceito**: " . lista($ac['componentes']) . "\n"
        . "- **Pode ser observado automaticamente?**: {$ac['observado_automaticamente']}\n"
        . "- **Pode ser organizado automaticamente?**: {$ac['organizado_automaticamente']}\n"
        . "- **Pode ser classificado automaticamente?**: {$ac['classificado_automaticamente']}\n"
        . "- **Depende de confirmação do sujeito?**: {$ac['depende_confirmacao_sujeito']}\n"
        . "- **Depende de validação do analista?**: {$ac['depende_validacao_analista']}\n"
        . "- **Gera hipótese clínica?**: Nunca automaticamente.\n"
        . "- **Evidências produzidas pelo sistema**: " . lista($ac['evidencias_produzidas']) . "\n"
        . "- **Limitações computacionais**: {$ac['limitacoes_computacionais']}\n"
        . "- **Trabalhos científicos relacionados**: " . lista($ac['trabalhos_relacionados']) . "\n"
        . "- **Motores impactados**: " . lista($ac['motores_impactados']) . "\n\n"
        . "## Representação Computacional\n\n"
        . "### Visão do Sujeito\n\n"
        . "- **Como este conceito interfere na conversa?**: {$vs['interfere_na_conversa']}\n"
        . "- **O sujeito pode perceber sua existência?**: {$vs['sujeito_pode_perceber']}\n"
        . "- **Como a IA deve se comportar diante dele?**: {$vs['comportamento_da_ia']}\n"
        . "- **Quais perguntas podem ser feitas?**: {$vs['perguntas_permitidas']}\n"
        . "- **Quais perguntas nunca podem ser feitas?**: {$vs['perguntas_proibidas']}\n\n"
        . "### Visão do Analista\n\n"
        . "- **Como o conceito é apresentado?**: {$va['como_e_apresentado']}\n"
        . "- **Quais visualizações são produzidas?**: {$va['visualizacoes_produzidas']}\n"
        . "- **Quais relações podem ser exibidas?**: {$va['relacoes_exibidas']}\n"
        . "- **Quais evidências sustentam essa representação?**: " . lista($va['evidencias_que_sustentam']) . "\n"
        . "- **Quais motores produzem essa informação?**: " . lista($va['motores_que_produzem']) . "\n"
        . "- **Quais componentes do sistema participam dessa construção?**: " . lista($va['componentes_participantes']) . "\n";

    $arquivo = $dir . '/' . slug($c['conceito']) . '.md';
    file_put_contents($arquivo, $conteudo);
}

$base = dirname(__DIR__); // docs/Biblioteca-Teorica

$freud = require __DIR__ . '/obras.freud.php';
foreach ($freud as $o) {
    escreverObra($base . '/Freud/Obras', $o);
}
echo "Freud: " . count($freud) . " obras\n";

$lacanEscritos = require __DIR__ . '/obras.lacan.escritos.php';
foreach ($lacanEscritos as $o) {
    escreverObra($base . '/Lacan/Escritos', $o);
}
echo "Lacan/Escritos: " . count($lacanEscritos) . " obras\n";

$lacanOutros = require __DIR__ . '/obras.lacan.outros-escritos.php';
foreach ($lacanOutros as $o) {
    escreverObra($base . '/Lacan/Outros-Escritos', $o);
}
echo "Lacan/Outros-Escritos: " . count($lacanOutros) . " obras\n";

$lacanSeminarios = require __DIR__ . '/obras.lacan.seminarios.php';
foreach ($lacanSeminarios as $o) {
    escreverObra($base . '/Lacan/Seminarios', $o);
}
echo "Lacan/Seminarios: " . count($lacanSeminarios) . " obras\n";

$referencias = require __DIR__ . '/autores.referencias.php';
foreach ($referencias as $a) {
    escreverAutor($base . '/Referencias', $a);
}
echo "Referencias: " . count($referencias) . " autores\n";

$psicanalise = require __DIR__ . '/autores.psicanalise.php';
foreach ($psicanalise as $a) {
    escreverAutor($base . '/Psicanalise', $a);
}
echo "Psicanalise: " . count($psicanalise) . " autores\n";

$conceitos = require __DIR__ . '/conceitos.php';
foreach ($conceitos as $c) {
    escreverConceito($base . '/Conceitos', $c);
}
echo "Conceitos: " . count($conceitos) . " conceitos\n";
