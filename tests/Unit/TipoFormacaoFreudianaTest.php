<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

final class TipoFormacaoFreudianaTest extends TestCase
{
    public function testCasesCorrespondemAoVocabularioFechadoDeOntologiaFreud(): void
    {
        $this->assertSame(TipoFormacaoFreudiana::AtoFalho, TipoFormacaoFreudiana::from('ato_falho'));
        $this->assertSame(TipoFormacaoFreudiana::Chiste, TipoFormacaoFreudiana::from('chiste'));
        $this->assertSame(TipoFormacaoFreudiana::Sonho, TipoFormacaoFreudiana::from('sonho'));
        $this->assertSame(TipoFormacaoFreudiana::Repeticao, TipoFormacaoFreudiana::from('repeticao'));
        $this->assertSame(
            TipoFormacaoFreudiana::FormacaoDeCompromisso,
            TipoFormacaoFreudiana::from('formacao_de_compromisso')
        );
        $this->assertSame(TipoFormacaoFreudiana::NaoClassificado, TipoFormacaoFreudiana::from('nao_classificado'));
    }

    public function testTryFromDevolveNullParaValorForaDoVocabularioFechado(): void
    {
        $this->assertNull(TipoFormacaoFreudiana::tryFrom('hipotese_diagnostica'));
        $this->assertNull(TipoFormacaoFreudiana::tryFrom(''));
    }
}
