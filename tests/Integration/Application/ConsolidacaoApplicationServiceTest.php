<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\ConsolidacaoApplicationService;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\MemoriaApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteMemoriaRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class ConsolidacaoApplicationServiceTest extends SQLiteTestCase
{
    private ConsolidacaoApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $discursoRepository = new SQLiteDiscursoRepository($this->pdo);
        $memoriaRepository = new SQLiteMemoriaRepository($this->pdo);

        $this->service = new ConsolidacaoApplicationService($sujeitoRepository, $memoriaRepository, $sessaoRepository);

        $sujeitos = new SujeitoApplicationService($sujeitoRepository);
        $sessoes = new SessaoApplicationService($sessaoRepository, $sujeitoRepository);
        $discursos = new DiscursoApplicationService($discursoRepository, $sessaoRepository);
        $memorias = new MemoriaApplicationService($memoriaRepository);

        $sujeitos->criar('sujeito-1', 'Sujeito Um');
        $sessoes->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $sessoes->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
        $discursos->criar('sessao-1', 'discurso-1', 'Conteúdo A');
        $discursos->criar('sessao-2', 'discurso-2', 'Conteúdo B');
        $discursos->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 0);
        $discursos->adicionarEvento('discurso-1', 'evento-2', 'Chiste', 1);
        $memorias->criar($sujeitoRepository->findById('sujeito-1'), 'memoria-1');

        // Segundo Sujeito, para garantir que a consolidação de um não
        // soma recursos do outro.
        $sujeitos->criar('sujeito-2', 'Sujeito Dois');
        $sessoes->criar('sujeito-2', 'sessao-x', new DateTimeImmutable('2026-01-15 10:00:00'));
        $memorias->criar($sujeitoRepository->findById('sujeito-2'), 'memoria-x');
    }

    public function testConsolidarLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->consolidar('inexistente');
    }

    public function testConsolidarContaSessoesDiscursosEventosEMemoriasApenasDoSujeito(): void
    {
        $dto = $this->service->consolidar('sujeito-1');

        self::assertSame('sujeito-1', $dto->sujeitoId);
        self::assertSame(2, $dto->quantidadeDeSessoes);
        self::assertSame(2, $dto->quantidadeDeDiscursos);
        self::assertSame(2, $dto->quantidadeDeEventosDiscursivos);
        self::assertSame(1, $dto->quantidadeDeMemorias);
    }

    public function testConsolidarDeUmSujeitoSemProducaoDevolveZerosMenosSessoes(): void
    {
        $dto = $this->service->consolidar('sujeito-2');

        self::assertSame(1, $dto->quantidadeDeSessoes);
        self::assertSame(0, $dto->quantidadeDeDiscursos);
        self::assertSame(0, $dto->quantidadeDeEventosDiscursivos);
        self::assertSame(1, $dto->quantidadeDeMemorias);
    }
}
