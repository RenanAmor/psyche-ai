<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Web;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Sobe a API REST de verdade (public/index.php) num servidor HTTP real
 * via `php -S`, sobre um arquivo SQLite temporário próprio, para que os
 * testes desta pasta exerçam ApiHttpClient/Controllers da interface web
 * contra respostas HTTP reais — sem nenhum tipo de mock/duplo — cobrindo
 * o requisito "Interface ↔ API" da Sprint 11B.
 */
abstract class RealApiTestCase extends TestCase
{
    /** @var resource|null */
    private static $processo;

    protected static string $baseUrl;

    private static string $dbPath;

    public static function setUpBeforeClass(): void
    {
        self::$dbPath = sys_get_temp_dir() . '/psyche-ai-teste-' . uniqid('', true) . '.sqlite';

        $porta = self::portaDisponivel();
        self::$baseUrl = sprintf('http://127.0.0.1:%d', $porta);

        $raiz = dirname(__DIR__, 3);

        // Redireciona para arquivos (não pipes): no Windows, um pipe anônimo
        // que ninguém lê enche e trava o processo filho assim que o `php -S`
        // acumula algumas linhas de log de requisição no stderr.
        $descritores = [
            1 => ['file', self::$dbPath . '.out.log', 'w'],
            2 => ['file', self::$dbPath . '.err.log', 'w'],
        ];

        $env = array_merge(getenv() ?: [], ['PSYCHEAI_DATABASE_PATH' => self::$dbPath]);

        $processo = proc_open(
            static::comandoServidor($porta),
            $descritores,
            $pipes,
            $raiz,
            $env
        );

        if ($processo === false) {
            throw new RuntimeException('Não foi possível iniciar o servidor de teste da API.');
        }

        self::$processo = $processo;

        self::aguardarServidorPronto($porta);
    }

    /**
     * Comando do servidor de teste. Sobrescrito por FakeApiServerTestCase
     * para servir um "roteador" fixo (fixtures/fake-status-server.php) que
     * devolve status HTTP arbitrários, em vez da API de verdade — usado
     * apenas para exercitar o mapeamento de status/erro de ApiHttpClient
     * em cenários (500, corpo não-JSON) que a API real e saudável nunca
     * produz.
     */
    protected static function comandoServidor(int $porta): string
    {
        return sprintf('php -S 127.0.0.1:%d -t public public/index.php', $porta);
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$processo !== null) {
            // No Windows, proc_open roda o comando via `cmd /c`; proc_terminate()
            // só mata esse cmd.exe intermediário, deixando o `php -S` (processo
            // neto) órfão e ainda escutando na porta. taskkill /T mata a árvore
            // inteira. Em outros sistemas, proc_terminate() já basta.
            if (PHP_OS_FAMILY === 'Windows') {
                $status = proc_get_status(self::$processo);
                exec(sprintf('taskkill /F /T /PID %d >NUL 2>&1', $status['pid']));
            } else {
                proc_terminate(self::$processo);
            }

            proc_close(self::$processo);
            self::$processo = null;
        }

        foreach ([self::$dbPath, self::$dbPath . '.out.log', self::$dbPath . '.err.log'] as $arquivo) {
            if (is_file($arquivo)) {
                unlink($arquivo);
            }
        }
    }

    private static function portaDisponivel(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $codigoErro, $mensagemErro);

        if ($socket === false) {
            throw new RuntimeException(sprintf('Não foi possível reservar uma porta livre: %s', $mensagemErro));
        }

        $nome = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($nome, (int) strrpos($nome, ':') + 1);
    }

    private static function aguardarServidorPronto(int $porta): void
    {
        for ($tentativa = 0; $tentativa < 100; $tentativa++) {
            $conexao = @fsockopen('127.0.0.1', $porta, $codigoErro, $mensagemErro, 0.2);

            if ($conexao !== false) {
                fclose($conexao);

                return;
            }

            usleep(50000);
        }

        throw new RuntimeException('O servidor de teste da API não respondeu a tempo.');
    }

    /**
     * Porta fechada garantida (socket aberto e fechado na hora), usada
     * para simular uma falha real de comunicação (conexão recusada).
     */
    protected static function portaFechada(): int
    {
        return self::portaDisponivel();
    }
}
