<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\EventoDiscursivo;

final class DetectorRecorrencias implements DomainServiceInterface
{
    /**
     * @param EventoDiscursivo[] $eventos
     * @return array<string,int>
     */
    public function detectar(array $eventos): array
    {
        $recorrencias = [];

        foreach ($eventos as $evento) {
            $conteudo = self::normalizar($evento->conteudo()->valor());

            if (!isset($recorrencias[$conteudo])) {
                $recorrencias[$conteudo] = 0;
            }

            $recorrencias[$conteudo]++;
        }

        return $recorrencias;
    }

    /**
     * Atenção flutuante: variações triviais de grafia (espaços nas bordas,
     * caixa) não podem fragmentar a mesma repetição em contagens
     * separadas — normalização textual determinística, sem similaridade
     * semântica nem NLP.
     *
     * Pública para que quem precise comparar um texto avulso contra as
     * chaves já normalizadas de detectar() (ex.: RespostaEcoRecorrenciaService,
     * Sprint 17) use exatamente a mesma regra, sem duplicá-la.
     */
    public static function normalizar(string $conteudo): string
    {
        return mb_strtolower(trim($conteudo));
    }
}