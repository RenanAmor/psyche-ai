# Roadmap — Psyche AI

> Versão 1.5 — Sprint 30: Consolidação Científica v1.0 (Base Científica certificada; Fase 1 encerrada, Fase 2 iniciada); Decisões de Arquitetura Permanente pós-Sprint 30: Modos de Operação da Plataforma (ajustada para dois modos, Modo 2 nomeado Laboratório com política de acesso registrada) e Ética da Psicanálise (fundamentação da ECO); Sprint 32: Interface Definitiva da ECO (Modo Conversacional por Voz)

## Sprint 0 — Fundação oficial do projeto (concluída)

- [x] Certificação do ambiente de desenvolvimento.
- [x] Estrutura física do repositório.
- [x] README institucional.
- [x] Documento Mestre (visão, missão, objetivos, escopo, princípios éticos, arquitetura conceitual).
- [x] `composer.json` base (PHP 8.2, sem dependências).
- [x] Publicação e sincronização do repositório remoto.

## Sprint 1 — Modelo Computacional do Discurso (concluída)

- [x] `docs/Modelo-Computacional-Discurso.md`: objetivo do modelo, objeto computacional do sistema (discurso registrado), unidade fundamental (Evento Discursivo), estrutura do discurso, temporalidade, estruturas discursivas, representação conceitual de hipóteses e limites.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

## Sprint 2 — Ontologia Freud (concluída)

- [x] `docs/Ontologia-Freud.md`: objetivo da ontologia, escopo teórico, dez conceitos fundamentais (Inconsciente, Recalque, Pulsão, Desejo, Formação de compromisso, Ato falho, Chiste, Sonhos, Repetição, Transferência), relações conceituais, limites e estrutura de referências bibliográficas.
- [x] Referências cruzadas adicionadas em `Documento-Mestre.md`.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

## Sprint 3 — Ontologia Lacan (atual)

- [x] `docs/Ontologia-Lacan.md`: objetivo da ontologia (complementa, reorganiza e amplia a Ontologia Freud, sem substituí-la), escopo teórico, onze conceitos fundamentais (Significante, Cadeia significante, Metáfora, Metonímia, Registro Simbólico, Registro Imaginário, Registro Real, Outro, Objeto a, Falta, Desejo lacaniano), relações conceituais e articulação explícita com cada conceito correspondente da Ontologia Freud, limites — incluindo a reafirmação explícita de que o significante não é uma palavra, não pode ser identificado automaticamente pelo sistema, e que apenas o sujeito, no processo analítico, confirma seu estatuto — e estrutura de referências bibliográficas.
- [x] Referências cruzadas adicionadas em `Documento-Mestre.md`.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente ontológico — sem algoritmos, regras de negócio, banco de dados, classes, código, APIs ou especificação do Lacan Engine / Discourse Engine.

## Sprint 7 — Ports & Infrastructure Contracts (concluída)

- [x] `app/Infrastructure/Contracts/`: dez portas (interfaces) que isolam o
      núcleo do sistema de qualquer tecnologia externa — `ClockInterface`,
      `LoggerInterface`, `PersistenceInterface`, `StorageInterface`,
      `UuidGeneratorInterface`, `TransactionInterface`,
      `EventDispatcherInterface`, `MessageBusInterface`,
      `TranscriptionInterface` e `LLMInterface`.
- [x] `app/Infrastructure/Contracts/DTOs/`: DTOs de suporte às portas de IA
      (`LLMRequestDTO`, `LLMResponseDTO`, `TranscriptionResultDTO`).
- [x] Pastas reservadas para futuras implementações concretas:
      `Persistence/`, `Logging/`, `Messaging/`, `Storage/`, `AI/`, `Clock/`,
      `UUID/` — sem nenhuma classe concreta nesta sprint.
- [x] Testes unitários dos contratos em `tests/Unit/Infrastructure/`,
      validando que cada porta é implementável e se comporta conforme
      esperado através de dublês de teste.
- [x] Domínio e Aplicação permanecem inalterados e independentes de
      Infraestrutura.
- [x] Atualização de `docs/Estrutura-de-Pastas.md` e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente de contratos — sem SQLite, MySQL,
PostgreSQL, HTTP, API REST, controllers, front-end, WhatsApp, Telegram,
OpenAI, Claude, Gemini, transcrição real, persistência real, filas ou
cache.

## Sprint 8 — Persistência Local (concluída)

- [x] `app/Infrastructure/Persistence/SQLite/Connection.php`: conexão PDO
      com criação automática do diretório e do arquivo de banco quando
      inexistentes, e `PRAGMA foreign_keys` habilitado.
- [x] `app/Infrastructure/Persistence/SQLite/TransactionManager.php`:
      implementação de `TransactionInterface` sobre transações nativas do
      PDO.
- [x] `app/Infrastructure/Persistence/SQLite/Migrations/`: seis migrations
      (uma por tabela) e um `MigrationRunner` idempotente, com histórico em
      `schema_migrations`.
- [x] `app/Infrastructure/Persistence/SQLite/Repositories/`: adaptadores
      concretos `SQLiteSujeitoRepository`, `SQLiteSessaoRepository`,
      `SQLiteDiscursoRepository` e `SQLiteMemoriaRepository`, implementando
      os Repositórios do Domínio via PDO puro, com mapeadores internos
      (`SessaoMapper`, `DiscursoMapper`, `EventoDiscursivoMapper`)
      cascateando o agregado Sujeito → Sessão → Discurso → Evento
      Discursivo.
- [x] Correção de defeito pré-existente em
      `Domain/Contracts/RepositoryInterface.php`: os métodos genéricos
      `save`/`remove`/`findById(object)` violavam a variância de
      parâmetros do PHP ao serem restringidos para tipos concretos nas
      interfaces filhas (`SujeitoRepository`, `SessaoRepository`,
      `DiscursoRepository`, `MemoriaRepository`), causando um erro fatal ao
      carregar qualquer implementação. A interface tornou-se um marcador
      vazio; cada Repositório mantém suas próprias assinaturas tipadas.
- [x] Testes de integração em `tests/Integration/Persistence/SQLite/`
      cobrindo criação do banco, execução e idempotência das migrations,
      transações (commit/rollback), e inserção/atualização/consulta/remoção
      — incluindo cascatas — para os quatro repositórios.
- [x] Suíte completa executada sem regressões (73 testes, 147 asserções).
- [x] Atualização de `docs/Estrutura-de-Pastas.md` e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente persistência local — sem API REST,
controllers, front-end, chat, WhatsApp, Telegram, IA, OpenAI, Claude,
Gemini, uploads, áudio ou transcrição.

## Sprint 9 — Integração Application → Persistence (concluída)

- [x] `app/Domain/Repositories/`: `findAll(): array` adicionado às quatro
      interfaces (`SujeitoRepository`, `SessaoRepository`,
      `DiscursoRepository`, `MemoriaRepository`) para suportar a operação
      "listar", exigida pelos critérios de aceite, sem violar o contrato
      de Domínio.
- [x] `app/Infrastructure/Persistence/SQLite/Repositories/`: os quatro
      adaptadores concretos e os mapeadores internos (`SessaoMapper`,
      `DiscursoMapper`) passam a implementar `findAll()`.
- [x] `app/Application/Services/`: quatro novas Application Services —
      `SujeitoApplicationService`, `SessaoApplicationService`,
      `DiscursoApplicationService` e `MemoriaApplicationService` — cada
      uma recebendo por injeção de dependência apenas Repositórios do
      Domínio (nunca SQLite), expondo `criar`, `atualizar`, `excluir`,
      `buscarPorId` e `listar`. Reaproveitam os Use Cases da Sprint 6 para
      construir/validar as Entidades e delegam a persistência ao
      Repositório injetado.
- [x] Decisão arquitetural: `EventoDiscursivo` não é raiz de agregado
      (não possui Repositório de Domínio próprio desde a Sprint 8 — é
      persistido apenas em cascata através de `Discurso`). Seu registro é
      exposto como `DiscursoApplicationService::adicionarEvento()`, e não
      como um serviço/repositório à parte, preservando o limite do
      agregado em vez de seguir literalmente a menção a um
      "EventoDiscursoRepository" no briefing da sprint.
- [x] `app/Application/Exceptions/RecursoNaoEncontradoException.php`:
      lançada por `atualizar`/`excluir`/operações dependentes quando o id
      informado não é encontrado pelo Repositório.
- [x] `app/Infrastructure/Providers/ApplicationServiceProvider.php`: raiz
      de composição da aplicação — monta a conexão SQLite, executa as
      migrations pendentes e injeta os Repositórios concretos nas quatro
      Application Services. Único ponto do sistema que conhece
      Application e Infrastructure simultaneamente.
- [x] Testes de integração em `tests/Integration/Application/` cobrindo,
      para cada Application Service, criação, atualização, exclusão,
      recuperação por id e listagem, além de um teste de ponta a ponta
      (`ApplicationServiceProviderTest`) que percorre o grafo completo
      Sujeito → Sessão → Discurso → Evento Discursivo → Memória
      Longitudinal inteiramente através das Application Services e
      confirma a cascata de remoção.
- [x] Suíte completa executada sem regressões (101 testes, 203 asserções;
      eram 73 testes e 147 asserções ao final da Sprint 8).
- [x] Atualização de `docs/Estrutura-de-Pastas.md` e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente a integração Application →
Persistence — sem API REST, controllers, front-end, chat, WhatsApp,
Telegram, IA, OpenAI, Claude, Gemini, uploads, áudio, transcrição ou
gerenciamento explícito de transações na camada de Aplicação (cada
`save()` de agregado já é uma unidade cascateada única, conforme
desenhado na Sprint 8).

## Sprint 11A — Estrutura da Interface Web (concluída)

- [x] `app/Presentation/Web/`: interface web (HTML) construída de forma
      inteiramente independente da conclusão da API REST (Sprint 10),
      isolada em namespace próprio (`PsycheAI\Presentation\Web`) para
      não colidir com os Controllers/Requests/Responses/Http da API
      REST, que já ocupam a raiz de `Presentation/`.
- [x] `Web/Http/`: `Request`, `Response`, `Router` (com handler de "não
      encontrado" configurável) e `ViewRenderer`, que renderiza uma
      view PHP isolada e a encaixa no layout principal (header +
      sidebar + área de conteúdo).
- [x] `Web/Client/`: `HttpClientInterface` — a estrutura de comunicação
      com a futura API REST — e `MockApiHttpClient`, sua única
      implementação nesta Sprint, devolvendo dados fixos simulados para
      os cinco recursos e permitindo forçar qualquer um dos quatro
      tipos de erro para fins de teste e demonstração. Nenhuma chamada
      real de rede é realizada.
- [x] `Web/ViewModels/`: `SujeitoViewModel`, `SessaoViewModel`,
      `DiscursoViewModel`, `MemoriaViewModel`, `EventoDiscursivoViewModel`
      e `DashboardViewModel` — projeções somente-leitura construídas a
      partir do array devolvido pelo Cliente HTTP, nunca de uma
      Entidade de Domínio.
- [x] `Web/Navigation/`: `NavigationItem` e `NavigationMenu`, fonte
      única das seis seções do menu lateral (Dashboard, Sujeitos,
      Sessões, Discursos, Memórias, Eventos Discursivos).
- [x] `Web/Components/`: `TableComponent` (com estado vazio automático),
      `CardComponent`, `ButtonComponent`, `FormComponent`,
      `ModalComponent`, `AlertComponent`, `LoadingIndicatorComponent` e
      `EmptyStateComponent`, todos escapando HTML via `Html`.
- [x] `Web/Errors/`: `ErrorType` (comunicação, não encontrado,
      validação, interno), `ErrorViewModel` e `ErrorViewModelFactory` —
      os quatro estados de erro exigidos, simulados nesta Sprint.
- [x] `Web/Controllers/`: `DashboardController`,
      `AbstractResourceController` (ceremonial comum às cinco páginas
      de listagem) e suas especializações — `SujeitosController`,
      `SessoesController`, `DiscursosController`, `MemoriasController`,
      `EventosDiscursivosController` — além de `ErrorController`.
      `SujeitosController::novo/store` demonstra o fluxo de validação
      básica de entrada (campo obrigatório), permitida à Apresentação
      pela Arquitetura em Camadas.
- [x] `Web/Views/`: `layout.php`, `partials/header.php`,
      `partials/sidebar.php`, uma view por seção, `carregando.php` e
      `errors/error.php`.
- [x] `Web/Routes.php` e `public/web/index.php`: todas as rotas
      internas registradas e navegáveis fim a fim, cada uma funcionando
      inteiramente com dados simulados.
- [x] Testes em `tests/Unit/Presentation/Web/` e
      `tests/Feature/Presentation/Web/` cobrindo renderização,
      navegação, componentes, estado de carregamento, estados vazios e
      as quatro mensagens de erro.
- [x] Suíte completa executada sem regressões (246 testes, 589
      asserções; eram 101 testes e 203 asserções ao final da Sprint 9).
- [x] Atualização de `docs/Estrutura-de-Pastas.md`,
      `docs/Arquitetura-Camadas.md` e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente a estrutura da interface web —
sem consumir a API REST, acessar SQLite, Application Services ou
Domain, e sem autenticação ou autorização. Toda comunicação permanece
mockada através de `MockApiHttpClient`.

## Sprint 11B — Integração da Interface Web com a API REST (concluída)

- [x] `Web/Client/ApiHttpClient`: substitui `MockApiHttpClient` (removida
      por completo, junto de seu teste unitário) como única implementação
      de produção de `HttpClientInterface` — fala HTTP de verdade (cURL)
      com a API REST da Sprint 10, com `get`/`post`/`put`/`delete`.
      Traduz o envelope JSON e os status HTTP reais (200-299, 204, 400,
      404, 409, 422, 500, falha de conexão) para o mesmo catálogo fechado
      de `ErrorType` da Sprint 11A, preservando a mensagem vinda da API
      sempre que houver uma.
- [x] `Presentation\Controllers\SujeitoController` + `CriarSujeitoRequest`
      + `AtualizarSujeitoRequest` + `SujeitoResponse` + rotas `/subjects`:
      a Sprint 10 havia implementado `SujeitoApplicationService` por
      completo, mas nunca expôs um endpoint HTTP para ele — lacuna
      fechada nesta Sprint para que o módulo de Sujeitos tivesse CRUD
      real para integrar.
- [x] `eventos_discursivos.criado_em` (migration `0007`) +
      `EventoDiscursivo::criadoEm()` (parâmetro opcional, compatível com
      todos os pontos de construção já existentes) + `sessaoId` exposto
      via `DiscursoRepository::sessaoIdDoDiscurso()`: a tela de Eventos
      Discursivos passou a exibir identificador, sessão, discurso, ordem,
      conteúdo e data de criação, conforme exigido — os dois únicos
      campos que a Sprint 10 ainda não persistia/expunha.
- [x] `Web/Controllers/AbstractCrudResourceController`: "mostrar" e
      "excluir" (idênticos nos quatro módulos com CRUD completo) —
      `SujeitosController`, `SessoesController`, `DiscursosController`,
      `MemoriasController` — cada um com `novo/store/editar/atualizar`
      próprios, pois os campos de formulário divergem por recurso.
      `EventosDiscursivosController` permanece somente para consulta.
- [x] `Web/Views/*/mostrar.php` e `*/editar.php` para os quatro módulos
      de CRUD completo, formulário de Discursos com `<textarea>`
      (suporte a textos extensos), e coluna de ações (Ver/Editar) nas
      listagens via novo parâmetro `$colunasComHtml` de `TableComponent`.
- [x] Tratamento de erros consistente em todas as ações mutáveis: falha
      de validação da API reexibe o formulário com a mensagem real;
      falha de comunicação/não encontrado/interna substitui a página
      inteira pela tela de erro correspondente (`ErrorController`).
- [x] `MockApiHttpClient` removida por completo de `app/`; testes que a
      usavam passaram a usar `PsycheAI\Tests\Support\HttpClientStub`
      (duplo de teste equivalente, chaves de recurso iguais às rotas
      reais da API) ou a API real de ponta a ponta.
- [x] `tests/Integration/Web/RealApiTestCase` + `FakeApiServerTestCase`:
      sobem a API REST real (ou um roteador fixo mínimo, só para status
      HTTP que a API saudável nunca produz) via `php -S` sobre SQLite
      temporário, para que `ApiHttpClientTest` e
      `SujeitosApiIntegrationTest` exercitem Interface ↔ API por HTTP de
      verdade — CRUD completo, 404/409/422 reais e falha de comunicação
      por indisponibilidade real.
- [x] Suíte completa executada sem regressões (294 testes, 716
      asserções; eram 246 testes e 589 asserções ao final da Sprint 11A).
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente a integração HTTP real — sem
autenticação, autorização, WebSocket, notificações em tempo real,
dashboards analíticos ou identidade visual definitiva.

## Sprint 12 — Primeiro Diálogo (concluída)

- [x] Tela de Conversa (`Web/Views/conversa/index.php` +
      `ConversaController`): histórico de mensagens, caixa de texto e
      botão Enviar, todos montados exclusivamente com os Componentes já
      existentes desde a Sprint 11A (`TableComponent`, `FormComponent`,
      `AlertComponent`). A atualização do histórico acontece porque a
      própria resposta ao POST de envio já é a página recarregada com
      as mensagens atualizadas — sem WebSocket nem polling, escopo
      explicitamente excluído desde a Sprint 11B — e um `<script>`
      inline mínimo rola o histórico até a última mensagem.
- [x] Sessão automática: `ConversaController` garante um Sujeito
      "Visitante" padrão sob demanda (`GET`/`POST /subjects`, já
      existentes) e cria uma nova Sessao (`POST /sessions`, já
      existente) na primeira visita, mantendo seu id em `$_SESSION`
      nativa do PHP (`session_start()` adicionado a
      `public/web/index.php`) para persistir entre requisições da mesma
      aba — não há autenticação, apenas continuidade de conversa.
- [x] `POST /sessions/{id}/messages` (`MensagemController` +
      `EnviarMensagemRequest` + `MensagemEnviadaResponse`, sobre
      `MensagemApplicationService`): único endpoint novo desta Sprint.
      Registra a mensagem do usuário e a resposta automática do sistema
      como dois `EventoDiscursivo` dentro do único `Discurso` da
      conversa (criado sob demanda no primeiro envio), reaproveitando
      `RegistrarDiscursoHandler`/`RegistrarEventoDiscursivoHandler` já
      existentes — nenhum campo novo no Domínio. Como nem `Discurso` nem
      `EventoDiscursivo` guardam quem falou, a paridade da `Posicao`
      (par = usuário, ímpar = sistema) é quem distingue o autor na
      Apresentação, já que a conversa sempre alterna estritamente entre
      os dois. A leitura do histórico reaproveita `GET /events` já
      existente, filtrado por `sessaoId` em
      `MensagemViewModel::historicoDaSessao()` — sem endpoint novo só
      para leitura.
- [x] `Infrastructure/Contracts/RespostaAutomaticaInterface.php` +
      `Infrastructure/AI/RespostaFixaService.php`: porta e implementação
      temporária da resposta automática ("Recebi sua mensagem. Continue
      falando livremente."), deliberadamente separada da já existente
      `LLMInterface` — que pressupõe negociação real com um provedor de
      LLM — para não descrever de forma enganosa uma resposta fixa como
      chamada de IA. Isolada para substituição futura pelos motores
      Freud e Lacan, que implementarão o mesmo contrato sem exigir
      mudanças em `MensagemApplicationService` nem em qualquer camada
      acima dela.
- [x] `Infrastructure/UUID/RandomUuidGenerator.php`: primeira
      implementação concreta de `UuidGeneratorInterface` (Sprint 7) —
      UUID v4 via `random_bytes`, sem dependências externas — usada para
      gerar os ids de Discurso/EventoDiscursivo/Sessao desta Sprint.
- [x] Tratamento de erros sem quebrar a interface: falha de
      conexão/timeout e erro HTTP reexibem a conversa com um alerta
      inline preservando o texto digitado; mensagem vazia é validada
      antes de qualquer chamada à API; Sessao inexistente (ex.: removida
      por outra aba via o CRUD de Sessões já existente) é recuperada
      automaticamente — `ConversaController` cria uma nova Sessao e
      reenvia a mensagem uma única vez, avisando o usuário.
- [x] `NavigationMenu`: nova seção "Conversa" (`/conversa`), primeira
      depois do Dashboard.
- [x] Testes: `MensagemApplicationServiceTest`
      (`tests/Integration/Application/`), `MensagemEndpointsTest`
      (`tests/Feature/Http/`, ponta a ponta Router → Application →
      SQLite), `ConversaControllerTest`
      (`tests/Feature/Presentation/Web/`, cobrindo criação/reuso de
      Sessao, envio, validação de conteúdo vazio, recuperação de Sessao
      inexistente e falhas de comunicação/validação sem quebrar),
      `RandomUuidGeneratorTest`, `RespostaFixaServiceTest`, além dos
      casos de `MensagemViewModel` em `ViewModelsTest`. Suíte completa
      executada sem regressões (319 testes, 787 asserções; eram 294
      testes e 716 asserções ao final da Sprint 11B).
- [x] Atualização de `docs/Estrutura-de-Pastas.md`,
      `docs/Arquitetura-Camadas.md` e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente o primeiro diálogo funcional — sem
Freud Engine, Lacan Engine, interpretação clínica, detecção de
recorrências, memória longitudinal aplicada à conversa, observador
clínico, IA generativa ou autenticação.

## Sprint 13 — Memória Discursiva Longitudinal (concluída)

- [x] `Application/Services/LinhaDoTempoApplicationService`: consulta
      somente-leitura da Linha do Tempo Discursiva de um Sujeito —
      Sessões, Discursos, Eventos Discursivos e Memórias Longitudinais já
      registrados, projetados em `LinhaDoTempoItemDTO` e ordenados
      cronologicamente. `Discurso` e `MemoriaLongitudinal` não têm
      timestamp próprio no Domínio (só `Sessao::data()` e
      `EventoDiscursivo::criadoEm()` têm) — cada Discurso é ancorado na
      data da Sessao que o contém, e cada Memória na data da última
      Sessao que consolida. Suporta filtro por tipo, período (`de`/`ate`),
      texto (`q`) e paginação — tudo resolvido em memória sobre o grafo
      já hidratado do Sujeito, sem nenhuma leitura sobre o conteúdo além
      de correspondência literal de substring.
- [x] `Application/Services/ConsolidacaoApplicationService`: consolida
      automaticamente a quantidade de Sessões, Discursos, Eventos
      Discursivos e Memórias de um Sujeito — contagem pura, sem comparar
      sessões nem identificar recorrências.
- [x] `Domain/Repositories/SessaoRepository::sujeitoIdDaSessao()` (+
      `SessaoMapper::sujeitoIdDe()`): mesmo padrão de
      `DiscursoRepository::sessaoIdDoDiscurso()` (Sprint 11B) — expõe o
      vínculo persistido Sessao → Sujeito, que a Entidade não carrega.
      Necessário porque `MemoriaLongitudinal` só guarda as sessões que
      consolida, não o id do Sujeito a quem pertence; localizar as
      Memórias de um Sujeito exige essa ponte.
- [x] `GET /subjects/{id}/timeline` e `GET /subjects/{id}/consolidation`
      (`LinhaDoTempoController` + `ConsultarLinhaDoTempoRequest` +
      `LinhaDoTempoResponse`/`ConsolidacaoResponse`): os dois únicos
      endpoints novos desta Sprint, ambos somente-leitura, satisfazendo a
      exigência de criar apenas o estritamente necessário para consulta
      longitudinal. `Presentation\Http\Request::queries()` e os
      auxiliares opcionais de `HttpRequestData` (`opcionalString`,
      `opcionalData`, `opcionalInteiroPositivo`) foram acrescentados para
      validar parâmetros de query string opcionais — distintos dos
      campos sempre obrigatórios do corpo de escrita.
- [x] Tela de Histórico do Sujeito (`Web/Controllers/HistoricoSujeitoController`
      + `Web/Views/historico/mostrar.php`, rota `GET /sujeitos/{id}/historico`,
      link "Ver Histórico" em `sujeitos/mostrar.php`): reúne Sujeito,
      Linha do Tempo (com formulário de filtro por tipo/período/texto e
      paginação anterior/próxima) e Consolidação em uma única página,
      compondo as três consultas somente-leitura já existentes da API.
      Não estende `AbstractResourceController`/`AbstractCrudResourceController`
      — nenhum dos dois ceremoniais genéricos cobre uma página que
      combina três respostas de uma vez.
- [x] `ErrorType::TIMEOUT` (+ `ErrorViewModelFactory::timeout()`,
      `ErrorViewModel::statusHttp()` → 504, rota de demonstração
      `/erros/timeout`): a Sprint 13 exige tratar falha de conexão e
      timeout como cenários distintos. `ApiHttpClient` já usava
      `CURLOPT_TIMEOUT`, mas `curl_errno() === CURLE_OPERATION_TIMEDOUT`
      também é disparado quando a conexão nunca chega a ser aberta dentro
      do prazo — por isso a distinção real é feita por
      `CURLINFO_CONNECT_TIME`: maior que zero significa que o servidor
      aceitou a conexão e só demorou a responder (timeout de verdade);
      igual a zero significa que a conexão em si falhou (mapeado para
      `ErrorType::COMUNICACAO`, como já acontecia).
- [x] Testes: `SQLiteSessaoRepositoryTest` (novos casos de
      `sujeitoIdDaSessao`), `LinhaDoTempoApplicationServiceTest` e
      `ConsolidacaoApplicationServiceTest`
      (`tests/Integration/Application/`, incluindo isolamento entre
      Sujeitos diferentes), `LinhaDoTempoEndpointsTest`
      (`tests/Feature/Http/`, ponta a ponta Router → Application →
      SQLite, cobrindo filtros, paginação e erros HTTP 400/404),
      `HistoricoSujeitoControllerTest` (`tests/Feature/Presentation/Web/`,
      com o novo duplo `Tests\Support\HistoricoHttpClientFake`, cobrindo
      sucesso, repasse de filtros e as quatro falhas —
      comunicação/não encontrado/timeout/interno), casos novos em
      `ViewModelsTest`, `ErrorViewModelFactoryTest`, `ErrorControllerTest`
      e `RoutesTest`, e um teste de integração real
      (`ApiHttpClientTest::testRespostaLentaEMapeadaParaTimeout`, via
      nova rota `/lento` em `fixtures/fake-status-server.php`) que
      distingue timeout de falha de conexão sem nenhum mock. Suíte
      completa executada sem regressões (358 testes, 896 asserções; eram
      319 testes e 787 asserções ao final da Sprint 12).
- [x] Atualização de `docs/Estrutura-de-Pastas.md`,
      `docs/Arquitetura-Camadas.md`, `docs/Casos-de-Uso.md` (UC-007 —
      Consultar Histórico, marcado como implementado) e do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente registro e organização
cronológica do que já foi produzido — sem Freud Engine, Lacan Engine,
interpretação clínica, detecção de recorrências, identificação de
significantes ou cadeia significante, associação livre automática ou
IA generativa. `DetectarRecorrencias`/`GerarObservacoes` (já existentes
desde antes da Sprint 7) não foram tocados nem estendidos por esta
Sprint.

## Sprint 14 — Discourse Engine: exposição sem persistência (concluída)

- [x] Decisão arquitetural: `Recorrencia`/`Observacao` (já existentes desde
      antes da Sprint 7, produzidas por `DetectorRecorrencias` +
      `GeradorObservacoes` + `RecorrenciaMinimaSpecification`, encadeadas
      por `CicloDeObservacaoService`) não ganham migration nem repositório.
      O mesmo padrão da Sprint 13 (`LinhaDoTempoApplicationService`/
      `ConsolidacaoApplicationService`, que nunca gravam o resultado
      derivado no banco) é reaproveitado: recalcular a cada consulta evita
      que uma Recorrência gravada seja lida como constatação/diagnóstico
      armazenado — na contramão das Regras 7-10 de
      [Regras-Dominio.md](Regras-Dominio.md) — e evita o problema de
      invalidação caso a sessão de origem seja editada depois.
- [x] `Application/DTOs/ObservacaoResultadoDTO` +
      `Application/Services/ObservacaoApplicationService`: carrega o
      Sujeito (lança `RecursoNaoEncontradoException` se não existir, como
      nas Sprints 9/13), monta uma `MemoriaLongitudinal` transitória — usa
      o próprio id do Sujeito como identificador, sem risco de colisão
      pois nunca é persistida — e delega a `CicloDeObservacaoService::executar()`,
      já existente e inalterado.
- [x] `GET /subjects/{id}/observations` (`ObservacaoController` +
      `ConsultarObservacoesRequest` + `ObservacaoResponse`, parâmetro de
      query opcional `minimoDeRecorrencia`): único endpoint novo desta
      Sprint, somente-leitura, seguindo o padrão de
      `GET /subjects/{id}/timeline`.
- [x] Tela própria `Web/Controllers/ObservacoesSujeitoController` +
      `Web/Views/observacoes/mostrar.php` (rota
      `GET /sujeitos/{id}/observacoes`, link "Ver Observações" a partir de
      `historico/mostrar.php`): lista as Recorrências e Observações
      recalculadas. Tela separada da de Histórico (em vez de embutida
      nela) porque a Sprint 16 vai estendê-la com os rótulos do Motor
      Lacan lado a lado — antecipar essa separação evita reabrir
      `HistoricoSujeitoController` na Sprint 16.
- [x] Testes: `ObservacaoApplicationServiceTest`
      (`tests/Integration/Application/`, incluindo isolamento entre
      Sujeitos e o parâmetro `minimoDeRecorrencia`), `ObservacaoEndpointsTest`
      (`tests/Feature/Http/`, ponta a ponta Router → Application → SQLite),
      `ObservacoesSujeitoControllerTest` (`tests/Feature/Presentation/Web/`,
      com o novo duplo `Tests\Support\ObservacoesHttpClientFake`). Suíte
      completa executada sem regressões (370 testes, 932 asserções; eram
      358 testes e 896 asserções ao final da Sprint 13).
- [x] Atualização de `docs/Estrutura-de-Pastas.md`,
      `docs/Arquitetura-Camadas.md` e do Roadmap. Correção de referências
      desatualizadas: a âncora `#4-visão-arquitetural-de-longo-prazo`,
      citada por ambas as Ontologias, não existia em `Arquitetura.md`
      (nunca atualizado desde a Sprint 4); e `Documento-Mestre.md` §7
      ainda continha uma instrução editorial não aplicada mandando remover
      a menção ao Discourse Engine — ambos corrigidos para refletir que o
      Discourse Engine existe de fato desde esta Sprint.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente exposição do que já existia em
memória — sem nova entidade de Domínio, sem persistência de
Recorrencia/Observacao, sem mudança de comportamento do detector (isso é
Sprint 15), sem rótulo lacaniano (Sprint 16) e sem nenhuma hipótese,
interpretação ou diagnóstico automático.

## Sprint 15 — Motor Freud (concluída)

- [x] `Domain/Services/DetectorRecorrencias::detectar()`: a comparação de
      conteúdo de `EventoDiscursivo`, antes igualdade exata de string,
      passou a normalizar espaços nas bordas e caixa (`trim` +
      `mb_strtolower`) antes de agrupar — decisão tomada com o usuário
      antes da implementação. "Lapso", " Lapso " e "LAPSO" passam a contar
      como a mesma recorrência. Normalização puramente textual e
      determinística — sem similaridade semântica, sem NLP, sem
      interpretação de sentido.
- [x] `Domain/Specifications/RecorrenciaMinimaSpecification`: avaliado e
      mantido sem alteração. O limiar mínimo (2) não é um filtro de
      "importância" a remover — é a própria definição de "repetição" (uma
      única ocorrência não é uma recorrência). Conferido que nenhum ponto
      do fluxo (`GeradorObservacoes`, `ObservacaoResultadoDTO`,
      `ObservacaoResponse`, `Web/ViewModels/ObservacaoViewModel`) ordena ou
      prioriza recorrências por frequência — a atenção flutuante (escutar
      tudo sem hierarquizar importância) já era respeitada antes desta
      Sprint, nada precisou ser removido.
- [x] Nenhuma entidade nova, nenhum endpoint novo: o Motor Freud é uma
      configuração/refinamento do `DetectorRecorrencias` já existente
      desde antes da Sprint 7, reexposto pelos endpoints/tela da Sprint 14
      (`GET /subjects/{id}/observations`, `sujeitos/{id}/observacoes`) sem
      nenhuma mudança de contrato.
- [x] Testes: `DetectorRecorrenciasTest::testNormalizaEspacosEMaiusculasAoComparar`
      e `DetectarRecorrenciasHandlerTest::testNormalizaVariacoesDeGrafiaComoAMesmaRecorrencia`
      cobrem a nova tolerância de grafia; os demais testes existentes
      (`RecorrenciaMinimaSpecificationTest`, `CicloDeObservacaoServiceTest`,
      `ObservacaoApplicationServiceTest`, `ObservacaoEndpointsTest`)
      continuam passando sem alteração, pois usavam apenas conteúdo já
      normalizado ("lapso", "chiste"). Suíte completa executada sem
      regressões (372 testes, 936 asserções; eram 370 testes e 932
      asserções ao final da Sprint 14).
- [x] Atualização do Roadmap.

Escopo desta sprint é exclusivamente o refinamento do detector de
recorrências existente — sem hipótese, interpretação, diagnóstico,
identificação de significante, NLP, similaridade semântica ou IA
generativa. `docs/Ontologia-Freud.md` não foi alterado (permanece
somente vocabulário conceitual).

## Sprint 16 — Motor Lacan (concluída)

- [x] `Domain/Services/ReclassificadorLacaniano`: novo Domain Service que
      recebe as mesmas `Recorrencia[]` já produzidas pelo Motor Freud
      (Sprint 15) e devolve um rótulo lacaniano por id de Recorrencia —
      nenhum dado novo é criado, apenas uma reclassificação. Como o
      detector só reconhece repetição de conteúdo (normalizado desde a
      Sprint 15), sem nenhuma substituição entre conteúdos distintos, a
      releitura estrutural correspondente da tabela de
      [Ontologia-Lacan.md (4)](Ontologia-Lacan.md#4-relações-conceituais)
      é sempre a de deslocamento/metonímia — nunca condensação/metáfora,
      que pressuporia dois significantes distintos em substituição, algo
      que este detector não captura. O rótulo devolvido
      ("Estrutura candidata: deslize metonímico.") ecoa literalmente o
      vocabulário de "estrutura candidata" de
      [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites): nunca afirma
      o estatuto de significante confirmado — só o sujeito, no processo
      analítico, confirma esse estatuto.
- [x] `Application/DTOs/RecorrenciaDTO` + `ObservacaoApplicationService::consultar()`:
      novo parâmetro opcional `$comLeituraLacaniana` (padrão `false`,
      preservando o contrato da Sprint 14/15) — quando `true`, aplica
      `ReclassificadorLacaniano` sobre as Recorrencias do Motor Freud e
      preenche `rotuloLacaniano` em cada `RecorrenciaDTO`; quando ausente,
      o campo permanece `null`.
- [x] `GET /subjects/{id}/observations?vocabulario=lacan` (mesmo endpoint
      da Sprint 14, sem endpoint novo): `ConsultarObservacoesRequest` passa
      a aceitar o parâmetro de query opcional `vocabulario`, repassado por
      `ObservacaoController` ao Application Service. `ObservacaoResponse`
      inclui `rotuloLacaniano` (null por padrão) ao lado de cada
      recorrência.
- [x] Tela combinada (absorve o "Observador Clínico" — não há sprint
      própria para isso): `Web/Controllers/ObservacoesSujeitoController`
      passa a consultar sempre com `vocabulario=lacan`, e
      `Web/Views/observacoes/mostrar.php` ganha a coluna "Leitura
      Lacaniana" lado a lado com Descrição/Frequência do Motor Freud —
      uma única tela, sem endpoint nem tela nova.
- [x] Testes: `ReclassificadorLacanianoTest` (`tests/Unit/`, cobrindo o
      rótulo uniforme, a lista vazia e que os dados originais da
      Recorrencia não são alterados), novos casos em
      `ObservacaoApplicationServiceTest` (com/sem leitura lacaniana),
      `ObservacaoEndpointsTest` (com/sem `?vocabulario=lacan`) e
      `ObservacoesSujeitoControllerTest` (rótulo lacaniano renderizado na
      tela). Suíte completa executada sem regressões (379 testes, 952
      asserções; eram 372 testes e 936 asserções ao final da Sprint 15).
- [x] Atualização do Roadmap.

Escopo desta sprint é exclusivamente reclassificação de vocabulário sobre
dados já produzidos pelo Motor Freud — sem significante, sem leitura de
sentido, sem hipótese, sem diagnóstico, sem endpoint ou tela nova além da
extensão já prevista desde a Sprint 14.

## Sprint 17 — Interface Conversacional (concluída)

Referência de produto confirmada com o usuário: "algo como o ChatGPT" só
na experiência (streaming/atualização incremental, visual fluido) — não
na inteligência por trás. Isso não reabre a decisão de "sem LLM" que
vale desde a Sprint 1: `LLMInterface` continua sem nenhuma
implementação. As duas decisões que o planejamento desta Sprint deixou
em aberto foram fechadas com o usuário antes de implementar:

- [x] **Identidade por cookie, sem autenticação real.** Em vez de
      resolver identidade de usuário (reservado para a Sprint 18),
      `ConversaController` passa a gerar um cookie de longa duração
      (`psyche_pessoa_id`, 1 ano, `HttpOnly`, independente de
      `$_SESSION`) na primeira vez que uma nova Sessao precisa ser
      criada, e reaproveitá-lo nas visitas seguintes — inclusive depois
      que `$_SESSION` expira. Isso substitui o Sujeito "visitante" fixo
      compartilhado por todo mundo por uma identidade pseudônima
      estável por navegador: cada pessoa acumula seu próprio histórico
      ao longo de múltiplas Sessões (visitas), isolado dos demais,
      necessário para que os motores Freud/Lacan (Sprints 15-16)
      observem recorrências de UM sujeito. Testado manualmente de ponta
      a ponta (dois servidores PHP embutidos + curl): o mesmo cookie é
      reaproveitado entre visitas diferentes, criando uma nova Sessao
      sob o mesmo Sujeito em vez de um Sujeito novo.
- [x] **`RespostaAutomaticaInterface` passa a refletir repetições
      (sem interpretar).** Novo binding padrão
      `Infrastructure/AI/RespostaEcoRecorrenciaService`: reaproveita
      `ConstruirMemoriaLongitudinalHandler` + `DetectarRecorrenciasHandler`
      (os mesmos Use Cases do Discourse Engine/Motor Freud, Sprints
      13-15) para checar se o conteúdo normalizado da mensagem que a
      pessoa acabou de enviar já apareceu antes no histórico persistido
      do Sujeito. Quando sim, a resposta automática é uma pergunta-eco
      que só nomeia a repetição e convida a continuar falando (ex.:
      `Você voltou a falar em "X". O que vem à mente sobre isso?`) —
      nunca uma afirmação ou hipótese sobre a causa da repetição (Regra
      7, [Regras-Dominio.md](Regras-Dominio.md)). Quando não há
      repetição (ou o Sujeito não é encontrado), delega para
      `RespostaFixaService`, que não foi removida — só deixou de ser o
      binding padrão. `RespostaAutomaticaInterface::responder()` ganha
      um segundo parâmetro `$sujeitoId` (valor padrão `''`, para não
      quebrar implementações que não precisam dele), que
      `MensagemApplicationService` preenche via o já existente
      `SessaoRepository::sujeitoIdDaSessao()` (Sprint 13).
      `Domain/Services/DetectorRecorrencias::normalizar()` passou a ser
      público para que essa comparação use exatamente a mesma regra do
      Motor Freud, sem duplicá-la.
- [x] **Atualização incremental via fetch(), sem WebSocket/SSE.** A
      resposta do sistema é sempre determinística e síncrona — não há
      nada gerado token a token para transmitir em chunks, então
      "streaming" aqui significa trocar HTML pronto por HTML pronto:
      novo endpoint `POST /conversa/mensagens`
      (`ConversaController::mensagens()`) devolve, em JSON, o mesmo
      fragmento HTML que o novo `Presentation/Web/Components/ConversaAreaComponent`
      já monta para a página cheia (extraído para não duplicar a
      montagem de alerta+histórico em dois lugares); o `<script>` de
      `conversa/index.php` (vanilla, sem bundler/framework) intercepta o
      submit do formulário e troca o `innerHTML` de `#conversa-area` por
      esse fragmento, sem recarregar a página. `POST /conversa/enviar`
      (rota clássica da Sprint 12) continua existindo e funcional — é o
      caminho de reserva quando `fetch()` falha ou JavaScript está
      desabilitado, então a Sprint não introduz nenhum caminho que
      dependa de JavaScript para funcionar. Nova variante
      `Presentation/Web/Http/Response::json()` ao lado da `Response` HTML
      existente.
- [x] Testes: `RespostaEcoRecorrenciaServiceTest` (integração, histórico
      persistido de verdade via SQLite), novos casos em
      `MensagemApplicationServiceTest` (repasse de `$sujeitoId`) e em
      `DetectorRecorrenciasTest` (`normalizar()` público), e novos casos
      em `ConversaControllerTest` (cookie de pessoa gerado/reaproveitado,
      isolamento entre navegadores, endpoint JSON nos três cenários já
      cobertos por `enviar()`: sucesso, conteúdo vazio, sessão
      inexistente). Suíte completa executada sem regressões (393 testes,
      980 asserções; eram 379 testes e 952 asserções ao final da Sprint
      16).
- [x] Atualização do Roadmap, `Estrutura-de-Pastas.md` e
      `Arquitetura-Camadas.md` (nova seção "Interface Conversacional
      (Sprint 17)").

Escopo desta sprint é exclusivamente UX da conversa (atualização
incremental, identidade por navegador) e refletir repetições já
detectadas pelo Motor Freud — sem significante, sem leitura lacaniana na
conversa, sem hipótese, sem diagnóstico, sem autenticação real.

## Revisão das Sprints 15-16 (2026-07-30 — pós-implementação)

As Sprints 14-16 foram implementadas e commitadas exatamente como
planejado (379 testes/952 asserções ao final da Sprint 16). Uma conversa
posterior com o usuário aprofundou o que "mapear a pulsão, todo o
caminho" e "Lacan é a linguagem" significam de verdade, motivando esta
revisão aditiva — nenhum contrato das Sprints 14-17 muda; tudo abaixo é
extensão.

Três decisões do usuário fecharam o escopo: (1) dois públicos, duas
regras — o Sujeito que fala em `/conversa` nunca vê os motores (já
garantido desde a Sprint 14); o analista/administrador é para quem os
motores trabalham de verdade, e "toda interpretação pertence ao
analista" (Regra 10) não exige que o sistema seja conservador com o
próprio analista; (2) "mapear a pulsão, todo o caminho" é o
circuito/trajeto de uma recorrência ao longo do tempo (quando/onde ela
reaparece através das Sessões), não só a contagem pontual já existente;
(3) "Lacan é a linguagem" pede a transcrição do aparato formal/notação
lacaniano como gramática sobre o material do Freud — nunca uma
interpretação de sentido.

**Achado, adiado explicitamente**: os quatro discursos (Seminário 17)
não têm base hoje — nem ontológica ([Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)
documenta onze conceitos, não os quatro discursos) nem estrutural
(`EventoDiscursivo` não modela interlocutor, papel de enunciação ou laço
social). Mapeá-los exigiria uma sprint própria de ontologia antes de
qualquer código — ver "Sprints futuras" abaixo.

- [x] **Peça A — Papel do Analista.** Sem entidade nova de Domínio
      (`Sujeito` continua significando "quem é observado"; um
      `Analista` com conta duplicaria a Sprint 18, que será totalmente
      greenfield em auth) — a separação é só na Apresentação Web, e
      descartável sem dívida quando a Sprint 18 chegar:
      `Presentation/Web/Security/PortaoDeAnalista.php` (novo:
      `estaAutenticado()`, `autenticar(string $senha)` — compara com
      `getenv('PSYCHEAI_SENHA_ANALISTA')` via `hash_equals()` —, `sair()`,
      `proteger(callable $handler): Closure`);
      `Presentation/Web/Http/Response::redirecionar()` (302, novo);
      `Presentation/Web/Controllers/AutenticacaoAnalistaController` (novo,
      rotas `GET/POST /entrar` e `POST /sair`) + view
      `autenticacao/entrar.php`, reaproveitando `FormComponent`/
      `AlertComponent`. Em `Web/Routes.php`, todo handler de
      coleta/análise (`/`, `/sujeitos*`, `/sessoes*`, `/discursos*`,
      `/memorias*`, `/eventos-discursivos`) passa a ser envolvido por
      `PortaoDeAnalista::proteger()` no momento do registro —
      `/conversa*` (superfície do Sujeito), `/erros/*`, `/entrar` e
      `/sair` permanecem deliberadamente fora do Portão. A API REST
      (`Presentation/Routes.php`) não é protegida nesta passada — só é
      chamada servidor-a-servidor por `ApiHttpClient`, nunca direto pelo
      navegador na topologia atual; se a API algum dia for exposta
      direto, precisa de firewall próprio. Chave de sessão deliberadamente
      `psyche_analista_autenticado` (distinta de `psyche_pessoa_id`/
      `psyche_conversa_sessao_id`, do Sujeito), para a Sprint 18 poder
      apagar `PortaoDeAnalista`/`AutenticacaoAnalistaController` inteiros
      sem dívida ao construir contas reais.
- [x] **Peça B — Circuito/Trajeto Pulsional.** Extensão aditiva do Motor
      Freud, sem tocar a assinatura de `DetectorRecorrencias::detectar()`
      (usada por 9 arquivos já existentes, incluindo
      `RespostaEcoRecorrenciaService` da Sprint 17): novo VO
      `Domain/ValueObjects/OcorrenciaRecorrencia` (`sessaoId`,
      `discursoId`, `eventoId`, `momento`, `posicao`; `momento` é a data
      da Sessão, mesma ancoragem estrutural do tempo da Sprint 13, não o
      timestamp técnico do Evento); novo método
      `DetectorRecorrencias::detectarCircuito(MemoriaLongitudinal): array<string, OcorrenciaRecorrencia[]>`
      (mesma `normalizar()` de `detectar()`, para que "mesma recorrência"
      nunca divirja entre contagem e circuito); nova tríade
      `Application/UseCases/DetectarCircuitoRecorrencia/{Command,Handler,Result}`
      + DTOs `OcorrenciaCircuitoDTO`/`CircuitoRecorrenciaDTO`/
      `CircuitoResultadoDTO`; `ObservacaoApplicationService::consultarCircuito()`
      (novo método, mesmo Application Service) usa o resultado já
      filtrado (limiar ≥2) de `CicloDeObservacaoService::executar()` como
      única fonte de quais Recorrencias existem, cruzando com
      `detectarCircuito()`; endpoint
      `GET /subjects/{id}/observations/circuito`
      (`ObservacaoController::circuito()`, `Responses/CircuitoResponse`);
      tela estendida (não bifurcada) —
      `ObservacoesSujeitoController`/`observacoes/mostrar.php` ganham
      `Components/CircuitoTrajetoComponent`, que lista por Recorrencia o
      trajeto "Sessão {data} → Sessão {data} → …".
- [x] **Peça C — Motor Lacan: rótulo estrutural de circuito.**
      `ReclassificadorLacaniano::reclassificar()` fica congelado
      (assinatura e saída intocadas, protegendo todos os consumidores da
      Sprint 16). Método novo e aditivo,
      `reclassificarComTrajeto(array $recorrencias, array $circuitos): array`:
      quando as ocorrências de uma Recorrencia cobrem ≥2 `sessaoId`
      distintos, novo rótulo ("Estrutura candidata: circuito — o tema
      retorna ao mesmo ponto através de sessões distintas."); senão, o
      mesmo rótulo de sempre ("deslize metonímico"). Constatação
      estrutural contável (mesmo conteúdo normalizado em ≥2 registros de
      sessão), nunca leitura de sentido — mesmo tipo de afirmação que o
      rótulo da Sprint 16 já fazia; fundamentado em
      [Ontologia-Lacan.md §3.7/§4](Ontologia-Lacan.md#37-registro-real)
      (Repetição → Real) e na compulsão à repetição freudiana. Wire só em
      `consultarCircuito(..., $comLeituraLacaniana)` →
      `CircuitoRecorrenciaDTO::$rotuloLacaniano`; o endpoint/resposta
      antigos (`GET /subjects/{id}/observations`) ficam inalterados.
- [x] Testes, tudo aditivo (nenhuma asserção existente mudou): novos
      casos em `DetectorRecorrenciasTest` (`detectarCircuito`),
      `ReclassificadorLacanianoTest` (`reclassificarComTrajeto`), novo
      `DetectarCircuitoRecorrenciaHandlerTest`, casos novos em
      `ObservacaoApplicationServiceTest` e `ObservacaoEndpointsTest`
      (`consultarCircuito`/`GET .../observations/circuito`), casos novos
      em `ObservacoesSujeitoControllerTest` e `ViewModelsTest`, novo
      `CircuitoTrajetoComponentTest`, novo `PortaoDeAnalistaTest`,
      `AutenticacaoAnalistaControllerTest`, caso novo em `ResponseTest`
      (`redirecionar()`), e `RoutesTest` passa a autenticar a "sessão" de
      testes antes de iterar as rotas protegidas, com um caso novo
      confirmando que uma rota protegida sem sessão redireciona para
      `/entrar`. Suíte completa executada sem regressões (436 testes,
      1102 asserções; eram 393 testes e 980 asserções ao final da Sprint
      17).
- [x] Atualização do Roadmap, `Arquitetura-Camadas.md` e
      `Estrutura-de-Pastas.md`.

Escopo desta revisão é exclusivamente as três peças acima — sem os
quatro discursos (adiados, ver acima), sem significante, sem hipótese,
sem diagnóstico, sem autenticação real (o Portão é descartável, não uma
antecipação da Sprint 18).

## Revisão do Motor Freud — classificação estrutural via LLM (2026-07-30)

Uma conversa posterior com o usuário aprofundou o que "atenção
flutuante" exige na prática: para reconhecer, na forma de um conteúdo
discursivo, qual das espécies de formação de compromisso (ou a
repetição) de [Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais)
ele mais se assemelha, o Motor Freud precisa de conhecimento conceitual
— algo que a comparação literal de string de `DetectorRecorrencias`
nunca poderia fornecer. Isso **reverte uma decisão de escopo anterior**
("sem LLM/IA generativa nos motores Freud/Lacan", fechada durante o
planejamento das Sprints 14-16), reaberta explicitamente pelo usuário
ao constatar que a restrição original vinha de um entendimento
equivocado sobre o que o método socrático do sistema — provocar
associação livre sem nunca interpretar, ver
[Documento-Mestre.md §6.7](Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático)
— realmente exige, e não de uma limitação do domínio.

**Achado que simplificou o escopo:** o Motor Lacan não precisa de LLM
algum. `ReclassificadorLacaniano` sempre foi "não analisa dado novo,
só reclassifica com vocabulário lacaniano" — uma vez que o Motor Freud
produza um rótulo mais rico, o Motor Lacan só precisa de uma tabela de
lookup determinística sobre esse rótulo, já documentada em
[Ontologia-Lacan.md §4](Ontologia-Lacan.md#4-relações-conceituais).
Isso resolve o problema de o Motor Lacan estar preso a um único rótulo
fixo (`ROTULO`) sem estender a superfície de LLM a um segundo motor.

O guardrail contra "classificar forma" virar "interpretar conteúdo"
(Documento-Mestre.md §6.5; Regra 7) é de sistema, não de prompt: a
chamada usa `output_config.format` com um JSON Schema cujo único campo
é um enum fechado de 6 strings — nenhum campo de "justificativa" existe
no schema. A resposta bruta do LLM nunca é confiada: é validada contra
um enum PHP nativo (`TipoFormacaoFreudiana::tryFrom()`); qualquer coisa
fora do esperado cai em `NaoClassificado`, nunca um valor solto ou texto
livre passa adiante.

- [x] `Domain/ValueObjects/TipoFormacaoFreudiana` (novo): enum fechado —
      `AtoFalho`, `Chiste`, `Sonho`, `Repeticao`,
      `FormacaoDeCompromisso`, `NaoClassificado` (fallback
      determinístico).
- [x] `Infrastructure/Contracts/ClassificadorEstruturalInterface` (novo):
      `classificar(string): TipoFormacaoFreudiana` — o contrato que a
      Application conhece, nunca a implementação concreta.
- [x] `Infrastructure/AI/AnthropicLLMService` (novo): **primeira
      implementação concreta de `LLMInterface`** no projeto (a interface
      existe desde a Sprint 1, sem implementação até aqui). Usa o SDK
      oficial `anthropic-ai/sdk` (primeira dependência de runtime do
      projeto — até aqui `composer.json` só listava `php`), modelo
      `claude-haiku-4-5` (classificação fechada em categorias é tarefa
      simples; mais barato e rápido que um modelo de raciocínio
      profundo). Lê a API key via `getenv('ANTHROPIC_API_KEY')` — mesmo
      padrão de `getenv('PSYCHEAI_SENHA_ANALISTA')` da Peça A.
- [x] `Infrastructure/AI/ClassificadorFreudianoLLM` (novo): monta o
      prompt grounded em Ontologia-Freud.md §3, valida a saída contra o
      enum fechado, aplica o guardrail acima.
- [x] Nova tríade `Application/UseCases/ClassificarFormacaoFreudiana/{Command,Handler,Result}`,
      mesmo padrão de `DetectarRecorrencias`. Sem valor default para o
      classificador no construtor do Handler (ao contrário de
      `DetectorRecorrenciasHandler`, que instancia um Domain Service
      puro por padrão) — instanciar `AnthropicLLMService` implicitamente
      esconderia a dependência de credencial externa.
- [x] `ReclassificadorLacaniano::reclassificarPorTipoFreudiano(TipoFormacaoFreudiana): string`
      (novo, aditivo): tabela de lookup determinística — Chiste/Sonho
      (condensação) → metáfora; Ato falho/Repetição (deslocamento) →
      deslize metonímico; Formação de compromisso → indeterminado entre
      as duas. `reclassificar()`/`reclassificarComTrajeto()` continuam
      congelados.
- [x] `.env.example`: nova entrada `ANTHROPIC_API_KEY=`.
- [x] Testes, tudo aditivo: novo `TipoFormacaoFreudianaTest`, novo
      `ClassificadorFreudianoLLMTest` (o teste que prova o guardrail —
      JSON válido, tipo fora do enum, texto livre não-JSON, JSON sem
      campo esperado, e falha de rede/API simulada: todos os casos caem
      em `NaoClassificado`, nenhum lança exceção para fora do adapter),
      novo `ClassificarFormacaoFreudianaHandlerTest`, casos novos em
      `ReclassificadorLacanianoTest`. 449 testes, 1124 asserções (eram
      436/1102 ao final da revisão anterior) — zero regressão.

**Explicitamente fora de escopo nesta passada** (ver
[Sprints futuras](#sprints-futuras-não-planejadas-em-detalhe-nesta-fase)):
nenhum endpoint ou tela expõe o rótulo do Motor Freud ainda — fica para
depois, quando a Application Service Provider ganhar o wiring completo;
nenhuma chamada de LLM entra no Motor Lacan.

## Sprint 18 — Plataforma: contas reais de analista (2026-07-30)

Escopo desta passagem, alinhado com o usuário antes de implementar (o
plano completo, "autenticação real, usuários, permissões, administração,
publicação", é grande demais para uma sprint só): substitui a senha
única `PSYCHEAI_SENHA_ANALISTA` por contas reais de analista — um único
papel, sem permissões/administração granulares ainda. O Sujeito que fala
em `/conversa*` continua anônimo por cookie, sem conta — "dois públicos,
duas regras" (revisão pós-Sprint 16) permanece valendo.

- [x] `Domain/ValueObjects/Email` (novo) + `Domain/Entities/Analista`
      (novo): conta de acesso ao sistema, sem nenhum significado
      psicanalítico — fora do vocabulário teórico das Ontologias, ao
      contrário de Sujeito/Sessao/Discurso. `verificarSenha()`
      (`password_verify`) fica na Entidade; `senhaHash` nunca sai da
      fronteira de Domínio (nem em `AnalistaDTO`).
- [x] `Domain/Repositories/AnalistaRepository` +
      `Infrastructure/Persistence/SQLite/Repositories/SQLiteAnalistaRepository`
      + migration `CreateAnalistasTable` (versão `0008`, tabela
      `analistas`, e-mail com `UNIQUE`).
- [x] `Application/UseCases/CadastrarAnalista/{Command,Handler,Result}`
      (mesmo padrão de `CadastrarSujeito`: Handler puro, valida via
      Value Objects, `ComandoInvalidoException` em caso de e-mail
      inválido ou senha vazia) + `Application/Services/AnalistaApplicationService`
      (`criar`, `buscarPorEmail`, `autenticar` — este último nunca
      distingue "e-mail não existe" de "senha errada" no retorno, para
      não vazar quais e-mails têm conta).
- [x] API REST ganha `POST /auth/login`
      (`Presentation/Controllers/AutenticacaoController`): 200 com
      `{id, email}` ou 401 (`HttpException::naoAutorizado()`, novo).
      Sem endpoint de cadastro exposto por HTTP nesta passagem — ver CLI
      abaixo.
- [x] `Presentation/Web/Security/PortaoDeAnalista`: `autenticar(string
      $senha): bool` (comparava com `getenv('PSYCHEAI_SENHA_ANALISTA')`)
      foi removido; `abrirSessao(string $analistaId): void` (chamado só
      depois que a API confirma a credencial) e `analistaId(): ?string`
      são novos. `estaAutenticado()`, `sair()` e `proteger()` continuam
      intocados — nenhuma rota protegida mudou em `Web/Routes.php`.
- [x] `Presentation/Web/Controllers/AutenticacaoAnalistaController` passa
      a chamar `POST /auth/login` via `HttpClientInterface` (mesmo
      cliente injetado em todo Controller Web) — a Web continua nunca
      falando com o banco diretamente. Formulário de `/entrar` ganha
      campo de e-mail. `ApiHttpClient::erroParaStatus()` passa a mapear
      401 para o mesmo `ErrorType::VALIDACAO` de 400/409/422.
- [x] `bin/criar-analista.php <email> <senha>` (novo): provisiona contas
      via terminal, bootstrapando `ApplicationServiceProvider::comSQLite()`
      direto (mesmo padrão de composição de `public/index.php`), sem rota
      HTTP — decisão explícita do usuário: o único uso real hoje é o
      próprio dono do sistema, uma tela de cadastro público seria
      superfície de ataque sem necessidade real.
- [x] Testes, tudo aditivo: `EmailTest`, `AnalistaTest`,
      `CadastrarAnalistaHandlerTest`, `SQLiteAnalistaRepositoryTest`,
      `AnalistaApplicationServiceTest`, `AutenticacaoEndpointsTest`
      (API), `PortaoDeAnalistaTest`/`AutenticacaoAnalistaControllerTest`/`RoutesTest`
      reescritos para o novo fluxo (Web), `HttpClientStub` ganha suporte
      a `auth/login`. Verificado também de ponta a ponta com os
      servidores reais (`php -S` + `curl`): login errado → 422, login
      certo → sessão + redirecionamento, rota protegida → 200 com
      sessão/302 sem ela, logout → sessão encerrada. 483 testes, 1200
      asserções (eram 457/1147 ao final da Sprint 19) — zero regressão.

**Explicitamente fora de escopo nesta passagem** (ver "Sprints futuras"
abaixo): múltiplos papéis/permissões, telas de administração de conta, e
contas reais para o Sujeito.

## Sprint 19 — Camada de Visualização Gráfica: Fundação + Grafo do Circuito/Trajeto

O usuário pediu, como direção de produto, poder ver graficamente os
diagramas formais que Lacan propôs (Esquema L, Esquema R, Grafo do
Desejo, os quatro discursos e nós Borromeanos, além da cadeia de
significantes). Investigação prévia confirmou que nenhum desses seis
conceitos tem base hoje — nem ontológica
([Ontologia-Lacan.md §2/§4](Ontologia-Lacan.md#2-escopo-teórico)) nem
estrutural — e que a única visualização viável imediatamente é o
circuito/trajeto (revisão pós-Sprint 16), que já tem dado real
(`OcorrenciaRecorrencia`). Esta sprint entrega só isso; as demais cinco
visualizações entram como backlog abaixo, cada uma gated atrás de sua
própria sprint de ontologia.

**Peça única — Grafo do Circuito/Trajeto.** Zero alteração em
Domain/Application/API REST — prova de que a sprint não introduz nenhuma
afirmação ontológica nova, só uma nova serialização do dado que a revisão
pós-Sprint 16 já autorizou:

- `Presentation/Web/ViewModels/GrafoCircuitoViewModel.php` (novo):
  `apartirDosCircuitos(CircuitoRecorrenciaViewModel[]): self` produz nós
  (uma Sessão distinta por `sessaoId`, deduplicada através de todos os
  circuitos) e arestas (um par consecutivo de ocorrências por
  Recorrencia, carregando `rotuloLacaniano` nullable); `toArray()` serve
  de payload a `Response::json()`.
- `ObservacoesSujeitoController::grafoCircuito()` (novo método, mesma
  classe — não bifurca): chama
  `GET subjects/{id}/observations/circuito?vocabulario=lacan` (a mesma
  chamada que `mostrar()` já faz) e devolve o grafo via
  `Response::json()`; erro de comunicação vira
  `Response::json(['sucesso' => false, ...], 502)`, mesmo padrão de
  `ConversaController::mensagens()`.
- Rota nova `GET /sujeitos/{id}/observacoes/grafo-circuito`, protegida
  por `PortaoDeAnalista::proteger()` como toda rota de análise — só o
  analista autenticado acessa (nunca o Sujeito em `/conversa*`).
- `observacoes/mostrar.php` mantém `CircuitoTrajetoComponent` como
  fallback textual sempre renderizado no servidor, e acrescenta um
  container + `<script src="https://d3js.org/d3.v7.min.js">` +
  `<script src="/assets/js/grafo-circuito.js" defer>` — SVG desenhado no
  navegador (D3 via CDN, sem bundler/Node), nós = Sessões em ordem
  cronológica, arestas sólidas (constatação de recorrência) ou
  tracejadas com rótulo visível (quando o Motor Lacan já reclassificou
  como "estrutura candidata: circuito") — nunca uma interpretação
  confirmada (Regra 10).
- Testes: novo `GrafoCircuitoViewModelTest` (dedução de nós, contagem de
  arestas, `rotuloLacaniano` nulo, lista vazia); casos novos em
  `ObservacoesSujeitoControllerTest` (`grafoCircuito()` sucesso e erro
  502) e `RoutesTest` (rota protegida redireciona a `/entrar` sem
  sessão; fluxo funcional completo através do Router). JS não ganha
  framework de teste novo — script deliberadamente sem lógica de
  negócio, verificação manual de ponta a ponta. Suíte completa sem
  regressões: 457 testes, 1147 asserções (eram 449 testes e 1124
  asserções ao final da revisão anterior).
- Atualização do Roadmap e `Arquitetura-Camadas.md`
  ("Camada de Visualização Gráfica — Grafo do Circuito/Trajeto (Sprint
  19)"). `Ontologia-Lacan.md` não é alterada — nenhum conceito lacaniano
  novo está sendo ontologizado.

Escopo desta sprint é exclusivamente o grafo do circuito/trajeto — sem
cadeia de significantes formal, sem Esquema L/R, sem Grafo do Desejo, sem
quatro discursos, sem nós Borromeanos (ver "Sprints futuras" abaixo).

## Motor Lacan — fundamentação teórica para o analista (2026-07-30)

Decisão do usuário, a partir de uma conversa teórica sobre a leitura
que Lacan faz do *Projeto para uma Psicologia Científica* de Freud, o
limite do significante e a distinção Sujeito/Analista: as telas do
**analista** passam a poder mostrar a regra da ontologia que fundamenta
cada rótulo lacaniano — nunca uma leitura clínica do conteúdo do
sujeito específico, que continua exclusiva do analista. Formalizado
como **Regra 11** em [Regras-Dominio.md](Regras-Dominio.md). O Sujeito
em `/conversa*` não muda em nada.

Ao implementar, ficou explícito que dois rótulos lacanianos coexistiam:
o estrutural sem LLM (`reclassificar()`/`reclassificarComTrajeto()` —
"deslize metonímico"/"circuito", já exposto) e o baseado no Motor Freud
via LLM (`reclassificarPorTipoFreudiano()` — "metáfora"/"formação de
compromisso indeterminada"), que **nunca havia sido ligado a nenhum
endpoint** desde a revisão do Motor Freud (commit `c734795`) — lacuna
fechada nesta passagem.

- [x] `ReclassificadorLacaniano::fundamentacaoPara(string $rotulo):
      string` (novo, Domain): tabela de lookup determinística — sem LLM
      — sobre os 4 rótulos já existentes, citando a regra de
      `Ontologia-Lacan.md §3`/`§4`/`§3.7` que os fundamenta. Não altera
      nenhum método existente.
- [x] `ObservacaoApplicationService::consultarCircuito()` ganha regra de
      precedência para o rótulo único de cada Recorrência, quando
      `comLeituraLacaniana=true`: (1) circuito (≥2 sessões) tem
      prioridade e **não chama o Motor Freud/LLM** — economia de
      custo/latência, já que o circuito é a leitura mais rica
      disponível; (2) senão, classifica o conteúdo via
      `ClassificarFormacaoFreudianaHandler` (já existia, nunca fora
      usado) e reclassifica com `reclassificarPorTipoFreudiano()`; (3)
      sem classificador disponível ou `NaoClassificado`, cai no rótulo
      padrão de sempre. Novo parâmetro construtor opcional
      `?ClassificarFormacaoFreudianaHandler $classificarFormacaoFreudiana`
      (mesmo padrão de `?RespostaAutomaticaInterface $respostaAutomatica`
      em `ApplicationServiceProvider::comPDO()`), para testes injetarem
      um classificador stub sem chamada de rede real.
- [x] `ApplicationServiceProvider::comPDO()` passa a instanciar
      `ClassificadorFreudianoLLM` + `AnthropicLLMService` como default
      do novo parâmetro — fecha a lacuna "nunca ligado a endpoint
      algum". Sem `ANTHROPIC_API_KEY` configurada, o classificador já
      captura qualquer falha (`Throwable`) e cai em `NaoClassificado`
      (comportamento existente de `ClassificadorFreudianoLLM`), então
      nenhum teste depende da chave — mas o caminho só é de fato
      exercitado quando a Recorrência não está em circuito.
- [x] `CircuitoRecorrenciaDTO`/`CircuitoRecorrenciaViewModel` ganham
      `?string $fundamentacaoTeorica`; `CircuitoResponse::toArray()`
      propaga o campo no JSON; `CircuitoTrajetoComponent` exibe a
      fundamentação junto do rótulo (`<small
      class="fundamentacao-lacaniana">`), só quando não-nula.
- [x] Testes, tudo aditivo: casos novos em
      `ReclassificadorLacanianoTest`, `ObservacaoApplicationServiceTest`
      (cobrindo os 3 ramos da precedência, com classificador stub —
      inclui um teste confirmando que o classificador **não** é
      chamado quando já há circuito), `ViewModelsTest`,
      `CircuitoTrajetoComponentTest`. Suíte completa sem regressão:
      492 testes, 1218 asserções (eram 483/1200 ao final da Sprint 18).

Escopo desta passagem é exclusivamente o rótulo único por Recorrência
em `consultarCircuito()` — não altera `consultar()` (sem circuito, que
continua usando só `reclassificar()`), não mexe na conversa do sujeito,
não introduz papéis/permissões novas.

## Sprint 20 — Contas reais do Sujeito (2026-07-31)

Decisão de produto do usuário: para que cada Sujeito tenha seu **espaço
singular de registro do discurso**, o cookie pseudônimo `psyche_pessoa_id`
(Sprint 17) não basta — não sobrevive a troca de navegador/dispositivo
nem a limpeza de cookies, então não garante de verdade que aquele espaço
seja "dele" ao longo do tempo. Reabre o item que tinha ficado como
"Sprints futuras" na Sprint 18 ("Contas reais para o Sujeito"). Escopo
fechado com o usuário antes de implementar: auto-cadastro público (não
CLI, ao contrário do analista), histórico do cookie vinculado à conta no
primeiro cadastro (não começa do zero), sem recuperação de senha nesta
passagem.

- [x] `Domain/Entities/Sujeito` ganha `?Email $email = null`/`?string
      $senhaHash = null` opcionais (trailing, compatível com todo
      `new Sujeito($id, $nome)` já existente) — `email()`, `temConta()`,
      `verificarSenha()`, `senhaHash()` (exposto só para a
      Infraestrutura persistir o hash). A maioria dos Sujeitos continua
      anônima; só ganham valor quando o Sujeito se cadastra.
      `Domain/Repositories/SujeitoRepository` ganha `findByEmail()`.
- [x] Migration `AddContaToSujeitosTable` (versão `0009`): `email`/
      `senha_hash` nulos na tabela `sujeitos` + índice único parcial
      (`WHERE email IS NOT NULL`). `SQLiteSujeitoRepository` atualizado
      (hidratar/save/findByEmail).
- [x] `SujeitoApplicationService` ganha `registrarConta()` (liga
      e-mail/senha a um Sujeito **já existente**, nunca cria um novo —
      reconstrói a Entidade preservando as Sessões já acumuladas, mesmo
      padrão de `atualizar()`), `autenticar()` e `buscarPorEmail()` —
      `autenticar()` nunca distingue e-mail inexistente de senha errada
      no retorno, mesmo cuidado de `AnalistaApplicationService`.
      `SujeitoDTO` ganha `?string $email`.
- [x] API REST: `POST /subjects/{id}/account` (`SujeitoController::registrarConta()`)
      liga a conta a um Sujeito existente — 404 se o Sujeito não existe,
      409 se ele já tem conta, 409 se o e-mail já está em uso por outro
      Sujeito. `POST /auth/subject/login` (novo
      `AutenticacaoSujeitoController`) — 200 com `{id, nome,
      quantidadeDeSessoes, email}` ou 401. Distinto de
      `/subjects/{id}/account` porque o login não sabe o id de antemão
      (é assim que se recupera o espaço a partir de outro
      navegador/dispositivo).
- [x] `Presentation/Web/Controllers/ConversaController` ganha
      `cadastro()`/`cadastrar()` (`GET`/`POST /conversa/cadastro`) e
      `entrar()`/`autenticar()` (`GET`/`POST /conversa/entrar`), mesmos
      nomes de método de `AutenticacaoAnalistaController` por
      consistência. `cadastrar()` liga a conta ao Sujeito que o cookie
      atual já aponta (chama `garantirSujeito()` primeiro, para quem
      chega direto nesta tela sem nunca ter passado por `/conversa`).
      `autenticar()` **troca o cookie de identidade** para o Sujeito da
      conta (via `POST auth/subject/login`) e descarta a Sessão ativa
      (pertencia à identidade anterior) — é assim que o mesmo espaço é
      recuperado de outro navegador. Novo `sair()`
      (`POST /conversa/sair`) remove o cookie de identidade; a próxima
      visita gera um Sujeito anônimo novo, como um primeiro acesso.
      Continua tudo fora do Portão do Analista — é a superfície pública
      do Sujeito. Views novas `conversa/cadastro.php`/`conversa/entrar.php`
      (mesmo padrão de `autenticacao/entrar.php`); `conversa/index.php`
      ganha links simples "Criar conta"/"Entrar" (sem personalização por
      enquanto — evita uma chamada de API extra a cada carregamento só
      para mostrar status de login, redução de escopo deliberada, mesmo
      espírito de "sem recuperação de senha").
- [x] Testes aditivos em todas as camadas (Domain/Infra/Application/
      API/Web) + `HttpClientStub` ganha suporte a
      `subjects/{id}/account` e `auth/subject/login`. Verificado também
      de ponta a ponta com servidores reais (`php -S` + `curl`,
      simulando "outro navegador" com um cookie jar novo): cadastro liga
      a conta ao Sujeito do cookie, login a partir de um cookie jar
      **diferente** devolve exatamente o mesmo `pessoa_id` original
      (prova de que o espaço é recuperado de verdade), logout remove o
      cookie. 523 testes, 1304 asserções (eram 492/1218 ao final da
      revisão do Motor Lacan acima) — zero regressão.

**Explicitamente fora de escopo nesta passagem** (ver "Sprints futuras"
abaixo): recuperação de senha, papéis/permissões diferenciados para o
Sujeito (continua um único tipo de conta), qualquer decisão sobre como a
Home do investimentos369 linka para este cadastro/login.

## Sprint 21 — Prefixo de base para a interface web (2026-07-31)

Decisão de integração: o PsycheAI vai rodar, ao menos na fase de testes,
sob um subcaminho do investimentos369.com (`investimentos369.com/psycheai`)
— sem subdomínio disponível na hospedagem atual, e um domínio próprio
para o PsycheAI ainda não existe (previsto para depois; a Home e o
Laboratório do investimentos369 já têm entradas/placeholder esperando o
link). Diferente de sonus-ai/Collector369 (integrados por chamada direta
de código/leitura de arquivo — nenhum dos dois é uma aplicação web com
rotas próprias), o PsycheAI é uma aplicação web completa: toda a camada
Web gera/casa caminhos absolutos a partir da raiz do domínio
(`NavigationMenu`, formulários, `Response::redirecionar()`, cookies, o
asset do grafo D3, `fetch()` em JS), então rodar sob `/psycheai` exige
que ela aprenda um prefixo configurável — decisão técnica que ficava em
aberto desde a Sprint 18 (ver memória de projeto `integracao_investimentos369.md`).

- [x] `Presentation/Web/Http/BasePath` (novo): holder estático com
      `definir()`/`url()`/`valor()`. Zero acoplamento com o Router: rotas
      continuam declaradas "limpas" (`/conversa`, `/sujeitos`, ...) em
      `Routes.php` — o prefixo é removido na borda de entrada (antes do
      Router ver a requisição) e reaplicado na borda de saída (em todo
      lugar que gera link/cookie/redirect), nunca no meio.
- [x] `public/web/index.php` lê `PSYCHEAI_BASE_PATH` (novo, `.env.example`)
      e remove o prefixo do `REQUEST_URI` antes de montar a `Request` —
      o Router nunca sabe que o prefixo existe. Sem a variável definida
      (padrão, inclusive em todo o desenvolvimento local até aqui),
      `BasePath::url()` é *no-op* — string vazia antes de qualquer
      caminho não muda nada, por isso as 532 asserções existentes
      continuam passando sem nenhuma alteração.
- [x] Prefixo reaplicado em: `Response::redirecionar()` (cobre todo
      redirect do Portão/Analista/Sujeito de graça, sem tocar
      Controllers); `ButtonComponent::link()` e `FormComponent::render()`
      (cobrem praticamente todo `href`/`action` da Web, únicos dois
      pontos que geravam link em toda a árvore de Views, confirmado por
      busca exaustiva); `partials/sidebar.php` (menu de navegação);
      `ConversaController` (cookie `psyche_pessoa_id`, path escopado ao
      prefixo); `conversa/index.php` (links "Criar conta"/"Entrar" e o
      `fetch()` em JS); `observacoes/mostrar.php` (`data-endpoint` do
      grafo e o `<script src>` de `grafo-circuito.js`). O cookie de
      sessão nativo do PHP (`session_set_cookie_params()`) também é
      escopado ao prefixo, para não colidir com a sessão do próprio
      investimentos369 na raiz do domínio.
- [x] Achado durante a verificação de ponta a ponta: o servidor embutido
      do PHP (`php -S`), ao receber `return false` do roteador achando
      que é um arquivo estático, procura o arquivo pelo `REQUEST_URI`
      **original** (ainda com o prefixo) — não bate com o caminho físico
      em disco quando há prefixo configurado. Corrigido servindo o
      arquivo diretamente (`readfile()`) no roteador quando
      `PSYCHEAI_BASE_PATH` está em jogo; só afeta teste local — em
      produção (Apache/Nginx) o servidor real nunca invoca PHP para pedir
      um asset estático.
- [x] Testes aditivos: `BasePathTest` (novo), casos novos em
      `ButtonComponentTest`/`FormComponentTest`/`ResponseTest`/`RoutesTest`
      confirmando o prefixo aplicado a link/ação de formulário/redirect/
      menu de navegação quando configurado — todos com `tearDown()`
      limpando `BasePath::definir('')`, para não vazar estado entre
      testes (holder estático). Verificado também de ponta a ponta com
      servidores reais e `PSYCHEAI_BASE_PATH=/psycheai`: menu, formulário,
      cookie escopado a `/psycheai`, redirecionamento do Portão e o asset
      do grafo — todos corretos. 532 testes, 1318 asserções (eram
      523/1304 ao final da Sprint 20) — zero regressão.

**Fora de escopo desta passagem** (decisão do usuário/infraestrutura, não
de código): onde exatamente os arquivos do PsycheAI ficam no servidor da
Hostinger (o document root público — `public_html` ou equivalente —
precisa apontar direto para o conteúdo hoje em `public/web/`, mesmo
padrão que o próprio investimentos369 já usa consigo mesmo: código fora
do document root, só o necessário exposto); a criação do subcaminho/link
real na Home e no Laboratório do investimentos369 (já feita pelo usuário
— commit `f9c854b` — como placeholder, à espera deste prefixo).

## Sprint 22 — Captura de Áudio da Sessão (transcrição verbatim)

**Origem**: o Sujeito hoje se comunica só digitando num textarea em `/conversa`. Pedido do usuário: o significante que representa o sujeito do inconsciente está nas entrelinhas da fala — meia-palavra, ato falho, hesitação — material que a escrita já filtra e que uma transcrição "corrigida" apagaria de novo. A sessão passa a poder ser **falada e gravada**, não só digitada, com a gravação bruta preservada para que o analista possa ouvir o original e validar o que o sistema escreveu.

Três decisões fechadas com o usuário antes de implementar:

1. **Gravação contínua por sessão** — um único áudio do início ao fim (não um-por-turno).
2. **Transcrição server-side verbatim via API paga (OpenAI Whisper, `response_format=verbose_json`, `temperature=0`)** — sem infraestrutura própria de GPU; os segmentos com timestamp que a API já devolve resolvem de graça a divisão da gravação contínua em eventos discursivos, sem detecção de pausa própria.
3. **Convive com o texto** (não substitui o textarea) — o usuário precisa poder ouvir as sessões para validar a transcrição, então o áudio bruto fica acessível ao analista, nunca só vira texto e é descartado.
4. **v1 usa upload único** — o sujeito clica em "Encerrar e enviar gravação"; o navegador manda o arquivo completo de uma vez. Upload incremental em pedaços fica para depois de validado o uso real.

**Achado da exploração**: o projeto já previa isso desde a Sprint 7 — `Infrastructure/Contracts/StorageInterface.php` (docblock cita literalmente "áudios de sessão") e `Infrastructure/Contracts/TranscriptionInterface.php`/`TranscriptionResultDTO` existiam desde a fundação do projeto, sem implementação concreta até aqui.

- [x] **Domain**: nova entidade `GravacaoAudio` (id, sessaoId, caminhoArmazenamento, status, criadaEm, transcritaEm) — status é metadado operacional de pipeline, não conteúdo produzido pelo sujeito, então `marcarTranscrita()`/`marcarFalha()` não violam a Regra 2. Novo enum `StatusTranscricao` (Pendente/Transcrita/Falha). Nova interface `GravacaoAudioRepository`.
- [x] **Infrastructure**: migration `CreateGravacoesAudioTable` (versão `0010`); `GravacaoAudioMapper`/`SQLiteGravacaoAudioRepository`; **primeira implementação concreta de `StorageInterface`** (`LocalFilesystemStorage`, disco local sob `storage/audio/`, raiz configurável via `PSYCHEAI_AUDIO_STORAGE_PATH`); **primeira implementação concreta de `TranscriptionInterface`** (`OpenAIWhisperTranscriptionService`, chave em `OPENAI_API_KEY`); `TranscriptionResultDTO` ganha campo aditivo `segments` (texto/início/fim por trecho). Novo worker CLI `bin/transcrever-gravacoes.php` (mesmo padrão de `bin/criar-analista.php`), pensado para rodar via cron do servidor (agendar o cron em si é decisão de infraestrutura do usuário, fora do escopo desta sprint).
- [x] **Application**: `RegistrarGravacaoAudio` (Command/Handler/Result) grava o áudio bruto via `StorageInterface` e persiste a `GravacaoAudio` pendente. `TranscreverGravacaoAudio` (Command/Handler/Result) reaproveita `RegistrarEventoDiscursivoHandler` — o mesmo usado por `MensagemApplicationService::enviar()` — para criar um `EventoDiscursivo` por segmento, **sem** disparar `RespostaAutomaticaInterface` (é a fala contínua do sujeito, não um turno de pergunta/resposta). Nova `GravacaoAudioApplicationService` (`registrar`/`transcrever`/`buscarPorSessao`/`bytesDoArquivo`/`listarPendentes`).
- [x] **Presentation API**: `Presentation\Http\Request` ganha decodificação JSON preguiçosa (só ao chamar `corpo()`) e `corpoBinario()` aditivo — necessário porque o corpo de upload é o próprio áudio, nunca JSON. Novo `GravacaoAudioController` com `POST /sessions/{id}/audio` (primeiro endpoint de upload binário do projeto) e `GET /sessions/{id}/audio` (devolve os bytes originais, `Content-Type: audio/webm` — nunca o texto transcrito).
- [x] **Presentation Web**: `HttpClientInterface`/`ApiHttpClient` ganham `postBinario()`/`getBinario()` (novo `BinaryApiResponse`, simétrico a `ApiResponse` mas com bytes crus) — preserva a regra de que a Web nunca fala com storage/banco direto, só via HTTP client. `Presentation\Web\Http\Request` ganha `corpoBinario()` aditivo (capturado de `php://input` em `public/web/index.php`). `conversa/index.php` ganha gravação via `MediaRecorder` (botão "Gravar"/"Encerrar e enviar gravação", oculto sem suporte do navegador) postando para `POST /conversa/audio` (novo método em `ConversaController`) — o textarea existente continua funcionando em paralelo. Tela de detalhe de Sessão (`sessoes/mostrar.php`, atrás do Portão do Analista) ganha um player `<audio>` servido por `GET /sessoes/{id}/audio`.
- [x] **Testes**: aditivos em todas as camadas (Domain/Infrastructure/Application/API/Web), incluindo novos duplos de teste compartilhados `StorageStub`/`TranscricaoStub` (`tests/Support/`, mesmo padrão de `HttpClientStub`) e atualização dos três fakes de `HttpClientInterface` existentes (`ObservacoesHttpClientFake`/`HistoricoHttpClientFake`/`MensagemHttpClientFake`) com os dois métodos binários novos da interface. **569 testes / 1392 asserções, zero regressão** (eram 532/1318 ao final da Sprint 21).
- [x] **Fora de escopo desta passagem**: upload incremental em pedaços (decisão explícita, ver acima); segmentação por turno em vez de gravação contínua; qualquer leitura/rótulo dos Motores Freud/Lacan sobre o conteúdo transcrito (a Regra 11 continua intocada — `/conversa*` nunca expõe interpretação); agendamento do cron do worker no servidor (infraestrutura, não código).

## Sprint 23 — Motor de Enunciação Socrática via LLM (conversa real com o Sujeito)

**Origem**: até aqui `RespostaAutomaticaInterface` só tinha dois estados possíveis, nenhum deles uma conversa de verdade — `RespostaFixaService` (texto fixo) e `RespostaEcoRecorrenciaService` (Sprint 17, template `sprintf` quando detecta repetição literal). Nenhum LLM participava da resposta ao Sujeito; o único LLM do projeto (Motor Freud) classificava formações estruturais só para as telas do analista. Pedido do usuário: a ferramenta precisa "conversar de verdade", com "jogo de cintura", dando continuidade ao assunto que o Sujeito traz — mantendo a promessa do método socrático ([Documento-Mestre.md §6.7](Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático)) e as Regras 7/9/10/11 ([Regras-Dominio.md](Regras-Dominio.md)): nunca afirmar, nunca interpretar, nunca dar causa ou diagnóstico, só perguntar.

Três decisões fechadas com o usuário antes de implementar, não relitigar:

1. **O LLM roda em toda mensagem do Sujeito**, não só quando há repetição literal — é o que torna isso conversa, não um gatilho raro.
2. **Guardrail é estrutural** (JSON Schema fechado + validação de forma), mesmo padrão do Motor Freud — sem lista de bloqueio léxica; a fidelidade ao método socrático vem do prompt/design da experiência, não de moderação de conteúdo a posteriori.
3. **Vira o binding padrão** de `RespostaAutomaticaInterface` em `ApplicationServiceProvider::comPDO()`, mesmo padrão `??=` já usado para `$classificarFormacaoFreudiana`.

**Achado que simplifica o design**: `EventoDiscursivo` não guarda quem falou — a convenção já usada pelo projeto (`MensagemApplicationService`, docblock + `MensagemViewModel`) é que, dentro do único `Discurso` de uma `Sessao`, posição par = Sujeito e ímpar = Sistema (a conversa sempre alterna estritamente). Isso permite reconstruir "os últimos N turnos desta sessão" sem nenhum campo novo no Domínio, só sabendo o `sessaoId` — que `RespostaAutomaticaInterface::responder()` ainda não recebia.

- [x] **Infrastructure/Contracts**: `RespostaAutomaticaInterface::responder()` ganha `$sessaoId = ''` (parâmetro aditivo, mesma técnica já usada para `$sujeitoId` na Sprint 17 — implementações que ignoram continuam válidas). Nova porta `GeradorDePerguntaSocraticaInterface` (`gerar(string, ContextoConversaDTO): ?string` — `null` sinaliza "guardrail falhou, use o fallback determinístico") + novo DTO `ContextoConversaDTO` (turnos recentes, se é repetição, descrição da recorrência).
- [x] **Infrastructure/AI**: `GeradorDePerguntaSocraticaLLM` — mesmo guardrail sistêmico do Motor Freud: `output_config.format` só admite um campo (`pergunta`); a resposta bruta só é aceita se for JSON válido, com texto não vazio terminando em "?"; qualquer desvio (JSON inválido, campo ausente, texto que não é pergunta, falha de rede/API) devolve `null` dentro de `try/catch (Throwable)`, nunca propaga exceção. O prompt de sistema cita/parafraseia Documento-Mestre.md §6.7 e as Regras 7/9/10/11, proibindo explicitamente vocabulário técnico (ato falho, metáfora, etc., exclusivos do analista). Nova `RespostaSocraticaService` — **binding padrão a partir desta sprint**: calcula a resposta de `RespostaEcoRecorrenciaService` primeiro (cobre Sujeito não encontrado sem custo de API, e serve de rede de segurança quando o LLM falha/guardrail rejeita); recalcula `ehRepeticao`/`descricaoRecorrencia` com o mesmo critério da Sprint 17 (`minimo=1`); monta os turnos recentes a partir de `Sessao->discursos()[0]` (paridade de `Posicao`).
- [x] **Application**: nova tríade `UseCases/GerarPerguntaSocratica/` (Command/Handler/Result), mesmo padrão de `ClassificarFormacaoFreudiana` — `GerarPerguntaSocraticaHandler` sem valor default para o gerador (dependência externa, wiring explícito no composition root). `MensagemApplicationService::enviar()`: única linha alterada, passa `$sessaoId` (já em escopo) para `responder()`.
- [x] **Infrastructure/Providers**: `ApplicationServiceProvider::comPDO()` — `$respostaAutomatica ??= new RespostaSocraticaService(...)`, mesmo padrão já usado para `$classificarFormacaoFreudiana`. Nenhuma rota/API muda — `MensagemApplicationService::enviar(string, string)` mantém a assinatura pública intacta.
- [x] **Testes**: novos `GeradorDePerguntaSocraticaLLMTest` (espelha `ClassificadorFreudianoLLMTest` — JSON válido terminando em "?", JSON válido que não termina em "?", campo ausente, texto livre não-JSON, pergunta vazia, falha de rede/API simulada — todos caem em `null`, nenhuma exceção escapa), `GerarPerguntaSocraticaHandlerTest`, `RespostaSocraticaServiceTest` (espelha `RespostaEcoRecorrenciaServiceTest`, `SQLiteTestCase`: Sujeito inexistente não chama o LLM, LLM válido devolvido verbatim, LLM inválido cai no fallback determinístico, turnos recentes montados na ordem cronológica correta). Espião de `MensagemApplicationServiceTest` atualizado para capturar `$sessaoId`. **596 testes / 1450 asserções, zero regressão** (eram 581/1428 antes desta sprint).
- [x] **Fora de escopo desta passagem**: nenhuma leitura do Motor Freud/Lacan entra no prompt do Sujeito (evita segunda chamada de LLM por mensagem, mantém a Regra 11 — fundamentação teórica exclusiva do analista); nenhum sinal de "circuito" cross-sessão no contexto, só a janela de turnos da sessão atual; nenhum filtro léxico adicional além do guardrail estrutural (decisão do usuário); zero mudança em `Presentation/Web` (Views/Components/CSS) — havia uma reforma visual em andamento em paralelo (`layout.php`, `ConversaAreaComponent.php`, `estilo.css` novo), deliberadamente não tocada por esta sprint.

## Sprint 25 — Biblioteca Teórica (Base de Conhecimento Científico)

**Origem**: pedido do usuário para que o PsycheAI passasse a ter uma base científica permanente e auditável — toda regra computacional futura deve possuir rastreabilidade até a literatura que a fundamenta. Quatro decisões de escopo, todas do usuário, fechadas em rodadas sucessivas ao longo da própria Sprint (não relitigar): (1) profundidade "obra por obra" para Freud e Lacan — "um arquivo por obra, literalmente todas", não uma tabela consolidada nem só os núcleos já citados nas Ontologias; (2) a Biblioteca deixa de ser só bibliografia e passa a ser a Base de Conhecimento Científico oficial do projeto, com "Aplicação Computacional" obrigatória por conceito; (3) "Representação Computacional" (Visão do Sujeito / Visão do Analista) também obrigatória por conceito, com os princípios permanentes de separação Sujeito/Analista e de que a escrita lacaniana pertence exclusivamente ao analista; (4) Princípio da Neutralidade Observacional — o sucesso do PsycheAI nunca é medido pelo desfecho clínico, só pela qualidade da observação, inspirado nos próprios casos clínicos de Freud que não foram "casos de sucesso". Nenhum motor novo pode ser desenvolvido sem a fundamentação completa documentada primeiro.

- [x] `docs/Biblioteca-Teorica/`: nova estrutura de primeiro nível — `Freud/Obras/`, `Lacan/{Escritos,Outros-Escritos,Seminarios}/`, `Referencias/`, `Psicanalise/`, `Conceitos/`, e as seis pastas de Ciências Auxiliares (`Filosofia/`, `Linguistica/`, `Antropologia/`, `Psiquiatria/`, `Inteligencia-Artificial/`, `Engenharia-de-Software/`) deliberadamente vazias nesta Sprint, com `README.md` explicando escopo futuro.
- [x] **229 documentos catalogados**: 94 obras de Freud (de "Sobre o Mecanismo Psíquico dos Fenômenos Histéricos", 1893, a "A Divisão do Ego no Processo de Defesa", 1940), 74 de Lacan (30 Écrits + 17 Autres Écrits + 27 Seminários I–XXVII), 27 Referências Primárias (Platão a Edgar Allan Poe, conforme lista do briefing), 13 autores de Psicanálise pós-freudiana/pós-lacaniana (Klein a Quinet) e 21 Conceitos canônicos (os dez de [Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais) + os onze de [Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)).
- [x] **Precisão sobre exaustividade**: onde a datação, o título exato ou a própria inclusão de uma obra numa coletânea não pôde ser confirmada com confiança (sobretudo em Lacan/Outros-Escritos/ e nos Seminários sem edição oficial estabelecida por Jacques-Alain Miller — IX, XII–XV, XXI, XXII, XXIV–XXVII), o campo `Status` do documento é `A verificar` em vez de apresentar falsa precisão bibliográfica.
- [x] `docs/Biblioteca-Teorica/Modelo-de-Documento.md`: modelo único para documento de Obra, Autor e Conceito. Documentos de Obra/Autor são só metadados (Autor, Título, Ano, Idioma, Tipo, Área, Conceitos, relações, Motores relacionados, Status, Observações) — nenhuma interpretação ou resumo. Documentos de Conceito (só os 21 de `Conceitos/`, nunca Obra/Autor) têm adicionalmente `## Aplicação Computacional` (objetivo computacional, fundamentação, dados necessários/opcionais, eventos de origem, componentes reais do código, sete perguntas Sim/Não sobre automação/observação/confirmação, "Gera hipótese clínica?" fixo em "Nunca automaticamente", evidências produzidas, limitações) e `## Representação Computacional` (Visão do Sujeito: como interfere na conversa, se é perceptível, comportamento da IA, perguntas permitidas/proibidas; Visão do Analista: apresentação, visualizações, relações exibidas, evidências, motores e componentes envolvidos) — refletindo o princípio de que a escrita lacaniana e qualquer estrutura dos motores pertencem exclusivamente à interface do Analista.
- [x] `docs/Biblioteca-Teorica/Conceitos/`: dos 21 conceitos, auditados contra o código real desta data — **Repetição** é o mais implementado (DetectorRecorrencias, Recorrencia, circuito, grafo D3); **Formação de compromisso/Ato falho/Chiste/Sonhos** têm classificação real via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`; **Metonímia** é o único rótulo lacaniano efetivamente produzido por `ReclassificadorLacaniano`; **Metáfora** está mapeada na mesma tabela mas nunca é disparada pelo detector atual (que só reconhece repetição literal, não substituição entre significantes distintos); os onze demais (Inconsciente, Recalque, Pulsão, Desejo, Transferência, Significante, Cadeia significante, os três Registros RSI, Outro, Objeto a, Falta, Desejo lacaniano) são fundamentação teórica de fundo ou questões de pesquisa em aberto, sem operacionalização computacional nesta versão — nenhum componente inventado.
- [x] `docs/Biblioteca-Teorica/Como-os-Motores-Usam-a-Biblioteca.md`: documento de fechamento exigido pelos Critérios de Aceite, explicando a cadeia de rastreabilidade e o mapeamento de cada um dos quatro motores (Discourse Engine, Freud Engine, Lacan Engine, Modo Socrático) aos conceitos que o fundamentam.
- [x] `docs/Biblioteca-Teorica/Indices/`: seis índices navegáveis (Autor, Obra, Ano, Área, Conceito, Motor), **gerados programaticamente a partir dos mesmos datasets que os documentos individuais** — nunca digitados à mão, para nunca divergirem.
- [x] `docs/Biblioteca-Teorica/_gerador/`: script de apoio à catalogação (PHP — `gerar.php`, `gerar-indices.php`, `funcoes.php` e um dataset por área), explicitamente fora do namespace da aplicação (`app/`) — não é código de domínio, aplicação ou infraestrutura do PsycheAI, apenas ferramenta de geração dos 229 documentos a partir de dados estruturados, para permitir correção/expansão futura sem editar `.md` um a um.
- [x] `docs/Documento-Mestre.md`: novo §6.0 "Objetivo Científico do PsycheAI" (hipótese de trabalho do projeto, inspirada no "Projeto de uma Psicologia Científica" de Freud) com a cadeia de rastreabilidade obrigatória; dois novos princípios em §5 (separação de interface entre Sujeito e Analista; a escrita lacaniana pertence ao analista, nunca à conversa com o Sujeito) — registrados como princípios permanentes da arquitetura, não específicos desta Sprint.
- [x] `docs/Arquitetura.md`: novo §9 "Base Científica e Princípios de Representação (Biblioteca Teórica)", com o mesmo diagrama da cadeia de rastreabilidade e o detalhamento dos dois princípios éticos acima.
- [x] `docs/Arquitetura-Cientifica.md` (novo): documento consolidado dos princípios científicos permanentes — cadeia de rastreabilidade, separação Sujeito/Analista, escrita lacaniana exclusiva do analista e o novo Princípio da Neutralidade Observacional — distinto de `Arquitetura.md` (que permanece o documento de arquitetura técnica).
- [x] `docs/Modelo-Observacional.md` (novo): objetivo da observação (produzir observações confiáveis do discurso, nunca sucesso terapêutico) e o novo "Status do Caso" (Em andamento / Encerrado / Interrompido pelo sujeito / Interrompido pelo analista / Abandono / Encaminhamento / Outro) — um atributo puramente descritivo que jamais altera o valor científico dos dados coletados. Nenhuma mudança de Domínio/API foi feita — o documento registra a dimensão observacional, a decisão de implementação fica para sprint futura, sujeita à mesma cadeia de rastreabilidade.
- [x] `docs/Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md` (novo): fundamentação histórica do Princípio da Neutralidade Observacional a partir de quatro casos já catalogados em `Freud/Obras/` com desfecho não conclusivo — Caso Dora (tratamento interrompido pela paciente), Homem dos Lobos (dificuldades recorrentes ao longo da vida), Caso Schreber (nunca houve relação terapêutica — só leitura de texto autobiográfico) e Estudos sobre a Histeria (caso fundador "Anna O.", desfecho clinicamente ambíguo).
- [x] Validação: todos os links internos da Biblioteca Teórica (229 documentos + README/Modelo/Como-os-Motores/Valor-Cientifico + 6 índices) e dos três novos documentos de nível `docs/` resolvidos programaticamente contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente documental — sem novo motor, sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem dependência nova. O script em `_gerador/` é
ferramenta de catalogação, não parte da aplicação. Nenhuma interpretação
de obra foi escrita; nenhum conceito foi resumido de forma opinativa;
"Aplicação Computacional"/"Representação Computacional" descrevem apenas o
que já existe no código real (auditável) ou é fundamentação teórica de
fundo — nunca uma intenção de implementação futura apresentada como fato.

## Sprint 26 — Modelo Observacional

**Origem**: pedido do usuário para construir o Modelo Observacional oficial do PsycheAI — a camada que transforma os conceitos da Biblioteca Teórica em fenômenos observáveis computacionalmente, obrigatória para toda implementação futura. Nenhum conceito pode chegar à implementação sem antes possuir seu Modelo Observacional documentado. Objetivo científico da Sprint: responder, para cada um dos 21 conceitos canônicos, o que exatamente pode ser observado, o que nunca poderá ser observado, quais evidências computacionais podem ser produzidas e quais limites da observação computacional devem ser respeitados.

- [x] `docs/Modelo-Observacional/` (novo): estrutura de primeiro nível — `README.md` (catálogo, panorama auditado contra o código real), `Freud/README.md` e `Lacan/README.md` (síntese por motor) e `Conceitos/` com os 21 documentos, um por conceito canônico (os dez de [Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais) + os onze de [Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)), mesmo escopo 1:1 de [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/).
- [x] **Modelo único de documento por conceito**: Fenômeno observado (descrição, nunca interpretação), Evidências observáveis (repetições, mudanças discursivas, recorrências, interrupções, atos falhos/sonhos/lapsos relatados, mudanças de posição subjetiva — sempre como evidência, nunca como diagnóstico), Dados necessários, Dados opcionais, Eventos relacionados, Limites da observação (o sistema nunca afirma significado, intenção, desejo, significante, diagnóstico ou hipótese clínica), as cinco perguntas Sim/Não já em uso na Biblioteca Teórica (observação/organização/classificação automática, confirmação do sujeito, validação do analista), Evidências produzidas e Componentes envolvidos (Motor Freud, Motor Lacan, Memória Discursiva, Interface do Sujeito, Interface do Analista, Timeline, Circuito Pulsional, demais motores).
- [x] **Auditoria contra o código real**, mesma base já estabelecida pela Biblioteca Teórica: **Repetição** é o único conceito observado, organizado e classificado automaticamente por conta própria (`DetectorRecorrencias`, circuito, grafo D3); **Ato falho/Chiste/Sonhos/Formação de compromisso** são observados e classificados via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`; **Metonímia** é o único rótulo lacaniano efetivamente produzido, e apenas por reclassificação de uma observação já feita pelo Motor Freud — nunca observa por conta própria; **Metáfora** está mapeada na tabela de reclassificação mas nunca é disparada pelo detector atual; **Inconsciente/Recalque/Pulsão/Desejo (Freud)/Transferência** são fundamentação teórica de fundo, sem observação própria; os nove conceitos lacanianos restantes (Significante, Cadeia significante, os três Registros RSI, Outro, Falta, Objeto a, Desejo lacaniano) não têm nenhuma representação computacional definida nesta versão — nenhum componente inventado.
- [x] `docs/Modelo-Observacional.md` (existente, Sprint 25): estendido para v0.2 com novo §5 apontando para o catálogo por conceito, sem alterar os princípios já registrados (Objetivo da observação, Neutralidade Observacional, Status do Caso).
- [x] `docs/Documento-Mestre.md` (§6.0) e `docs/Arquitetura-Cientifica.md` (§1): cadeia de rastreabilidade obrigatória atualizada para referenciar `Modelo-Observacional/` como o detalhamento conceito a conceito da camada "Modelo Observacional", complementando (sem substituir) `Modelo-Observacional.md`.
- [x] Validação: todos os links internos dos 24 novos documentos (README + Freud/README + Lacan/README + 21 Conceitos) e das quatro edições em documentos existentes resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente documental — sem novo motor, sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado. Nenhuma
interpretação foi escrita; nenhum diagnóstico ou hipótese clínica foi
introduzido em nenhum documento; cada afirmação sobre o que é efetivamente
observado, organizado ou classificado decorre diretamente da Aplicação
Computacional já auditada na Biblioteca Teórica (Sprint 25) — nenhum
componente ou comportamento novo foi atribuído ao sistema nesta Sprint.

## Sprint 27 — Modelo Relacional

**Origem**: pedido do usuário para construir o Modelo Relacional oficial do PsycheAI — a camada que documenta como todos os conceitos da Biblioteca Teórica se relacionam entre si, sustentando o futuro Motor de Representação. Nenhuma relação podia ser criada sem fundamentação explícita na literatura já catalogada. Objetivo científico da Sprint: para cada um dos 21 conceitos canônicos, mapear conceitos antecedentes, consequentes e relacionados; relações estruturais, temporais, observacionais, de dependência, bidirecionais e não observáveis computacionalmente — cada uma com obra, autor, capítulo, intensidade e natureza — e consolidar tudo em matrizes e na especificação de grafos científicos.

- [x] `docs/Modelo-Relacional/` (novo): estrutura de primeiro nível — `README.md` (o que o Modelo é, critério de relacionamento, panorama desta Sprint), `Freud/README.md` e `Lacan/README.md` (síntese por agrupamento conceitual, incluindo a tabela completa "Como a Ontologia Lacan reorganiza a Ontologia Freud"), `Conceitos/` com os 21 documentos (mesmo escopo 1:1 de [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/) e [Modelo-Observacional/Conceitos/](Modelo-Observacional/Conceitos/)), `Matrizes/` (seis matrizes) e `Grafos/` (cinco especificações, sem implementação).
- [x] **Modelo único de documento por conceito**: Relações Científicas (conceitos antecedentes/consequentes/relacionados; relações estruturais/temporais/observacionais/de dependência/bidirecionais/não observáveis computacionalmente), Fundamentação (obra, autor, capítulo, tipo da relação, por relação), Intensidade (Fundamental/Forte/Moderada/Fraca/Contextual), Natureza (Estrutural/Clínica/Observacional/Linguística/Temporal/Topológica), Observabilidade (as quatro perguntas Sim/Não) e Motores envolvidos.
- [x] **Fonte exclusiva das relações**: a prosa relacional já registrada em [Ontologia-Freud.md §3–4](Ontologia-Freud.md#3-conceitos-fundamentais) e [Ontologia-Lacan.md §3–4](Ontologia-Lacan.md#3-conceitos-fundamentais) (incluindo "Como a Ontologia Lacan reorganiza a Ontologia Freud") e o campo "Conceitos relacionados" de cada documento de [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/) — nenhuma obra nova foi consultada ou citada, nenhuma relação foi inferida sem essa origem explícita.
- [x] **Seis matrizes** em `Matrizes/`: Conceito×Conceito (51 arestas, em três blocos: Freud×Freud, Lacan×Lacan, Freud×Lacan), Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência e Conceito×Observabilidade — as quatro últimas derivadas diretamente dos campos já catalogados em [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/) e [Modelo-Observacional/Conceitos/](Modelo-Observacional/Conceitos/), sem nenhum dado novo.
- [x] **Cinco grafos científicos especificados** em `Grafos/` — Grafo de Freud (10 nós), Grafo de Lacan (11 nós), Grafo Integrado (21 nós, 51 arestas), Grafo dos Motores (grafo bipartido conceito↔componente) e Grafo dos Conceitos (7 agrupamentos) — apenas nós, arestas e propriedades topológicas; nenhuma estrutura de dados, biblioteca de grafo ou endpoint foi criado.
- [x] `docs/Documento-Mestre.md` (§6.0), `docs/Arquitetura-Cientifica.md` (§1), `docs/Arquitetura.md` (§9.1): cadeia de rastreabilidade obrigatória atualizada para incluir "Modelo Relacional" entre "Modelo Observacional" e "Representação Computacional".
- [x] `docs/Modelo-Observacional.md` (existente, Sprint 25/26): estendido para v0.3 com novo §6 apontando para o Modelo Relacional como camada seguinte, sem alterar os princípios já registrados.
- [x] `docs/Biblioteca-Teorica/README.md`: cadeia de rastreabilidade e referências cruzadas atualizadas para apontar para `Modelo-Relacional/`.
- [x] Validação: todos os links internos dos novos documentos (README + Freud/README + Lacan/README + 21 Conceitos + 6 Matrizes + 6 Grafos, incluindo README de Grafos) e das seis edições em documentos existentes resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente científico: sem novo motor, sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado. Nenhuma
interpretação foi escrita; nenhuma obra foi resumida de forma opinativa;
toda relação documentada decorre diretamente da prosa relacional já
registrada nas duas Ontologias ou dos metadados já catalogados na
Biblioteca Teórica — nenhuma relação foi inventada nesta Sprint.

## Sprint 28 — ECO (Estrutura Computacional de Observação)

**Origem**: pedido do usuário para definir oficialmente a ECO como a interface conversacional do PsycheAI — a camada que representa exclusivamente a presença da plataforma diante do sujeito, que nunca conversa diretamente com os motores. Objetivo científico da Sprint: documentar completamente a identidade da ECO (manifesto, princípios permanentes, método socrático, posição clínica, fluxo conversacional, separação Sujeito/Analista aplicada à conversa e ética), sem alterar nenhum comportamento conversacional existente.

- [x] `docs/ECO/` (novo): `README.md` (índice, posição na cadeia de rastreabilidade), `Manifesto.md` (o que é a ECO, por que esse nome, e o mapeamento explícito de que ela não é um componente novo, mas o nome oficial da camada conversacional já em produção desde a Sprint 12), `Principios.md` (missão permanente — sustentar um espaço de associação livre — e os cinco princípios: jamais interpretar, jamais aconselhar, jamais diagnosticar, jamais conduzir a respostas esperadas, jamais ocupar o lugar do analista), `Metodo-Socratico.md` (a Maiêutica Socrática, com exemplos de perguntas permitidas e proibidas e comportamentos proibidos), `Posicao-Clinica.md` (a ECO não é terapia, não é análise, não substitui o analista, sustenta o espaço da fala), `Fluxo-Conversacional.md` (as nove etapas — início, abertura, associação livre, perguntas, silêncio, mudança de tema, retorno de temas, encerramento, nova sessão — cada uma marcada como já implementada, com o componente real correspondente, ou como especificação para sprint futura), `Interface-Sujeito.md` (o que o sujeito visualiza — conversa, histórico de sessões, configurações pessoais, consentimentos — e o que nunca visualiza — motores, recorrências, significantes, classificações, grafos, estruturas lacanianas, hipóteses, observações), `Interface-Analista.md` (o que o analista pode visualizar fora da ECO — memória longitudinal, recorrências, formações freudianas, representações lacanianas, timelines, circuitos, grafos, indicadores, observações computacionais), `Limites-da-ECO.md` (os dez limites permanentes consolidados em um único documento de referência) e `Etica.md` (a ECO nunca produzirá interpretações clínicas; toda interpretação pertence ao sujeito e ao analista).
- [x] **Auditoria contra o código real**, mesma disciplina já estabelecida pela Biblioteca Teórica/Modelo Observacional/Modelo Relacional: cada componente citado (`ConversaController`, `RespostaSocraticaService`, `GeradorDePerguntaSocraticaLLM`, `RespostaEcoRecorrenciaService`, `PortaoDeAnalista`) já existia em produção antes desta Sprint. Em `Fluxo-Conversacional.md` e `Interface-Sujeito.md`, cada afirmação é marcada explicitamente como "implementado" (com o componente real) ou "especificação para sprint futura" — silêncio, mudança de tema, retorno de temas cross-sessão, histórico próprio do sujeito e consentimentos não têm tratamento dedicado hoje; nenhum desses comportamentos foi implementado por esta Sprint, que é exclusivamente documental.
- [x] `docs/Documento-Mestre.md` (§7): o "Modo socrático" já registrado desde a Sprint 17 ganha identidade oficial — ECO — Estrutura Computacional de Observação — com referência cruzada para `ECO/README.md`; versão do documento avançada para 0.8.
- [x] `docs/Arquitetura-Cientifica.md`: novo §5 "ECO — identidade da interface conversacional", consolidando que os princípios de separação Sujeito/Analista (§2) e de escrita lacaniana exclusiva do analista (§3) valem integralmente para a ECO; versão avançada para 0.3.
- [x] Validação: todos os links internos dos dez novos documentos de `docs/ECO/` e das duas edições em documentos existentes resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente documental e arquitetônico — sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado, sem novo
comportamento conversacional. Nenhuma interpretação foi escrita; nenhum
componente foi inventado; cada afirmação sobre o que a ECO já faz decorre
diretamente do código já auditado nas Sprints 12, 17, 20, 22-24.

## Sprint 29 — Representação Computacional

**Origem**: pedido do usuário para construir o modelo oficial de Representação Computacional do PsycheAI — a camada que documenta como toda observação produzida pelos motores (Discourse Engine, Motor Freud, Motor Lacan) é apresentada ao Analista e, de forma estritamente distinta, ao Sujeito. Objetivo científico da Sprint: transformar as observações computacionais em representações estruturadas, auditáveis e fundamentadas cientificamente — nenhuma representação pode produzir interpretação, todas devem representar apenas evidências observacionais.

- [x] `docs/Representacao-Computacional/` (novo): `README.md` (o que é a camada, por que existe separada do Modelo Relacional, estrutura, rastreabilidade), `Principios.md` (os cinco atributos obrigatórios — observacional, rastreável, auditável, reproduzível, fundamentada na Biblioteca Teórica — e as quatro proibições permanentes — interpretar, diagnosticar, concluir, produzir hipótese clínica), `Interface-Sujeito.md` (consolida, do ponto de vista desta camada, que nenhuma das oito representações alcança o Sujeito, complementando sem duplicar [ECO/Interface-Sujeito.md](ECO/Interface-Sujeito.md)), `Interface-Analista.md` (especifica as oito representações disponíveis, com estado de implementação, complementando [ECO/Interface-Analista.md](ECO/Interface-Analista.md)), `Timeline.md`, `Memoria-Longitudinal.md`, `Recorrencias.md`, `Formacoes-Freudianas.md`, `Representacoes-Lacanianas.md`, `Circuitos.md`, `Grafos.md` e `Indicadores.md` (uma representação por documento, cada uma com objetivo, rastreabilidade até a Biblioteca Teórica, dimensões próprias auditadas item a item contra o código real, dados necessários/opcionais, componentes envolvidos e visão do Analista/Sujeito), `Evidencias.md` (tabela de rastreabilidade das oito representações até a Biblioteca Teórica) e `Visualizacoes.md` (catálogo das oito visualizações, cada uma respondendo às nove perguntas obrigatórias: objetivo, evidências, componentes, motores, dados necessários/opcionais, automação, dependência do analista, visibilidade ao sujeito).
- [x] **Auditoria contra o código real**, mesma disciplina já estabelecida pela Biblioteca Teórica/Modelo Observacional/Modelo Relacional/ECO: cada afirmação de "implementado" verificada contra `app/` nesta data (`LinhaDoTempoApplicationService`, `MemoriaLongitudinal`, `DetectorRecorrencias`, `Recorrencia`, `TipoFormacaoFreudiana`/`ClassificadorFreudianoLLM`, `ReclassificadorLacaniano`, `DetectorRecorrencias::detectarCircuito()`, `GrafoCircuitoViewModel`, `ConsolidacaoApplicationService`). Toda dimensão não implementada (ex.: "Intensidade" de uma Recorrência, "Mudanças" e "Encerramentos" na Timeline/Circuitos, seis das oito estruturas lacanianas do briefing, quatro dos cinco tipos de grafo) é marcada explicitamente como "Não implementado — especificação para sprint futura", nunca apresentada por antecipação.
- [x] **Nota terminológica de precisão**, registrada em `Circuitos.md`: o componente "Circuito Pulsional", já nomeado desde o Modelo Relacional (Sprint 27) nas matrizes Motor×Conceito e no Grafo dos Motores, tem o nome inspirado no conceito de Pulsão mas não o operacionaliza — o que de fato implementa é o circuito/trajeto de uma Recorrência através de Sessões distintas, mesma ressalva já registrada em `Modelo-Relacional/Conceitos/pulsao.md` e `objeto-a.md`, preservada aqui sem contradição.
- [x] `docs/Documento-Mestre.md` (§6.0): "Representação Computacional" passa a apontar também para o novo modelo consolidado, além da seção obrigatória por Conceito já existente; versão avançada para 0.9.
- [x] `docs/Arquitetura-Cientifica.md`: novo §6 "Representação Computacional — modelo oficial", consolidando que a separação Sujeito/Analista (§2) e a exclusividade lacaniana ao Analista (§3) valem integralmente para as oito representações; versão avançada para 0.4.
- [x] `docs/Modelo-Observacional.md` (§7) e `docs/Modelo-Relacional/README.md`: ambos estendidos com referência à Representação Computacional como camada seguinte na cadeia de rastreabilidade.
- [x] Validação: todos os links internos dos treze novos documentos de `docs/Representacao-Computacional/` e das quatro edições em documentos existentes resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente científico: sem novo motor, sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado. Nenhuma
interpretação foi escrita; nenhum componente foi inventado; toda afirmação
de "implementado" decorre diretamente do código já auditado pelas Sprints
anteriores, e toda dimensão sem correspondência no código é marcada como
especificação para sprint futura, nunca como fato consumado.

## Sprint 30 — Consolidação Científica v1.0

**Origem**: pedido do usuário para consolidar oficialmente a Base Científica v1.0 do PsycheAI — Sprint exclusivamente de auditoria, sem novo conceito, sem novo componente arquitetônico, sem código. Objetivo científico: auditar, consolidar, padronizar e certificar toda a documentação científica produzida entre a Sprint 23 e a Sprint 29, congelando a Base Científica na versão 1.0 e encerrando formalmente a Fase 1 — Fundação Científica do PsycheAI.

- [x] **Auditoria completa em três frentes paralelas**, cobrindo a totalidade dos 351 documentos de `docs/`: (1) Biblioteca Teórica — todos os 21 Conceitos, todos os documentos de topo, amostragem de 15 documentos de Obra/Autor, cross-check com as duas Ontologias; (2) Modelo Observacional + Modelo Relacional — ambos os READMEs, ambas as sínteses Freud/Lacan, as 6 Matrizes, os 5 Grafos, 12 dos 21 pares de Conceitos em ambas as camadas, verificação estrutural programática dos 42 documentos de Conceito; (3) ECO + Ontologias + núcleo institucional — os 10 documentos da ECO, ambas as Ontologias, Regras-Dominio.md, Documento-Mestre.md, Arquitetura-Cientifica.md, Arquitetura.md, e os dois documentos de Interface de `Representacao-Computacional/`.
- [x] **Verificação mecânica de cobertura e links**: 21/21/21/21 de cobertura por conceito (Biblioteca Teórica, Modelo Observacional, Modelo Relacional, e Bloco 3 "Representação Computacional" dentro de cada documento de Conceito), confirmada byte-a-byte; 3.895 links internos verificados em 351 arquivos, zero quebrado, zero âncora sem correspondência (script Python dedicado, reexecutado após as correções).
- [x] **2 achados bloqueantes corrigidos**: status da reclassificação lacaniana de Metáfora, incorretamente descrito como "nunca produzido na prática" em 5 documentos (`Biblioteca-Teorica/Conceitos/metafora.md`, `Modelo-Observacional/Conceitos/metafora.md`, `Modelo-Observacional/README.md`, `Modelo-Relacional/Conceitos/metafora.md`, `ECO/Interface-Analista.md`), quando `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — em produção desde a revisão pós-Sprint 17 e alcançado por `ObservacaoApplicationService::consultarCircuito()` — o produz sempre que uma Recorrência sem circuito é classificada como Chiste ou Sonho pelo Motor Freud; e divergência de status ("Implementado" vs. "Parcialmente implementado") para Recorrências entre `ECO/Interface-Analista.md` e `Representacao-Computacional/Interface-Analista.md`, reconciliada com nota cruzada.
- [x] **7 achados moderados corrigidos**: contradição cross-camada em Transferência (Modelo Relacional negava componentes que o Modelo Observacional documentava); contradição em Objeto a ("Validação do analista"); três erros aritméticos nos grafos científicos (`Grafo-Lacan.md` contava 17 arestas em vez de 18; `Grafo-Integrado.md` herdava a inconsistência, 22+17+11=50≠51; `Grafo-Motores.md` atribuía grau máximo errado — real é Memória Discursiva=9, Motor Freud=9, não "Interface do Analista e Memória Discursiva, 6 cada"); elo "Modelo Relacional" ausente na cadeia de rastreabilidade reproduzida em `Biblioteca-Teorica/Como-os-Motores-Usam-a-Biblioteca.md` (defasada desde a Sprint 25); elo "Representação Computacional" ausente nos 21 documentos de `Modelo-Relacional/Conceitos/` (defasados desde a Sprint 27, nunca atualizados após a Sprint 29) — corrigido em lote via script.
- [x] **Padronização terminológica**: nomenclatura ECO/"Modo Socrático" reconciliada em seis ocorrências estruturais (Matrizes, Grafos, dois documentos de Repetição); nos 229 documentos de Obra/Autor gerados por `_gerador/*.php` a partir de dados fixados na Sprint 25, "Modo Socrático" foi mantido como valor de campo com nota de equivalência histórica em `Biblioteca-Teorica/README.md` e `Modelo-de-Documento.md`, em vez de reescrita retroativa de 229 arquivos; "Desejo" sem qualificador padronizado para "Desejo (Freud)" em 3 documentos; "Ontologia computacional" (nome de etapa divergente do canônico "Ontologia") corrigido em 22 documentos; cabeçalho de tabela "Interface Analista"/"Interface Sujeito" padronizado para "Interface do Analista"/"Interface do Sujeito"; caveat de não-operacionalização do "Circuito Pulsional" (nome inspirado em Pulsão, sem operacionalizá-la) completado em `Modelo-Observacional/Conceitos/pulsao.md`.
- [x] **Relatório de cobertura de implementação**: todo componente citado como "Implementado" nesta Sprint foi auditado contra `app/`; itens sem correspondência real classificados explicitamente como Especificado ou Planejado — nunca apresentados como implementados por antecipação (ver [Base-Cientifica-v1.0.md, "Estado atual da implementação"](Base-Cientifica-v1.0.md#estado-atual-da-implementação)).
- [x] `docs/Base-Cientifica-v1.0.md` (novo): objetivo científico, arquitetura científica definitiva, princípios permanentes, hipóteses científicas, limites éticos, estrutura documental, cobertura da documentação, estado da implementação, critérios para futuras alterações e condições para abertura da Base Científica v2.0.
- [x] `docs/Documento-Mestre.md` (versão 1.0), `docs/Arquitetura-Cientifica.md` (versão 1.0, novo §7 "Base Científica v1.0 — certificação"): ambos atualizados para registrar oficialmente a conclusão da Base Científica v1.0.
- [x] Achado não corrigido, aceito com nota explícita: divergência bibliográfica de "Obra de origem" entre 5 documentos de `Modelo-Relacional/Conceitos/` (falta, registro-imaginario, registro-real, registro-simbolico, outro) e `Ontologia-Lacan.md §6` — o próprio §6 se autodeclara "sem ainda consolidar"; registrada como condição para a Base Científica v2.0, não como defeito desta Sprint.
- [x] Atualização do Roadmap (esta seção).
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente auditoria e consolidação documental —
sem novo conceito, sem novo componente arquitetônico, sem alteração de
código de Domínio/Aplicação/Infraestrutura/Apresentação, sem API nova, sem
migration, sem banco de dados, sem teste alterado. Com a conclusão desta
Sprint, a **Fase 1 — Fundação Científica do PsycheAI** está oficialmente
encerrada; inicia-se a **Fase 2 — Desenvolvimento Experimental**.

## Decisão de Arquitetura Permanente — Modos de Operação da Plataforma

**Não é uma sprint numerada** — registrada aqui como evolução permanente da arquitetura da plataforma, imediatamente após a certificação da Base Científica v1.0 (Sprint 30), a pedido explícito do usuário.

**Origem**: decisão de que o PsycheAI deixa de ser definido como plataforma conversacional e passa a ser definido como **Plataforma de Observação Computacional do Discurso**. O objeto científico é o discurso, não a conversa — a plataforma deve produzir a mesma Representação Computacional independentemente de como o discurso chega até ela. Os Motores do PsycheAI (Discourse Engine, Freud Engine, Lacan Engine) passam a ser formalmente independentes da origem do material analisado.

- [x] **Três modos de operação oficiais registrados**: **Modo 1 — ECO Conversacional** (Público: Sujeito; Fluxo: Sujeito → ECO → Motores → Representação Computacional → Interface do Analista; já em produção, nenhuma mudança de comportamento); **Modo 2 — ECO Clínica** (Público: profissionais autorizados; Fluxo: Sessão Clínica → Áudio → Transcrição → Motores → Representação Computacional → Interface do Analista; a ECO não participa, o profissional conduz integralmente, o PsycheAI só observa posteriormente, nenhuma intervenção durante a sessão; especificado, sem implementação); **Modo 3 — ECO Pesquisa** (Público: pesquisadores; Fluxo: Sessões anonimizadas → Motores → Representação Computacional → Produção Científica; deve respeitar anonimização, consentimento, ética em pesquisa, proteção de dados; especificado, sem implementação).
- [x] **Princípio arquitetônico permanente**: os Motores não dependem da origem do discurso — podem receber conversa da ECO, áudio, vídeo, transcrição, texto ou documentos clínicos autorizados; todos convergem para a mesma Representação Computacional (princípio de equivalência computacional).
- [x] `docs/Arquitetura-Cientifica.md` (versão 1.1, novo §8 "Modos de Operação da Plataforma", seis subseções: redefinição do PsycheAI, os três modos, o princípio de independência de origem, o diagrama de convergência até a camada de Motores — que não substitui nem contradiz a cadeia completa de §1 — e a nota de que a ECO não é toda a arquitetura).
- [x] `docs/Documento-Mestre.md` (versão 1.1, novo §8): registra oficialmente que o PsycheAI não é um chatbot, que a ECO Conversacional é apenas uma das interfaces possíveis, e que a plataforma é independente da interface de coleta do discurso.
- [x] `docs/ECO/README.md` (versão 1.1, nova seção "A ECO não é toda a arquitetura do PsycheAI"): a ECO é a interface do Modo 1; nos Modos 2 e 3 ela não participa. Nenhum conteúdo já existente sobre a ECO foi alterado — permanece válido como descrição completa do Modo 1.
- [x] `docs/Representacao-Computacional/README.md` (nova seção "Origem dos Dados"): tabela de cinco origens (Conversa, Texto, Áudio, Transcrição, Vídeo) com estado de implementação auditado contra `app/` (Conversa/Texto implementados desde a Sprint 12; Áudio/Transcrição parcialmente implementados desde a Sprint 22/7, sem ligação aos Modos 2/3; Vídeo não implementado) — nenhuma das oito representações já catalogadas muda de estrutura conforme a origem.
- [x] Validação: todos os links internos das cinco edições resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta decisão é exclusivamente documental e arquitetônico — sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado. Os Modos
2 e 3 são especificação de arquitetura, não implementação: nenhum
componente de captura de vídeo, condução de sessão clínica fora da ECO,
anonimização de pesquisa ou exportação científica foi criado.

## Decisão de Arquitetura Permanente — Ética da Psicanálise (fundamentação da ECO)

**Não é uma sprint numerada** — registrada aqui como evolução permanente da arquitetura, imediatamente após a decisão sobre os Modos de Operação da Plataforma, a pedido explícito do usuário.

**Origem**: decisão de que a posição da ECO diante do sujeito seja fundamentada, explicitamente, na Ética da Psicanálise desenvolvida por Jacques Lacan no Seminário 7 (*A Ética da Psicanálise*, 1959-60), já catalogado em [Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md](Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md) desde a Sprint 25 — nunca antes vinculado a um motor do PsycheAI ("Motores do PsycheAI relacionados: Nenhum (catalogação apenas)").

- [x] `docs/ECO/Etica-da-Psicanalise.md` (novo): fundamentação teórica no Seminário 7; registro de que o método conversacional da ECO (maiêutica socrática) e sua posição ética (Ética da Psicanálise) são elementos complementares, nunca substitutos um do outro; as oito posições que a ECO não ocupa (mestre, especialista, quem sabe, condução a um ideal, adaptação social, conselho, interpretação) e a contrapartida positiva (sustentar a possibilidade de emergência do discurso do sujeito) — nenhuma delas contradiz os cinco princípios de [ECO/Principios.md](ECO/Principios.md) nem os dez limites de [ECO/Limites-da-ECO.md](ECO/Limites-da-ECO.md); aprofunda-os pela via da ética lacaniana.
- [x] `docs/Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md`: campo "Motores do PsycheAI relacionados" atualizado de "Nenhum (catalogação apenas)" para ECO, com referência à nova fundamentação.
- [x] `docs/Documento-Mestre.md` (versão 1.2, novo §6.8 "Fundamentação ética: a Ética da Psicanálise", complementar a §6.7 já existente).
- [x] `docs/Arquitetura-Cientifica.md` (versão 1.2, novo §5.1, dentro do §5 já existente sobre a identidade da ECO).
- [x] `docs/ECO/README.md` (versão 1.2, novo item na tabela de Estrutura, contagem de documentos atualizada de nove para dez).
- [x] `docs/ECO/Etica.md` (versão 1.1, nova seção "Fundamentação na Ética da Psicanálise", sem alterar o compromisso já registrado — apenas explicitando sua origem teórica).
- [x] Nenhum novo conceito canônico foi criado — a Ética da Psicanálise é registrada como pilar permanente da arquitetura, mesmo estatuto do método socrático, não como 22º conceito da Biblioteca Teórica (que permanece em 21, íntegra desde a Sprint 25).
- [x] Validação: todos os links internos das seis edições/criações resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta decisão é exclusivamente documental — sem alteração de
código de Domínio/Aplicação/Infraestrutura/Apresentação, sem API nova,
sem migration, sem banco de dados, sem teste alterado, sem novo
comportamento conversacional da ECO.

## Ajuste de Arquitetura Permanente — Modos de Operação da Plataforma (consolidação para dois modos)

**Não é uma sprint numerada** — registrada aqui como evolução permanente da arquitetura, a pedido explícito do usuário. **Não substitui** a decisão anterior ("Decisão de Arquitetura Permanente — Modos de Operação da Plataforma", acima) — apenas esclarece e simplifica o papel de cada modo de operação. O princípio de fundo permanece intacto: o objeto científico do PsycheAI continua sendo o discurso, e a arquitetura computacional (Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologias → Modelo Computacional → Motores) permanece única, sem nenhum componente científico duplicado.

**Origem**: a organização original em três modos (ECO Conversacional, ECO Clínica, ECO Pesquisa) foi consolidada em **dois modos**, esclarecendo que a única diferença entre eles é a origem do discurso e o público que utiliza a plataforma — nunca uma fundamentação científica paralela.

- [x] **Modo 1 — destinado ao Sujeito**: Fluxo Sujeito → ECO → Captura de áudio → Transcrição → Motores → Representação Computacional → Interface do Analista. O discurso nasce dentro da própria plataforma. O Sujeito nunca acessa as representações produzidas. Corresponde ao antigo "Modo 1 — ECO Conversacional", com o fluxo detalhado para incluir a captura de áudio/transcrição já em produção desde a Sprint 22/24 dentro da própria conversa com a ECO.
- [x] **Modo 2 — destinado a profissionais e pesquisadores**: Fluxo Material Discursivo → Importação → Transcrição (quando necessária) → Motores → Representação Computacional → Interface do Analista. O discurso é produzido fora da plataforma — gravações de áudio, vídeos, transcrições, textos, outros registros discursivos autorizados. A plataforma não participa da produção do discurso, apenas de sua observação computacional. Corresponde à fusão dos antigos "Modo 2 — ECO Clínica" e "Modo 3 — ECO Pesquisa": as exigências éticas de cada um (não intervenção durante sessão clínica; anonimização/consentimento/ética em pesquisa/proteção de dados para material de pesquisa) foram preservadas integralmente como condições que acompanham o material conforme sua origem, não como fluxos separados.
- [x] `docs/Arquitetura-Cientifica.md` (versão 1.3, §8 reescrito: 8.0 Redefinição, 8.1 Modo 1, 8.2 Modo 2, 8.3 Princípio de independência de origem, 8.4 diagrama de convergência — "ambos os modos" —, 8.5 "a ECO não é toda a arquitetura").
- [x] `docs/Documento-Mestre.md` (versão 1.3, §8 atualizado para descrever os dois modos).
- [x] `docs/ECO/README.md` (versão 1.3, seção "A ECO não é toda a arquitetura do PsycheAI" atualizada para o Modo 2 único).
- [x] `docs/Representacao-Computacional/README.md` (seção "Origem dos Dados": tabela de origem × modo atualizada, cada origem agora anotada com o modo ao qual pertence).
- [x] Validação: todos os links internos das quatro edições resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta tarefa é exclusivamente documental — sem alteração de
código de Domínio/Aplicação/Infraestrutura/Apresentação, sem API nova,
sem migration, sem banco de dados, sem teste alterado. Nenhum princípio
científico permanente foi revisto — apenas a organização dos modos de
operação foi simplificada.

## Sprint 32 — Interface Definitiva da ECO (Modo Conversacional por Voz)

**Origem**: pedido do usuário para substituir a interface temporária de texto do Sujeito (`/conversa`, caixa + botão "Enviar", em produção desde a Sprint 12) pela primeira versão da interface definitiva da ECO — exclusivamente por voz, sem dependência de teclado durante a sessão, sem aparência de chatbot/mensageiro. Escopo restrito à camada de Apresentação: nenhum Motor, Domínio, Ontologia ou camada científica (Biblioteca Teórica/Modelo Observacional/Modelo Relacional/Representação Computacional) foi alterada.

- [x] **Achado que definiu a arquitetura**: a captura de áudio já existente (Sprint 22) só transcreve de forma assíncrona, em lote, sobre a gravação contínua da sessão inteira (`bin/transcrever-gravacoes.php`) — nunca por turno, e nunca de forma síncrona. Não havia caminho para "falar → ouvir a resposta" em tempo real. Reaproveitar esse pipeline como estava para cada turno duplicaria/corromperia o histórico visível (ambos escrevem na mesma sequência de posição par/ímpar de `EventoDiscursivo` que `MensagemApplicationService::enviar()` usa para distinguir Sujeito/Sistema).
- [x] **Uma única adição pequena fora da Presentation, decidida explicitamente com o usuário antes da implementação**: `GravacaoAudioApplicationService::transcreverTexto(string): string` (Application) — grava um arquivo temporário, chama o mesmo `TranscriptionInterface` (Whisper) já usado desde a Sprint 22, devolve só o texto. **Sem escrita em nenhum repositório, `Discurso` ou `EventoDiscursivo`** — não é o mesmo método usado pelo worker assíncrono (`transcrever()`, inalterado). Não toca Motor/Domínio/Ontologia; existe apenas para viabilizar o caminho síncrono voz→texto→pipeline de mensagem já existente.
- [x] **API**: novo endpoint stateless `POST /audio/transcricao` (`GravacaoAudioController::transcrever()`), que apenas expõe `transcreverTexto()` — nenhuma outra rota da API foi alterada.
- [x] **Presentation Web**: `ConversaController` ganha `mensagensAudio()` (`POST /conversa/mensagens/audio`) — transcreve o turno via `POST /audio/transcricao` e, com o texto obtido, segue exatamente o mesmo caminho que uma mensagem digitada já percorre (`processarConteudo()`, extraído de `processarEnvio()` sem mudança de comportamento — pergunta socrática e áudio de resposta gerados pelo pipeline inalterado da Sprint 23/24). Ausência de fala reconhecida não cria turno nenhum (mesmo espírito do silêncio em [Fluxo-Conversacional.md](ECO/Fluxo-Conversacional.md)). `montarAreaConversa()`/`renderizarConversaJson()` passam a expor também `audioRespostaUrl` (URL pronta da resposta mais recente) e, quando aplicável, `textoTranscrito` — chaves aditivas, nenhum contrato JSON existente quebrado. `ViewRenderer::renderComLayout()` ganha um 5º parâmetro opcional `$layout` (padrão `'layout'`, preservando as ~25 chamadas existentes).
- [x] **Novo layout `layout-eco.php`**: sem `NavigationMenu`/sidebar — é assim que Sujeitos/Sessões/Discursos/Memórias/Eventos Discursivos saem da navegação do Sujeito, sem filtrar itens em tempo de execução. Usado por toda a superfície `/conversa*` (conversa, cadastro, entrar).
- [x] **`conversa/index.php` reescrita**: caixa de texto e botão "Enviar" removidos da UI; nenhum histórico em bolhas de chat é exibido. Palco de estados (`data-estado`) implementando os cinco estados do pedido — **Pronta** (aguardando início), **Escutando** (captura contínua via `MediaRecorder`, segmentação automática de turno por detecção de silêncio com Web Audio API/`AnalyserNode`, mais um botão discreto de emergência "Concluir minha fala agora"), **Processando** (transcrição em curso), **Respondendo** (áudio da ECO tocando), **Encerrada** (resumo só operacional — duração da sessão, client-side; nunca análise). Cronômetro de sessão, indicador visual animado por estado (CSS puro, sem waveform), legenda com `aria-live="polite"` dobrando como anúncio de acessibilidade. A antiga tela de texto/gravação manual (Sprint 12/17/22) permanece no HTML como ferramenta de desenvolvimento interna, dentro de `#conversa-dev`, com o atributo `hidden` — nunca alcançada pela navegação do Sujeito, mas ainda testável/POSTável diretamente (`/conversa/enviar`, `/conversa/mensagens`, inalterados).
- [x] `public/web/assets/css/estilo.css`: seção "Conversa (experiência de chat)" substituída por "ECO (Interface de Voz)" — paleta neutra reaproveitando as variáveis `--cor-*` já existentes (nenhuma cor nova), indicador com animação de respiração, sem nenhuma forma de bolha de mensagem.
- [x] **Documentação**: [ECO/Interface-Sujeito.md](ECO/Interface-Sujeito.md) (v1.1) e [ECO/Fluxo-Conversacional.md](ECO/Fluxo-Conversacional.md) (v1.1, passos 1 e 8) atualizados para descrever a interface de voz e o encerramento client-side.
- [x] **Testes**: 9 testes aditivos, comportamento existente preservado sem alteração (`processarEnvio()`/`mensagens()`/`audio()` inalterados) — `GravacaoAudioApplicationServiceTest` (`transcreverTexto()`, 3 novos), `GravacaoAudioEndpointsTest` (`POST /audio/transcricao`, 2 novos), `ConversaControllerTest` (`mensagensAudio()`, 4 novos), `MensagemHttpClientFake` ganha suporte opcional a simular `POST /audio/transcricao`. **605 testes / 1471 asserções, zero regressão** (eram 596/1450 antes desta Sprint).
- [x] **Limite explícito desta Sprint**: verificado por suíte automatizada (PHPUnit) e leitura do HTML/CSS/JS gerado — não por uso real em navegador. O laço de voz de ponta a ponta (permissão de microfone, sensibilidade dos limiares de silêncio ao ruído de fundo, política de autoplay do navegador) precisa de validação humana com microfone real antes de ser considerado testado na prática; os limiares de detecção de silêncio (`LIMIAR_VOLUME`/`SILENCIO_MS`/`FALA_MINIMA_MS` em `conversa/index.php`) são um primeiro palpite, não calibrado contra gravação real.

## Ajuste de Arquitetura Permanente — Acesso aos Modos de Operação (Modo 2 nomeado Laboratório)

**Não é uma sprint numerada** — registrada aqui como evolução permanente da arquitetura, a pedido explícito do usuário, imediatamente após a Sprint 32. **Não substitui** nenhuma das duas decisões anteriores sobre Modos de Operação (acima) — não altera os dois modos, seu fluxo ou sua fundamentação científica; define exclusivamente **quem** pode utilizar cada um. Tarefa exclusivamente documental: sem alteração de código, API, banco de dados ou teste.

**Origem**: consolidar oficialmente a política de acesso aos dois Modos de Operação da plataforma.

- [x] **Modo 1 — ECO registrado oficialmente** como a interface pública da plataforma, destinada ao Sujeito, cujo objetivo é permitir a associação livre por meio da ECO. Registrado explicitamente que o Sujeito nunca terá acesso às representações computacionais produzidas pela plataforma: Biblioteca Teórica, Modelo Observacional, Modelo Relacional, Representação Computacional, Motores, Memória Discursiva, Grafos, Indicadores, Recorrências, Ferramentas de pesquisa — enumeração no nível arquitetônico, complementar (não substitutiva) à enumeração já existente no nível de interface em [ECO/Interface-Sujeito.md](ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza).
- [x] **Modo 2 nomeado oficialmente Laboratório**: o ambiente interno do PsycheAI, cujo objetivo é apoiar desenvolvimento, validação científica, pesquisa, observação computacional do discurso e análise profissional. Todo material discursivo produzido fora da plataforma é processado exclusivamente neste ambiente.
- [x] **Política de acesso registrada**: nesta primeira fase do projeto, o Laboratório é de acesso exclusivo do Administrador do PsycheAI — nenhum outro usuário tem acesso, visando permitir a evolução científica da plataforma antes da abertura para uso profissional.
- [x] **Evolução futura registrada** (sem compromisso de prazo, fora do escopo da versão atual): acesso controlado ao Laboratório para psicólogos, psicanalistas, pesquisadores, universidades, hospitais, centros de pesquisa e instituições autorizadas, exclusivamente por meio de um sistema de permissões ainda inexistente.
- [x] **Princípio permanente registrado**: o Laboratório não é uma funcionalidade do Sujeito — é um ambiente científico da plataforma. A ECO e o Laboratório são interfaces distintas que compartilham exatamente a mesma arquitetura científica.
- [x] `docs/Documento-Mestre.md` (§8, novo parágrafo "Política de acesso").
- [x] `docs/Arquitetura-Cientifica.md` (§8.1 ganha a lista explícita de acesso do Modo 1; §8.2 renomeado "Modo 2 — Laboratório"; três novas subseções: §8.6 Política de Acesso, §8.7 Evolução Futura, §8.8 Princípio Permanente).
- [x] `docs/ECO/README.md` (seção "A ECO não é toda a arquitetura do PsycheAI" ganha o nome "Laboratório" para o Modo 2 e um novo parágrafo de política de acesso).
- [x] Validação: todos os links internos das quatro edições resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Publicação e sincronização do repositório remoto.

Nenhum princípio científico permanente foi revisto. Nenhuma rota, Controller, migration ou teste foi tocado — o Laboratório, como Modo 2 com público externo autorizado, continua especificado sem implementação, exatamente como antes desta decisão; apenas a política de quem poderá utilizá-lo quando implementado foi formalizada.

## Sprint 33 — Expansão da Biblioteca Científica (Fundamentos Computacionais)

**Origem**: pedido do usuário para expandir oficialmente a Biblioteca Científica do PsycheAI incorporando os fundamentos científicos que sustentam toda a arquitetura computacional da plataforma — até esta Sprint, a Biblioteca Teórica fundamentava cientificamente apenas a psicanálise (Freud/Lacan); a partir desta Sprint, ela também fundamenta a camada computacional (processamento de linguagem, processamento de áudio, IA, arquiteturas cognitivas, engenharia científica, ética computacional). Não altera a Biblioteca Psicanalítica — acrescenta uma segunda grande área da Base Científica, de igual importância. Divisão de trabalho entre os dois eixos, esclarecida pelo usuário durante a Sprint: a Biblioteca Computacional ajuda a extrair e qualificar os dados; a Biblioteca Psicanalítica orienta como esses dados podem ser organizados na representação.

- [x] `docs/Biblioteca-Teorica/Fundamentos-Computacionais/` (novo): `README.md` (por que o eixo existe, estrutura, relação com as três pastas de Ciências Auxiliares já reservadas desde a Sprint 25 — `Inteligencia-Artificial/`, `Linguistica/`, `Engenharia-de-Software/` — preservadas sem duplicação de conteúdo, cada uma com nota cruzada explícita), `Modelo-de-Documento.md` (modelo próprio desta área, estendendo sem contradizer o [Modelo-de-Documento.md](Biblioteca-Teorica/Modelo-de-Documento.md) geral, com cinco seções obrigatórias por tópico: Aplicação no PsycheAI, Componentes da Plataforma relacionados, Relação com a Base Científica, Relação com os Motores, Relação com a Representação Computacional) e `Indice-Topicos.md` (índice manual dos 44 tópicos, distinto dos seis índices gerados por script de [Indices/](Biblioteca-Teorica/Indices/) que cobrem só Autor/Obra/Conceito).
- [x] **Seis categorias, 44 documentos de tópico**: [Processamento-Computacional-da-Linguagem/](Biblioteca-Teorica/Fundamentos-Computacionais/Processamento-Computacional-da-Linguagem/README.md) (8 — NLP, Linguística Computacional, LLMs, Embeddings, Tokenização, Recuperação de Informação, Representação Vetorial, Engenharia de Prompts), [Processamento-de-Audio/](Biblioteca-Teorica/Fundamentos-Computacionais/Processamento-de-Audio/README.md) (9 — Processamento Digital de Sinais, ASR, Whisper, VAD, Diarização, Prosódia, Entonação, Pausas, Segmentação da Fala), [Inteligencia-Artificial/](Biblioteca-Teorica/Fundamentos-Computacionais/Inteligencia-Artificial/README.md) (7 — IA, Aprendizado de Máquina, Deep Learning, Redes Neurais, Transformers, IA Generativa, Neuro-Symbolic AI), [Arquiteturas-Cognitivas/](Biblioteca-Teorica/Fundamentos-Computacionais/Arquiteturas-Cognitivas/README.md) (6 — visão geral + ACT-R, SOAR, LIDA, CLARION, Sigma, catalogadas exclusivamente como referência de posicionamento científico, nunca como arquitetura de implementação), [Engenharia-Cientifica/](Biblioteca-Teorica/Fundamentos-Computacionais/Engenharia-Cientifica/README.md) (6 — Rastreabilidade, Reprodutibilidade, Proveniência de Dados, Auditoria Científica, Versionamento Científico, Validação Experimental) e [Etica-Computacional/](Biblioteca-Teorica/Fundamentos-Computacionais/Etica-Computacional/README.md) (8 — Ética em IA, Pesquisa com Seres Humanos, Consentimento, Anonimização, LGPD, GDPR, Segurança de Dados, Governança de IA).
- [x] **Auditoria contra o código real** em cada um dos 44 documentos: apenas os tópicos com componente real em `app/` (Reconhecimento Automático de Fala/Whisper via `OpenAIWhisperTranscriptionService`; Large Language Models/IA Generativa via `AnthropicLLMService`/`ClassificadorFreudianoLLM`/`GeradorDePerguntaSocraticaLLM`; Pausas/Segmentação da Fala via a segmentação nativa do Whisper em `TranscriptionResultDTO::$segments`; Neuro-Symbolic AI como leitura retroativa do padrão já em produção `ClassificadorFreudianoLLM` + `ReclassificadorLacaniano`; Segurança de Dados via `PortaoDeAnalista`) afirmam uso real — todos os demais registram explicitamente "Nenhum implementado nesta versão", nunca uma intenção futura apresentada como fato.
- [x] **Duas notas de arquitetura explícitas, registradas em todos os 44 documentos e nos seis READMEs de categoria**: (1) as cinco Arquiteturas Cognitivas (ACT-R, SOAR, LIDA, CLARION, Sigma) são catalogadas exclusivamente como referência de posicionamento científico — nenhuma é ou será arquitetura de implementação do PsycheAI; (2) a Ética Computacional (LGPD, GDPR etc.) complementa, sem substituir, a Ética da Psicanálise já registrada em [ECO/Etica-da-Psicanalise.md](ECO/Etica-da-Psicanalise.md).
- [x] **Três pastas reservadas desde a Sprint 25 preservadas sem duplicação**: [Biblioteca-Teorica/Inteligencia-Artificial/README.md](Biblioteca-Teorica/Inteligencia-Artificial/README.md), [Biblioteca-Teorica/Linguistica/README.md](Biblioteca-Teorica/Linguistica/README.md) e [Biblioteca-Teorica/Engenharia-de-Software/README.md](Biblioteca-Teorica/Engenharia-de-Software/README.md) ganham cada uma uma nota cruzada explícita apontando para a nova área e distinguindo o escopo (mais estreito e auditado contra código, no caso de IA; Semiótica vs. Linguística Computacional; padrões de arquitetura de software vs. engenharia da pesquisa científica) — nenhum conteúdo movido, nenhuma pasta duplicada.
- [x] `docs/Biblioteca-Teorica/README.md`: nova seção "Dois eixos da Base Científica", tabela de estrutura e contagem total atualizadas.
- [x] `docs/Documento-Mestre.md` (§6.0) e `docs/Arquitetura-Cientifica.md` (novo §9, versão avançada para 1.3): registram oficialmente que a Base Científica do PsycheAI passa a ser interdisciplinar, com Fundamentação Psicanalítica e Fundamentação Computacional como eixos de igual importância, sem alterar nenhum dos oito princípios permanentes anteriores.
- [x] `docs/Base-Cientifica-v1.0.md`: nota explícita de que esta expansão é estritamente aditiva à certificação da Sprint 30 — não configura abertura da Base Científica v2.0, cujas condições completas permanecem inalteradas.
- [x] Validação: todos os links internos dos 53 arquivos novos/editados (README + Modelo-de-Documento + Índice de Tópicos + 6 READMEs de categoria + 44 documentos de tópico, mais as edições em `Biblioteca-Teorica/README.md`, três pastas reservadas, `Documento-Mestre.md`, `Arquitetura-Cientifica.md`, `Base-Cientifica-v1.0.md`) resolvidos contra o sistema de arquivos — zero link quebrado.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente documental — sem novo motor, sem
alteração de código de Domínio/Aplicação/Infraestrutura/Apresentação, sem
API nova, sem migration, sem banco de dados, sem teste alterado, sem
alteração da arquitetura dos Motores. Nenhuma interpretação foi escrita;
nenhum componente foi inventado; toda afirmação de "implementado" decorre
diretamente do código já auditado em `app/` nesta data, e toda dimensão
sem correspondência no código é marcada como "Nenhum implementado nesta
versão", nunca como fato consumado.

## Sprints futuras (não planejadas em detalhe nesta fase)

- **Cadeia de significantes (Lacan) como matema formal** (S1↔S2,
  metáfora/metonímia como grafo) — distinta do circuito já
  implementado (Sprint 19, acima);
  [Ontologia-Lacan.md §4](Ontologia-Lacan.md#4-relações-conceituais)
  afirma que nenhuma representação computacional desses conceitos está
  definida; exige sprint de ontologia computacional decidindo como (ou
  se) S1/S2 se ancoram em `EventoDiscursivo` antes de qualquer
  nó/aresta.
- **Esquema L + Esquema R (agrupados)** — sem menção nominal em nenhum
  doc hoje; Esquema R compartilha os vértices de L (a-a'-S-Outro), o que
  justifica ontologizar e desenhar os dois numa sprint só; exige sprint
  de ontologia própria definindo os vértices e o que cada um
  representaria estruturalmente no discurso registrado.
- **Grafo do Desejo** — o mais complexo dos formalismos lacanianos
  (múltiplos andares, $◊D, S(Ⱥ)); zero base ontológica ou estrutural
  hoje; exige sprint de ontologia própria, possivelmente faseada por
  andar dado o tamanho do aparato formal.
- **Quatro discursos lacanianos** (mestre/universitário/histérica/analista,
  Seminário 17) — adiados na revisão das Sprints 15-16 acima por falta de
  base ontológica e estrutural; requer não só ontologia mas também
  mudança estrutural em `EventoDiscursivo` (interlocutor, papel de
  enunciação, laço social — hoje inexistentes) antes de qualquer código.
- **Nós Borromeanos** —
  [Ontologia-Lacan.md §2](Ontologia-Lacan.md#2-escopo-teórico) já declara
  essa formalização fora do escopo atual ("formalizações posteriores");
  além da sprint de ontologia própria, é a única das cinco que
  provavelmente não cabe em nó/aresta simples (topologia de enlace RSI)
  — viabilidade em D3 puro vs. lib de topologia dedicada só deve ser
  decidida quando a sprint de ontologia correspondente existir.
- **Papéis/permissões e administração de conta** — adiados na Sprint 18
  acima por escolha explícita de escopo (só a conta única de analista
  nesta passagem); entra quando o produto precisar de mais de um papel
  de acesso.
- **Recuperação de senha** (Sujeito e analista) — adiada explicitamente
  na Sprint 20 (contas do Sujeito) e na Sprint 18 (conta do analista);
  exigiria infraestrutura de envio de e-mail que o sistema ainda não
  tem.
- **Decisão de roteamento entre a Home do investimentos369 e o
  cadastro/login do Sujeito** — a Sprint 20 implementou o cadastro/login
  em si (`/conversa/cadastro`, `/conversa/entrar`), mas não decidiu como
  a Home pública do investimentos369 chega até lá (ver memória de
  projeto `integracao_investimentos369.md`).
- **Motor de Enunciação Socrática: grounding no Motor Freud/Lacan** —
  adiado explicitamente na Sprint 23 acima; o prompt de
  `GeradorDePerguntaSocraticaLLM` hoje só usa turnos recentes +
  repetição literal, nunca a classificação estrutural
  (`TipoFormacaoFreudiana`/rótulo lacaniano) que já existe desde a
  revisão do Motor Freud. Incorporar isso exigiria uma segunda chamada
  de LLM por mensagem (custo/latência) e cuidado redobrado para nunca
  vazar vocabulário técnico ao Sujeito (Regra 11).
- **Motor de Enunciação Socrática: continuidade cross-sessão ("circuito")**
  — adiado na Sprint 23; o contexto do prompt hoje só inclui os turnos
  recentes da sessão atual, não o histórico de quando/onde um tema
  reapareceu em sessões anteriores (`OcorrenciaRecorrencia`, já
  disponível desde a revisão pós-Sprint 16).
- Definição de arquitetura técnica detalhada (camadas de domínio, aplicação e infraestrutura), a partir do Modelo Computacional do Discurso.
- Especificação técnica do Evento Discursivo (formato de registro, granularidade, critérios de segmentação) — ver [Modelo-Computacional-Discurso.md (3.2)](Modelo-Computacional-Discurso.md#32-por-que-uma-unidade-própria).
- Consolidação da bibliografia freudiana estruturada em [Ontologia-Freud.md (6)](Ontologia-Freud.md#6-referências) e da bibliografia lacaniana estruturada em [Ontologia-Lacan.md (6)](Ontologia-Lacan.md#6-referências).
- Investigação da questão de pesquisa central: como representar computacionalmente um significante sem reduzi-lo a uma simples palavra (ver [Documento-Mestre.md](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto) e [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites)).
- Definição de regras de negócio.
- Configuração de ambiente de testes automatizados.
- Implementação das primeiras funcionalidades.

> Este roadmap será revisado e expandido ao final de cada sprint.
