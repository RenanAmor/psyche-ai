<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;

/**
 * Verifica a raiz de composição de ponta a ponta: cria o grafo completo
 * Sujeito -> Sessao -> Discurso -> EventoDiscursivo -> MemoriaLongitudinal
 * inteiramente através das Application Services (nenhuma delas conhece
 * SQLite diretamente) e confirma que a consistência das relações e as
 * cascatas de remoção se mantêm.
 */
final class ApplicationServiceProviderTest extends TestCase
{
    public function testConstroiEPersisteOGrafoCompletoAtravesDasApplicationServices(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();
        $provider = ApplicationServiceProvider::comPDO($pdo);

        $provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $provider->sessoes()->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $provider->discursos()->criar('sessao-1', 'discurso-1', 'Conteúdo do discurso');
        $provider->discursos()->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);

        $sujeitoCarregado = $provider->sujeitos()->buscarPorId('sujeito-1');
        self::assertSame(1, $sujeitoCarregado->quantidadeDeSessoes);

        $sessaoCarregada = $provider->sessoes()->buscarPorId('sessao-1');
        self::assertSame(1, $sessaoCarregada->quantidadeDeDiscursos);

        $discursoCarregado = $provider->discursos()->buscarPorId('discurso-1');
        self::assertSame(1, $discursoCarregado->quantidadeDeEventos);

        // A construção da memória longitudinal parte da entidade Sujeito
        // (com as sessões já hidratadas), assim como o já existente
        // CicloDeObservacaoService — por isso acessa o repositório
        // diretamente aqui, e não a Application Service, que só expõe DTOs.
        $sujeitoEntidade = (new SQLiteSujeitoRepository($pdo))->findById('sujeito-1');
        $memoriaDto = $provider->memorias()->criar($sujeitoEntidade, 'memoria-1');

        self::assertSame(1, $memoriaDto->quantidadeDeSessoes);
        self::assertNotNull($provider->memorias()->buscarPorId('memoria-1'));

        $provider->sujeitos()->excluir('sujeito-1');

        self::assertNull($provider->sujeitos()->buscarPorId('sujeito-1'));
        self::assertNull($provider->sessoes()->buscarPorId('sessao-1'));
        self::assertNull($provider->discursos()->buscarPorId('discurso-1'));
        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM eventos_discursivos')->fetchColumn());

        // A memória longitudinal referencia a sessão via pivô, mas não é
        // filha do agregado Sujeito — sua remoção é responsabilidade
        // própria e não cascateia a partir da exclusão do Sujeito.
        self::assertNotNull($provider->memorias()->buscarPorId('memoria-1'));
    }
}
