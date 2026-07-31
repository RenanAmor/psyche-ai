<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

/**
 * Estado operacional do pipeline de transcrição de uma GravacaoAudio —
 * puro metadado de processamento, nunca conteúdo produzido pelo sujeito
 * (Regras-Dominio.md, Regra 2 protege o conteúdo do discurso, não o
 * status de uma etapa mecânica de pipeline).
 */
enum StatusTranscricao: string
{
    case Pendente = 'pendente';
    case Transcrita = 'transcrita';
    case Falha = 'falha';
}
