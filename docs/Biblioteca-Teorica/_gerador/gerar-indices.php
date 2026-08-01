<?php
declare(strict_types=1);

/**
 * Gera os seis índices navegáveis da Biblioteca Teórica (Autor, Obra, Ano,
 * Área, Conceito, Motor) a partir dos mesmos datasets usados por gerar.php,
 * garantindo que os índices nunca divirjam dos documentos individuais.
 */

require __DIR__ . '/funcoes.php';

$base = dirname(__DIR__);
$indicesDir = $base . '/Indices';

/** @var list<array{tipo:string,titulo:string,arquivo:string,autor:string,ano:string,area:string,conceitos:array,motores:array}> $itens */
$itens = [];

function pastaDe(string $tipo): string
{
    return match ($tipo) {
        'freud' => 'Freud/Obras',
        'lacan-escritos' => 'Lacan/Escritos',
        'lacan-outros' => 'Lacan/Outros-Escritos',
        'lacan-seminarios' => 'Lacan/Seminarios',
        'referencia' => 'Referencias',
        'psicanalise' => 'Psicanalise',
        'conceito' => 'Conceitos',
        default => '.',
    };
}

foreach (require __DIR__ . '/obras.freud.php' as $o) {
    $itens[] = ['tipo' => 'Obra (Freud)', 'titulo' => $o['titulo'], 'arquivo' => pastaDe('freud') . '/' . slug($o['titulo']) . '.md',
        'autor' => $o['autor'], 'ano' => $o['ano'], 'area' => $o['area'], 'conceitos' => $o['conceitos'], 'motores' => $o['motores']];
}
foreach (require __DIR__ . '/obras.lacan.escritos.php' as $o) {
    $itens[] = ['tipo' => 'Obra (Lacan — Escritos)', 'titulo' => $o['titulo'], 'arquivo' => pastaDe('lacan-escritos') . '/' . slug($o['titulo']) . '.md',
        'autor' => $o['autor'], 'ano' => $o['ano'], 'area' => $o['area'], 'conceitos' => $o['conceitos'], 'motores' => $o['motores']];
}
foreach (require __DIR__ . '/obras.lacan.outros-escritos.php' as $o) {
    $itens[] = ['tipo' => 'Obra (Lacan — Outros Escritos)', 'titulo' => $o['titulo'], 'arquivo' => pastaDe('lacan-outros') . '/' . slug($o['titulo']) . '.md',
        'autor' => $o['autor'], 'ano' => $o['ano'], 'area' => $o['area'], 'conceitos' => $o['conceitos'], 'motores' => $o['motores']];
}
foreach (require __DIR__ . '/obras.lacan.seminarios.php' as $o) {
    $itens[] = ['tipo' => 'Obra (Lacan — Seminário)', 'titulo' => $o['titulo'], 'arquivo' => pastaDe('lacan-seminarios') . '/' . slug($o['titulo']) . '.md',
        'autor' => $o['autor'], 'ano' => $o['ano'], 'area' => $o['area'], 'conceitos' => $o['conceitos'], 'motores' => $o['motores']];
}
foreach (require __DIR__ . '/autores.referencias.php' as $a) {
    $itens[] = ['tipo' => 'Autor (Referência Primária)', 'titulo' => $a['nome'], 'arquivo' => pastaDe('referencia') . '/' . slug($a['nome']) . '.md',
        'autor' => $a['nome'], 'ano' => $a['vida'], 'area' => $a['area'], 'conceitos' => $a['conceitos'], 'motores' => $a['motores']];
}
foreach (require __DIR__ . '/autores.psicanalise.php' as $a) {
    $itens[] = ['tipo' => 'Autor (Psicanálise)', 'titulo' => $a['nome'], 'arquivo' => pastaDe('psicanalise') . '/' . slug($a['nome']) . '.md',
        'autor' => $a['nome'], 'ano' => $a['vida'], 'area' => $a['area'], 'conceitos' => $a['conceitos'], 'motores' => $a['motores']];
}
foreach (require __DIR__ . '/conceitos.php' as $c) {
    $itens[] = ['tipo' => 'Conceito', 'titulo' => $c['conceito'], 'arquivo' => pastaDe('conceito') . '/' . slug($c['conceito']) . '.md',
        'autor' => $c['autor'], 'ano' => $c['ano'], 'area' => $c['area'], 'conceitos' => array_merge([$c['conceito']], $c['conceitos_relacionados']),
        'motores' => $c['aplicacao_computacional']['motores_impactados']];
}

function linkMd(array $item): string
{
    return '[' . $item['titulo'] . '](../' . $item['arquivo'] . ')';
}

// --- Índice de Autores ---
$porAutor = [];
foreach ($itens as $it) {
    $porAutor[$it['autor']][] = $it;
}
ksort($porAutor, SORT_STRING | SORT_FLAG_CASE);
$md = "# Índice por Autor\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/).\n\n";
foreach ($porAutor as $autor => $lista) {
    $md .= "## {$autor}\n\n";
    foreach ($lista as $it) {
        $md .= "- {$it['tipo']}: " . linkMd($it) . " ({$it['ano']})\n";
    }
    $md .= "\n";
}
file_put_contents($indicesDir . '/Indice-Autores.md', $md);

// --- Índice de Obras (e autores, em ordem alfabética de título) ---
$porTitulo = $itens;
usort($porTitulo, fn($a, $b) => strcasecmp($a['titulo'], $b['titulo']));
$md = "# Índice por Obra/Autor (ordem alfabética)\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/).\n\n";
foreach ($porTitulo as $it) {
    $md .= "- " . linkMd($it) . " — {$it['tipo']}, {$it['autor']} ({$it['ano']})\n";
}
file_put_contents($indicesDir . '/Indice-Obras.md', $md);

// --- Índice por Ano ---
$porAno = [];
foreach ($itens as $it) {
    $porAno[$it['ano']][] = $it;
}
uksort($porAno, function ($a, $b) {
    preg_match('/-?\d+/', (string) $a, $ma);
    preg_match('/-?\d+/', (string) $b, $mb);
    $na = $ma[0] ?? 0;
    $nb = $mb[0] ?? 0;
    return $na <=> $nb ?: strcmp((string) $a, (string) $b);
});
$md = "# Índice por Ano\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/). Anos com qualificação textual (ex.: \"publicado postumamente\") são ordenados pelo primeiro número encontrado no campo.\n\n";
foreach ($porAno as $ano => $lista) {
    $md .= "## {$ano}\n\n";
    foreach ($lista as $it) {
        $md .= "- " . linkMd($it) . " — {$it['tipo']}, {$it['autor']}\n";
    }
    $md .= "\n";
}
file_put_contents($indicesDir . '/Indice-Anos.md', $md);

// --- Índice por Área ---
$porArea = [];
foreach ($itens as $it) {
    $porArea[$it['area']][] = $it;
}
ksort($porArea, SORT_STRING | SORT_FLAG_CASE);
$md = "# Índice por Área\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/).\n\n";
foreach ($porArea as $area => $lista) {
    $md .= "## {$area} (" . count($lista) . ")\n\n";
    foreach ($lista as $it) {
        $md .= "- " . linkMd($it) . " — {$it['tipo']}, {$it['autor']} ({$it['ano']})\n";
    }
    $md .= "\n";
}
file_put_contents($indicesDir . '/Indice-Areas.md', $md);

// --- Índice por Conceito ---
$porConceito = [];
foreach ($itens as $it) {
    foreach ($it['conceitos'] as $conceito) {
        $porConceito[$conceito][] = $it;
    }
}
ksort($porConceito, SORT_STRING | SORT_FLAG_CASE);
$md = "# Índice por Conceito\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/). Cada entrada de conceito aqui é um marcador bibliográfico (campo \"Conceitos\" das obras/autores); os 21 conceitos com definição rigorosa e Aplicação Computacional estão em [Conceitos/](../Conceitos/) e citados em [Como-os-Motores-Usam-a-Biblioteca.md](../Como-os-Motores-Usam-a-Biblioteca.md).\n\n";
foreach ($porConceito as $conceito => $lista) {
    $md .= "## {$conceito}\n\n";
    foreach ($lista as $it) {
        $md .= "- " . linkMd($it) . " — {$it['tipo']}, {$it['autor']} ({$it['ano']})\n";
    }
    $md .= "\n";
}
file_put_contents($indicesDir . '/Indice-Conceitos.md', $md);

// --- Índice por Motor do PsycheAI ---
$porMotor = [];
foreach ($itens as $it) {
    foreach ($it['motores'] as $motor) {
        $porMotor[$motor][] = $it;
    }
}
ksort($porMotor, SORT_STRING | SORT_FLAG_CASE);
$md = "# Índice por Motor do PsycheAI\n\n> Gerado a partir dos mesmos datasets que produzem os documentos individuais — nunca editar manualmente, ver [_gerador/](../_gerador/). \"Nenhum (catalogação apenas)\" agrupa toda obra/autor/conceito catalogado sem uso computacional já estabelecido — é o maior grupo desta versão, por design (ver [README.md](../README.md) sobre a decisão de escopo de cobertura).\n\n";
foreach ($porMotor as $motor => $lista) {
    $md .= "## {$motor} (" . count($lista) . ")\n\n";
    foreach ($lista as $it) {
        $md .= "- " . linkMd($it) . " — {$it['tipo']}, {$it['autor']} ({$it['ano']})\n";
    }
    $md .= "\n";
}
file_put_contents($indicesDir . '/Indice-Motores.md', $md);

echo "Índices gerados: Autores (" . count($porAutor) . "), Obras/Autores (" . count($porTitulo) . "), Anos (" . count($porAno) . "), Áreas (" . count($porArea) . "), Conceitos (" . count($porConceito) . "), Motores (" . count($porMotor) . ")\n";
