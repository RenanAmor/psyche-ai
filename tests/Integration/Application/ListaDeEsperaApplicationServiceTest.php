<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO;
use PsycheAI\Application\Services\ListaDeEsperaApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteListaDeEsperaRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;
use PsycheAI\Tests\Support\EmailSpy;
use RuntimeException;

final class ListaDeEsperaApplicationServiceTest extends SQLiteTestCase
{
    private ListaDeEsperaApplicationService $service;
    private EmailSpy $emailSpy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailSpy = new EmailSpy();
        $this->service = new ListaDeEsperaApplicationService(
            new SQLiteListaDeEsperaRepository($this->pdo),
            new RandomUuidGenerator(),
            $this->emailSpy
        );
    }

    private function inscrever(string $email = 'interessado@psyche.ai'): InscricaoListaDeEsperaDTO
    {
        return $this->service->inscrever(
            $email,
            'Ana Interessada',
            'Psicóloga',
            'Universidade Federal',
            'Brasil/SP',
            'Quero participar da pesquisa.',
            true,
            true
        );
    }

    public function testInscreveEPersisteComIdGerado(): void
    {
        $dto = $this->inscrever();

        $this->assertNotSame('', $dto->id);
        $this->assertSame('interessado@psyche.ai', $dto->email);
        $this->assertSame('Ana Interessada', $dto->nome);
    }

    public function testInscreverComEmailJaExistenteNaoDuplicaERetornaAInscricaoOriginal(): void
    {
        $primeira = $this->inscrever();
        $segunda = $this->inscrever();

        $this->assertSame($primeira->id, $segunda->id);
        $this->assertCount(1, $this->service->listar());
    }

    public function testListarRetornaVazioPorPadrao(): void
    {
        $this->assertSame([], $this->service->listar());
    }

    public function testListarRetornaTodasAsInscricoes(): void
    {
        $this->inscrever('um@psyche.ai');
        $this->inscrever('dois@psyche.ai');

        $this->assertCount(2, $this->service->listar());
    }

    public function testInscreverEnviaUmEmailDeConfirmacaoParaOInscrito(): void
    {
        $this->inscrever();

        $this->assertCount(1, $this->emailSpy->enviados);
        $this->assertSame('interessado@psyche.ai', $this->emailSpy->enviados[0]['destinatarioEmail']);
        $this->assertSame('Ana Interessada', $this->emailSpy->enviados[0]['destinatarioNome']);
        $this->assertStringContainsString('lista de espera', $this->emailSpy->enviados[0]['assunto']);
    }

    public function testInscreverComEmailJaExistenteNaoReenviaOEmailDeConfirmacao(): void
    {
        $this->inscrever();
        $this->inscrever();

        $this->assertCount(1, $this->emailSpy->enviados);
    }

    public function testInscreverComOrigemEcopsyEnviaPeloEmailDedicadoEmVezDoPadrao(): void
    {
        $emailEcopsy = new EmailSpy();
        $servicoComIdentidadeEcopsy = new ListaDeEsperaApplicationService(
            new SQLiteListaDeEsperaRepository($this->pdo),
            new RandomUuidGenerator(),
            $this->emailSpy,
            emailEcopsy: $emailEcopsy
        );

        $servicoComIdentidadeEcopsy->inscrever(
            'interessado@psyche.ai',
            'Ana Interessada',
            null,
            null,
            'Brasil/SP',
            'Quero participar da pesquisa.',
            true,
            true,
            'ecopsy'
        );

        $this->assertCount(1, $emailEcopsy->enviados);
        $this->assertCount(0, $this->emailSpy->enviados);
        $this->assertStringContainsString('contato@ecopsy.online', $emailEcopsy->enviados[0]['corpoTexto']);
    }

    public function testInscreverSemOrigemContinuaUsandoOEmailPadraoComContatoDoInvestimentos369(): void
    {
        $this->inscrever();

        $this->assertStringContainsString('contato@investimentos369.com', $this->emailSpy->enviados[0]['corpoTexto']);
    }

    public function testFalhaAoEnviarOEmailDeConfirmacaoNaoImpedeAInscricao(): void
    {
        $servicoComEmailFalho = new ListaDeEsperaApplicationService(
            new SQLiteListaDeEsperaRepository($this->pdo),
            new RandomUuidGenerator(),
            new EmailSpy(new RuntimeException('falha simulada de SMTP'))
        );

        $dto = $servicoComEmailFalho->inscrever(
            'interessado@psyche.ai',
            'Ana Interessada',
            null,
            null,
            'Brasil/SP',
            'Quero participar da pesquisa.',
            true,
            true
        );

        $this->assertNotSame('', $dto->id);
        $this->assertCount(1, $servicoComEmailFalho->listar());
    }
}
