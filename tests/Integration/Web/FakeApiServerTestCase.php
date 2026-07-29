<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Web;

abstract class FakeApiServerTestCase extends RealApiTestCase
{
    protected static function comandoServidor(int $porta): string
    {
        $roteador = __DIR__ . '/Fixtures/fake-status-server.php';
        $docRoot = __DIR__ . '/Fixtures';

        return sprintf('php -S 127.0.0.1:%d -t "%s" "%s"', $porta, $docRoot, $roteador);
    }
}
