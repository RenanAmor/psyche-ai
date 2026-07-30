<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Infrastructure\AI;

use DateTimeImmutable;
use PsycheAI\Application\Services\MensagemApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\AI\RespostaEcoRecorrenciaService;
use PsycheAI\Infrastructure\AI\RespostaFixaService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

/**
 * Constrói histórico persistido de verdade via MensagemApplicationService
 * (com RespostaFixaService, para não misturar as duas responsabilidades)
 * e então verifica RespostaEcoRecorrenciaService isoladamente contra esse
 * histórico — mesmo padrão de MensagemApplicationServiceTest.
 */
final class RespostaEcoRecorrenciaServiceTest extends SQLiteTestCase
{
    private SQLiteSujeitoRepository $sujeitoRepository;
    private MensagemApplicationService $mensagens;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $sessaoRepository = new SQLiteSessaoRepository($this->pdo);

        $this->mensagens = new MensagemApplicationService(
            $sessaoRepository,
            new RandomUuidGenerator(),
            new RespostaFixaService()
        );

        (new SujeitoApplicationService($this->sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');
        (new SessaoApplicationService($sessaoRepository, $this->sujeitoRepository))
            ->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testDevolveARespostaPadraoQuandoAindaNaoHaNenhumaRepeticao(): void
    {
        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);

        $resposta = $servico->responder('Estou me sentindo ansioso hoje.', 'sujeito-1');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
    }

    public function testDevolveUmaPerguntaEcoQuandoAMensagemJaFoiDitaAntesPeloSujeito(): void
    {
        $this->mensagens->enviar('sessao-1', 'Estou me sentindo ansioso hoje.');

        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);
        $resposta = $servico->responder('Estou me sentindo ansioso hoje.', 'sujeito-1');

        $this->assertStringContainsString('Estou me sentindo ansioso hoje.', $resposta);
        $this->assertStringContainsString('?', $resposta);
        $this->assertStringNotContainsString('Recebi sua mensagem', $resposta);
    }

    public function testComparacaoDeRepeticaoIgnoraEspacosEMaiusculas(): void
    {
        $this->mensagens->enviar('sessao-1', 'Lapso.');

        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);
        $resposta = $servico->responder('  LAPSO. ', 'sujeito-1');

        $this->assertStringContainsString('vem à mente', $resposta);
    }

    public function testNaoConfundeConteudosDiferentesComoRepeticao(): void
    {
        $this->mensagens->enviar('sessao-1', 'Primeira mensagem.');

        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);
        $resposta = $servico->responder('Segunda mensagem, totalmente distinta.', 'sujeito-1');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
    }

    public function testDevolveARespostaPadraoQuandoOSujeitoNaoExiste(): void
    {
        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);

        $resposta = $servico->responder('Qualquer coisa.', 'sujeito-inexistente');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
    }

    public function testDevolveARespostaPadraoQuandoOSujeitoIdEstaVazio(): void
    {
        $servico = new RespostaEcoRecorrenciaService($this->sujeitoRepository);

        $resposta = $servico->responder('Qualquer coisa.');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
    }
}
