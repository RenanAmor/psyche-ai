<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

/**
 * Vocabulário fechado do Motor Freud (revisão pós-Sprint 17, "atenção
 * flutuante"): as espécies de formação de compromisso e a repetição,
 * conforme Ontologia-Freud.md §3 (Ato falho, Chiste, Sonhos, Formação de
 * compromisso, Repetição). Um rótulo aqui é sempre uma classificação de
 * forma — nunca uma hipótese sobre o conteúdo ou sobre o sujeito
 * (Documento-Mestre.md §6.5; Regras-Dominio.md, Regra 7).
 *
 * `NaoClassificado` é o caso de fallback determinístico: usado sempre que
 * a classificação não puder ser confirmada dentro deste vocabulário
 * fechado — nunca um valor livre é aceito no lugar de um destes casos.
 */
enum TipoFormacaoFreudiana: string
{
    case AtoFalho = 'ato_falho';
    case Chiste = 'chiste';
    case Sonho = 'sonho';
    case Repeticao = 'repeticao';
    case FormacaoDeCompromisso = 'formacao_de_compromisso';
    case NaoClassificado = 'nao_classificado';
}
