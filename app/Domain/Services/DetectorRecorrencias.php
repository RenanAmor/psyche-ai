<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;

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
     * "Mapear a pulsão, todo o caminho" (revisão pós-Sprint 16): quando/onde
     * cada conteúdo normalizado reaparece através das Sessões da Memória
     * Longitudinal — o circuito/trajeto de uma recorrência ao longo do
     * tempo, não só a contagem pontual de detectar(). Usa exatamente a
     * mesma normalizar() de detectar(), para que "mesma recorrência" nunca
     * divirja entre a contagem e o circuito. A ordem cronológica das
     * ocorrências vem de graça: MemoriaLongitudinal já guarda as Sessões
     * ordenadas por data (Regra 5, docs/Regras-Dominio.md), via
     * OrganizadorMemoria.
     *
     * @return array<string, OcorrenciaRecorrencia[]> conteúdo normalizado => ocorrências em ordem cronológica
     */
    public function detectarCircuito(MemoriaLongitudinal $memoria): array
    {
        $ocorrencias = [];

        foreach ($memoria->sessoes() as $sessao) {
            foreach ($sessao->discursos() as $discurso) {
                foreach ($discurso->eventos() as $evento) {
                    $conteudo = self::normalizar($evento->conteudo()->valor());

                    $ocorrencias[$conteudo][] = new OcorrenciaRecorrencia(
                        $sessao->id()->valor(),
                        $discurso->id()->valor(),
                        $evento->id()->valor(),
                        $sessao->data()->valor(),
                        $evento->posicao()->valor()
                    );
                }
            }
        }

        return $ocorrencias;
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