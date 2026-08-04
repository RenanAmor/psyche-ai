<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

final class EventoDiscursivoViewModel
{
    private const ROTULOS_POR_LOCUTOR = [
        'sujeito' => 'Sujeito',
        'analista' => 'Analista',
        'sistema' => 'Sistema',
        'desconhecido' => 'Desconhecido',
    ];

    public function __construct(
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $posicao,
        public readonly string $discursoId = '',
        public readonly string $sessaoId = '',
        public readonly string $criadoEm = '',
        public readonly string $locutor = 'Desconhecido'
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $locutor = (string) ($dados['locutor'] ?? 'desconhecido');

        return new self(
            id: (string) ($dados['id'] ?? ''),
            conteudo: (string) ($dados['conteudo'] ?? ''),
            posicao: (int) ($dados['posicao'] ?? 0),
            discursoId: (string) ($dados['discursoId'] ?? ''),
            sessaoId: (string) ($dados['sessaoId'] ?? ''),
            criadoEm: (string) ($dados['criadoEm'] ?? ''),
            locutor: self::ROTULOS_POR_LOCUTOR[$locutor] ?? 'Desconhecido'
        );
    }

    /**
     * @param array<int, array<string, mixed>> $lista
     * @return self[]
     */
    public static function fromArrayList(array $lista): array
    {
        return array_map(self::fromArray(...), $lista);
    }
}
