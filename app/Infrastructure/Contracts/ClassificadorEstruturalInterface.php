<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

/**
 * Porta para classificação estrutural de um conteúdo discursivo dentro do
 * vocabulário fechado de TipoFormacaoFreudiana. Quem implementa esta
 * interface decide como classificar (LLM, regra determinística, etc.) —
 * a Application nunca conhece a implementação concreta, só este contrato.
 *
 * Implementações devem devolver TipoFormacaoFreudiana::NaoClassificado
 * em qualquer situação de incerteza (saída inválida, erro de rede, etc.)
 * em vez de lançar exceção ou devolver um valor fora do enum — nunca deve
 * propagar texto livre nem interpretação de conteúdo além do rótulo.
 */
interface ClassificadorEstruturalInterface
{
    public function classificar(string $conteudo): TipoFormacaoFreudiana;
}
