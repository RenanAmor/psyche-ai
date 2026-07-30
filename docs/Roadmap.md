# Roadmap — Psyche AI

> Versão 0.13 — Sprint 13 (Memória Discursiva Longitudinal)

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

## Sprints futuras (não planejadas em detalhe nesta fase)

- Definição de arquitetura técnica detalhada (camadas de domínio, aplicação e infraestrutura), a partir do Modelo Computacional do Discurso.
- Especificação técnica do Evento Discursivo (formato de registro, granularidade, critérios de segmentação) — ver [Modelo-Computacional-Discurso.md (3.2)](Modelo-Computacional-Discurso.md#32-por-que-uma-unidade-própria).
- Consolidação da bibliografia freudiana estruturada em [Ontologia-Freud.md (6)](Ontologia-Freud.md#6-referências) e da bibliografia lacaniana estruturada em [Ontologia-Lacan.md (6)](Ontologia-Lacan.md#6-referências).
- Investigação da questão de pesquisa central: como representar computacionalmente um significante sem reduzi-lo a uma simples palavra (ver [Documento-Mestre.md](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto) e [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites)).
- Especificação e implementação do **Discourse Engine** (estruturação do discurso, estruturas discursivas recorrentes candidatas a cadeias de significantes, contexto temporal).
- Especificação e implementação do **Freud Engine** (núcleo conceitual: inconsciente, recalque, pulsão, desejo, etc.), a partir da Ontologia Freud.
- Especificação e implementação do **Lacan Engine** (estrutura de leitura: significante, registros RSI, objeto a, etc.), a partir da Ontologia Lacan.
- Definição de regras de negócio.
- Configuração de ambiente de testes automatizados.
- Implementação das primeiras funcionalidades.

> Este roadmap será revisado e expandido ao final de cada sprint.
