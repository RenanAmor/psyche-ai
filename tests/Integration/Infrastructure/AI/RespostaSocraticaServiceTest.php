<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Infrastructure\AI;

use DateTimeImmutable;
use PsycheAI\Application\Services\MensagemApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaHandler;
use PsycheAI\Infrastructure\AI\RespostaEcoRecorrenciaService;
use PsycheAI\Infrastructure\AI\RespostaFixaService;
use PsycheAI\Infrastructure\AI\RespostaSocraticaService;
use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;
use PsycheAI\Infrastructure\Contracts\GeradorDePerguntaSocraticaInterface;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

/**
 * Constrói histórico persistido de verdade via MensagemApplicationService
 * (com RespostaFixaService, para não misturar as duas responsabilidades)
 * e então verifica RespostaSocraticaService isoladamente contra esse
 * histórico — mesmo padrão de RespostaEcoRecorrenciaServiceTest.
 */
final class RespostaSocraticaServiceTest extends SQLiteTestCase
{
    private SQLiteSujeitoRepository $sujeitoRepository;
    private SQLiteSessaoRepository $sessaoRepository;
    private MensagemApplicationService $mensagens;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $this->sessaoRepository = new SQLiteSessaoRepository($this->pdo);

        $this->mensagens = new MensagemApplicationService(
            $this->sessaoRepository,
            new RandomUuidGenerator(),
            new RespostaFixaService()
        );

        (new SujeitoApplicationService($this->sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');
        (new SessaoApplicationService($this->sessaoRepository, $this->sujeitoRepository))
            ->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testDevolveARespostaDeSegurancaSemChamarOLlmQuandoOSujeitoNaoExiste(): void
    {
        $gerador = $this->geradorQueConta();
        $servico = $this->criarServico($gerador->fake);

        $resposta = $servico->responder('Qualquer coisa.', 'sujeito-inexistente', 'sessao-1');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
        $this->assertSame(0, $gerador->chamadas);
    }

    public function testDevolveAPerguntaDoLlmVerbatimQuandoOGuardrailPassa(): void
    {
        $gerador = $this->geradorFixo('O que mais vem à mente sobre isso?');
        $servico = $this->criarServico($gerador);

        $resposta = $servico->responder('Estou ansioso hoje.', 'sujeito-1', 'sessao-1');

        $this->assertSame('O que mais vem à mente sobre isso?', $resposta);
    }

    public function testCaiNoFallbackDeterministicoQuandoOLlmDevolveNull(): void
    {
        $gerador = $this->geradorFixo(null);
        $servico = $this->criarServico($gerador);

        $resposta = $servico->responder('Estou ansioso hoje.', 'sujeito-1', 'sessao-1');

        $this->assertSame('Recebi sua mensagem. Continue falando livremente.', $resposta);
    }

    public function testCaiNaPerguntaEcoDeterministicaQuandoOLlmFalhaEHaRepeticao(): void
    {
        $this->mensagens->enviar('sessao-1', 'Estou me sentindo ansioso hoje.');

        $gerador = $this->geradorFixo(null);
        $servico = $this->criarServico($gerador);

        $resposta = $servico->responder('Estou me sentindo ansioso hoje.', 'sujeito-1', 'sessao-1');

        $this->assertStringContainsString('Estou me sentindo ansioso hoje.', $resposta);
        $this->assertStringContainsString('?', $resposta);
    }

    public function testPassaEhRepeticaoEDescricaoNoContextoQuandoHaRepeticao(): void
    {
        $this->mensagens->enviar('sessao-1', 'Estou me sentindo ansioso hoje.');

        $espiao = $this->geradorEspiao();
        $servico = $this->criarServico($espiao->fake);

        $servico->responder('Estou me sentindo ansioso hoje.', 'sujeito-1', 'sessao-1');

        $this->assertNotNull($espiao->contextoRecebido);
        $this->assertTrue($espiao->contextoRecebido->ehRepeticao);
        $this->assertSame('estou me sentindo ansioso hoje.', $espiao->contextoRecebido->descricaoRecorrencia);
    }

    public function testMontaOsTurnosRecentesDaSessaoAtualNaOrdemCronologica(): void
    {
        $this->mensagens->enviar('sessao-1', 'Primeira mensagem.');
        $this->mensagens->enviar('sessao-1', 'Segunda mensagem.');

        $espiao = $this->geradorEspiao();
        $servico = $this->criarServico($espiao->fake);

        $servico->responder('Terceira mensagem.', 'sujeito-1', 'sessao-1');

        $this->assertNotNull($espiao->contextoRecebido);
        $this->assertSame(
            [
                ['autor' => 'Sujeito', 'conteudo' => 'Primeira mensagem.'],
                ['autor' => 'Sistema', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.'],
                ['autor' => 'Sujeito', 'conteudo' => 'Segunda mensagem.'],
                ['autor' => 'Sistema', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.'],
            ],
            $espiao->contextoRecebido->turnosRecentes
        );
    }

    private function criarServico(GeradorDePerguntaSocraticaInterface $gerador): RespostaSocraticaService
    {
        return new RespostaSocraticaService(
            $this->sujeitoRepository,
            $this->sessaoRepository,
            new GerarPerguntaSocraticaHandler($gerador),
            new RespostaEcoRecorrenciaService($this->sujeitoRepository)
        );
    }

    private function geradorFixo(?string $pergunta): GeradorDePerguntaSocraticaInterface
    {
        return new class($pergunta) implements GeradorDePerguntaSocraticaInterface {
            public function __construct(private readonly ?string $pergunta)
            {
            }

            public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
            {
                return $this->pergunta;
            }
        };
    }

    private function geradorQueConta(): object
    {
        return new class {
            public int $chamadas = 0;
            public GeradorDePerguntaSocraticaInterface $fake;

            public function __construct()
            {
                $contador = $this;
                $this->fake = new class($contador) implements GeradorDePerguntaSocraticaInterface {
                    public function __construct(private readonly object $contador)
                    {
                    }

                    public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
                    {
                        $this->contador->chamadas++;

                        return 'pergunta qualquer?';
                    }
                };
            }
        };
    }

    private function geradorEspiao(): object
    {
        return new class implements GeradorDePerguntaSocraticaInterface {
            public ?ContextoConversaDTO $contextoRecebido = null;
            public GeradorDePerguntaSocraticaInterface $fake;

            public function __construct()
            {
                $this->fake = $this;
            }

            public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
            {
                $this->contextoRecebido = $contexto;

                return 'pergunta qualquer?';
            }
        };
    }
}
