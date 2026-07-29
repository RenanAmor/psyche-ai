<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Errors;

/**
 * Catálogo fechado dos tipos de erro que a interface web sabe exibir.
 * Nesta Sprint todos são produzidos de forma simulada (mock), mas o
 * mesmo catálogo será reutilizado quando o Cliente HTTP passar a
 * conversar com a API REST real.
 */
enum ErrorType: string
{
    case COMUNICACAO = 'comunicacao';
    case NAO_ENCONTRADO = 'nao_encontrado';
    case VALIDACAO = 'validacao';
    case INTERNO = 'interno';
}
