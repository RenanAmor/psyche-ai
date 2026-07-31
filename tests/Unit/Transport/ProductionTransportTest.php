<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Transport;

use PHPUnit\Framework\TestCase;
use PsycheAI\Tests\Concerns\InteractsWithTempDirectories;
use PsycheAI\Tests\Support\FakeFtpClient;
use PsycheAI\Transport\ProductionTransport;

final class ProductionTransportTest extends TestCase
{
    use InteractsWithTempDirectories;

    private string $root;

    protected function setUp(): void
    {
        $this->root = $this->makeTempDirectory('psycheai-transport-root');
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->root);
    }

    public function testUploadsIncludedTreeAndTopLevelFiles(): void
    {
        $this->writeLocalFile('app/Transport/Foo.php', '<?php // foo');
        $this->writeLocalFile('bin/criar-analista.php', '<?php // bin');
        $this->writeLocalFile('public/index.php', '<?php // public');
        $this->writeLocalFile('vendor/autoload.php', '<?php // vendor');
        $this->writeLocalFile('composer.json', '{}');
        $this->writeLocalFile('composer.lock', '{}');

        $ftp = new FakeFtpClient();
        $outcome = $this->makeTransport($ftp)->run();

        self::assertTrue($outcome->connected);
        self::assertSame('<?php // foo', $ftp->files['/app/Transport/Foo.php'] ?? null);
        self::assertSame('<?php // bin', $ftp->files['/bin/criar-analista.php'] ?? null);
        self::assertSame('<?php // public', $ftp->files['/public/index.php'] ?? null);
        self::assertSame('<?php // vendor', $ftp->files['/vendor/autoload.php'] ?? null);
        self::assertSame('{}', $ftp->files['/composer.json'] ?? null);
        self::assertSame('{}', $ftp->files['/composer.lock'] ?? null);
    }

    public function testNeverUploadsFilesInsideLocalStorage(): void
    {
        $this->writeLocalFile('storage/data/psyche-ai.sqlite', 'dados-reais-locais');
        $this->writeLocalFile('storage/logs/app.log', 'log-local');

        $ftp = new FakeFtpClient();
        $outcome = $this->makeTransport($ftp)->run();

        self::assertSame([], $ftp->files);
        self::assertTrue($outcome->connected);
    }

    public function testEnsuresProtectedStorageSubdirectoriesExistRegardlessOfLocalContent(): void
    {
        $ftp = new FakeFtpClient();
        $outcome = $this->makeTransport($ftp)->run();

        self::assertContains('/storage', $ftp->directories);
        self::assertContains('/storage/cache', $ftp->directories);
        self::assertContains('/storage/data', $ftp->directories);
        self::assertContains('/storage/logs', $ftp->directories);

        $statuses = array_map(static fn ($r) => $r->status, $outcome->results);
        self::assertCount(3, array_filter($statuses, static fn ($s) => $s === 'storage_dir_ensured'));
    }

    public function testExcludesTestsDocsReadmeAndGitFromUpload(): void
    {
        $this->writeLocalFile('tests/Unit/FooTest.php', '<?php');
        $this->writeLocalFile('docs/architecture/Alguma.md', '# doc');
        $this->writeLocalFile('README.md', '# readme');
        $this->writeLocalFile('.git/config', '[core]');
        $this->writeLocalFile('.env', 'SEGREDO=1');

        $ftp = new FakeFtpClient();
        $this->makeTransport($ftp)->run();

        self::assertSame([], $ftp->files);
    }

    public function testSkipsUploadWhenRemoteAlreadyHasSameSize(): void
    {
        $this->writeLocalFile('app/Foo.php', '<?php // igual');

        $ftp = new FakeFtpClient();
        $ftp->files['/app/Foo.php'] = '<?php // igual'; // mesmo tamanho

        $outcome = $this->makeTransport($ftp)->run();

        $result = $this->findResult($outcome, 'app/Foo.php');
        self::assertSame('already_current', $result->status);
        self::assertSame(0, $ftp->putCalls);
    }

    public function testConflictWhenRemoteSizeDiffersAndDoesNotOverwrite(): void
    {
        $this->writeLocalFile('app/Foo.php', '<?php // conteudo-local-mais-longo');

        $ftp = new FakeFtpClient();
        $ftp->files['/app/Foo.php'] = '<?php // curto';

        $outcome = $this->makeTransport($ftp)->run();

        $result = $this->findResult($outcome, 'app/Foo.php');
        self::assertSame('conflict', $result->status);
        self::assertSame(0, $ftp->putCalls);
        self::assertSame('<?php // curto', $ftp->files['/app/Foo.php']);
    }

    public function testRemovesOrphanTmpFileBeforeUploading(): void
    {
        $this->writeLocalFile('app/Foo.php', 'conteudo-real');

        $ftp = new FakeFtpClient();
        $ftp->files['/app/.tmp_Foo.php'] = 'lixo-de-tentativa-anterior';

        $outcome = $this->makeTransport($ftp)->run();

        self::assertContains('/app/.tmp_Foo.php', $ftp->deletedPaths);
        self::assertSame('transported', $this->findResult($outcome, 'app/Foo.php')->status);
        self::assertSame('conteudo-real', $ftp->files['/app/Foo.php']);
    }

    public function testProducesErrorAndCleansTmpWhenUploadIsCorrupted(): void
    {
        $this->writeLocalFile('app/Foo.php', 'conteudo-real');

        $ftp = new FakeFtpClient(corruptNextPut: true);
        $outcome = $this->makeTransport($ftp)->run();

        self::assertSame('error', $this->findResult($outcome, 'app/Foo.php')->status);
        self::assertArrayNotHasKey('/app/Foo.php', $ftp->files);
        self::assertArrayNotHasKey('/app/.tmp_Foo.php', $ftp->files);
    }

    public function testRetriesConnectionAfterTransientFailureThenSucceeds(): void
    {
        $this->writeLocalFile('app/Foo.php', 'conteudo-real');

        $ftp = new FakeFtpClient(connectSucceedsOnAttempt: 2);
        $outcome = $this->makeTransport($ftp, retryDelaysSeconds: [0, 0])->run();

        self::assertTrue($outcome->connected);
        self::assertSame(2, $ftp->connectAttempts);
    }

    public function testReportsTotalFailureWhenConnectionNeverSucceeds(): void
    {
        $this->writeLocalFile('app/Foo.php', 'conteudo-real');

        $ftp = new FakeFtpClient(connectSucceedsOnAttempt: 99);
        $outcome = $this->makeTransport($ftp, retryDelaysSeconds: [0, 0])->run();

        self::assertFalse($outcome->connected);
        self::assertSame([], $outcome->results);
        self::assertSame(3, $ftp->connectAttempts);
    }

    public function testInvokesProgressCallbackForEachResult(): void
    {
        $this->writeLocalFile('app/Foo.php', 'a');
        $this->writeLocalFile('bin/Bar.php', 'b');

        $seen = [];
        $this->makeTransport(new FakeFtpClient())->run(function ($result) use (&$seen): void {
            $seen[] = $result->relativePath;
        });

        self::assertContains('app/Foo.php', $seen);
        self::assertContains('bin/Bar.php', $seen);
    }

    private function findResult(\PsycheAI\Transport\DTO\TransportRunOutcome $outcome, string $relativePath): \PsycheAI\Transport\DTO\FileTransportResult
    {
        foreach ($outcome->results as $result) {
            if ($result->relativePath === $relativePath) {
                return $result;
            }
        }

        self::fail("Nenhum resultado encontrado para {$relativePath}");
    }

    private function writeLocalFile(string $relativePath, string $content): void
    {
        $fullPath = $this->root . '/' . $relativePath;
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents($fullPath, $content);
    }

    /**
     * @param array<int, int> $retryDelaysSeconds
     */
    private function makeTransport(FakeFtpClient $ftp, array $retryDelaysSeconds = [0, 0]): ProductionTransport
    {
        return new ProductionTransport(
            localRoot: $this->root,
            ftp: $ftp,
            host: 'ftp.investimentos369.com',
            port: 21,
            user: 'user',
            password: 'secret',
            remoteRoot: '/',
            connectTimeoutSeconds: 1,
            retryDelaysSeconds: $retryDelaysSeconds,
        );
    }
}
