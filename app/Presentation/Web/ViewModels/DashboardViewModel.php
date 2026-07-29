<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Agrega as contagens exibidas nos cartões do Dashboard. Construído a
 * partir das listas já obtidas do Cliente HTTP para cada recurso — não
 * introduz nenhuma regra de agregação além de contar itens.
 */
final class DashboardViewModel
{
    public function __construct(
        public readonly int $totalSujeitos,
        public readonly int $totalSessoes,
        public readonly int $totalDiscursos,
        public readonly int $totalMemorias,
        public readonly int $totalEventosDiscursivos
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $sujeitos
     * @param array<int, array<string, mixed>> $sessoes
     * @param array<int, array<string, mixed>> $discursos
     * @param array<int, array<string, mixed>> $memorias
     * @param array<int, array<string, mixed>> $eventosDiscursivos
     */
    public static function fromListas(
        array $sujeitos,
        array $sessoes,
        array $discursos,
        array $memorias,
        array $eventosDiscursivos
    ): self {
        return new self(
            totalSujeitos: count($sujeitos),
            totalSessoes: count($sessoes),
            totalDiscursos: count($discursos),
            totalMemorias: count($memorias),
            totalEventosDiscursivos: count($eventosDiscursivos)
        );
    }
}
