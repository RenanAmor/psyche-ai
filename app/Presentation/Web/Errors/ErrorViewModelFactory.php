<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Errors;

/**
 * Fábrica das quatro variações de erro exigidas pela Sprint 11A. Mantém
 * as mensagens de exibição centralizadas, para que Controllers e o
 * Cliente HTTP mockado não precisem montá-las manualmente.
 */
final class ErrorViewModelFactory
{
    public static function comunicacao(string $recurso): ErrorViewModel
    {
        return new ErrorViewModel(
            ErrorType::COMUNICACAO,
            'Falha de comunicação',
            sprintf('Não foi possível obter os dados de "%s" no momento. Tente novamente em instantes.', $recurso)
        );
    }

    public static function naoEncontrado(string $recurso): ErrorViewModel
    {
        return new ErrorViewModel(
            ErrorType::NAO_ENCONTRADO,
            'Recurso não encontrado',
            sprintf('O recurso "%s" solicitado não existe ou foi removido.', $recurso)
        );
    }

    /**
     * @param array<string, string> $erros
     */
    public static function validacao(array $erros): ErrorViewModel
    {
        return new ErrorViewModel(
            ErrorType::VALIDACAO,
            'Dados inválidos',
            'Corrija os campos indicados antes de continuar.',
            $erros
        );
    }

    public static function interno(): ErrorViewModel
    {
        return new ErrorViewModel(
            ErrorType::INTERNO,
            'Erro interno',
            'Ocorreu um erro inesperado ao processar sua solicitação.'
        );
    }

    public static function timeout(string $recurso): ErrorViewModel
    {
        return new ErrorViewModel(
            ErrorType::TIMEOUT,
            'Tempo de espera esgotado',
            sprintf('A resposta de "%s" demorou demais e a solicitação foi cancelada. Tente novamente.', $recurso)
        );
    }
}
