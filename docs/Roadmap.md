# Roadmap — Psyche AI

> Versão 0.18 — Revisão do Motor Freud: classificação estrutural via LLM (pós-implementação)

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

## Sprint 18 — Plataforma (escopo alto nível)

Autenticação real (substitui o Sujeito "visitante" fixo), usuários,
permissões, administração e publicação — totalmente greenfield, sem
nenhum código de auth/usuário/permissão hoje. Deve substituir
`Presentation/Web/Security/PortaoDeAnalista` (revisão pós-Sprint 16,
acima) por contas reais — ele foi desenhado deliberadamente para ser
descartável sem dívida de migração nesse momento.

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
- Definição de arquitetura técnica detalhada (camadas de domínio, aplicação e infraestrutura), a partir do Modelo Computacional do Discurso.
- Especificação técnica do Evento Discursivo (formato de registro, granularidade, critérios de segmentação) — ver [Modelo-Computacional-Discurso.md (3.2)](Modelo-Computacional-Discurso.md#32-por-que-uma-unidade-própria).
- Consolidação da bibliografia freudiana estruturada em [Ontologia-Freud.md (6)](Ontologia-Freud.md#6-referências) e da bibliografia lacaniana estruturada em [Ontologia-Lacan.md (6)](Ontologia-Lacan.md#6-referências).
- Investigação da questão de pesquisa central: como representar computacionalmente um significante sem reduzi-lo a uma simples palavra (ver [Documento-Mestre.md](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto) e [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites)).
- Definição de regras de negócio.
- Configuração de ambiente de testes automatizados.
- Implementação das primeiras funcionalidades.

> Este roadmap será revisado e expandido ao final de cada sprint.
