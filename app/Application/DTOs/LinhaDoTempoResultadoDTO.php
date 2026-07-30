<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

/**
 * Página de resultado de uma consulta à Linha do Tempo Discursiva de um
 * Sujeito: os itens já filtrados/ordenados/paginados, mais os metadados
 * de paginação necessários para a Apresentação navegar entre páginas.
 */
final class LinhaDoTempoResultadoDTO
{
    /**
     * @param LinhaDoTempoItemDTO[] $itens
     */
    public function __construct(
        public readonly array $itens,
        public readonly int $pagina,
        public readonly int $porPagina,
        public readonly int $total,
        public readonly int $totalDePaginas
    ) {
    }
}
