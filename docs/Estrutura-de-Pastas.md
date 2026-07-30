# Estrutura de Pastas — PsycheAI

> Versão 1.9

Este documento define a organização física oficial do PsycheAI.

A estrutura do projeto foi organizada para separar claramente as responsabilidades entre Domínio, Aplicação, Infraestrutura e Apresentação, seguindo os princípios da Arquitetura em Camadas e do Domain-Driven Design (DDD).

---

# Estrutura

```text
psyche-ai/
│
├── app/
│   │
│   ├── Application/
│   │   ├── Contracts/
│   │   ├── DTOs/
│   │   ├── Exceptions/
│   │   ├── Services/
│   │   └── UseCases/
│   │
│   ├── Domain/
│   │   ├── Aggregates/
│   │   ├── Contracts/
│   │   ├── Entities/
│   │   ├── Events/
│   │   ├── Exceptions/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Specifications/
│   │   └── ValueObjects/
│   │
│   ├── Infrastructure/
│   │   ├── Contracts/
│   │   ├── Persistence/
│   │   ├── Providers/
│   │   ├── Logging/
│   │   ├── Messaging/
│   │   ├── Storage/
│   │   ├── AI/
│   │   ├── Clock/
│   │   └── UUID/
│   │
│   └── Presentation/
│       ├── Controllers/
│       ├── Requests/
│       ├── Responses/
│       ├── Views/
│       └── Web/
│           ├── Client/
│           ├── Components/
│           ├── Controllers/
│           ├── Errors/
│           ├── Http/
│           ├── Navigation/
│           ├── ViewModels/
│           ├── Views/
│           └── Routes.php
│
├── config/
├── docs/
├── public/
│   └── web/
│       └── index.php
├── storage/
│   ├── cache/
│   ├── data/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   ├── Integration/
│   └── Unit/
│
├── composer.json
├── README.md
└── .env.example
```

---

# Camadas

## Application

Contém os casos de uso da aplicação.

Responsável por coordenar o fluxo entre a interface e o domínio.

Esta camada não contém regras de negócio do PsycheAI.

### Contracts

Interfaces de marcação da camada de Aplicação (`CommandInterface`,
`ResultInterface`, `UseCaseInterface`, `ApplicationServiceInterface`),
seguindo a mesma convenção de `DomainServiceInterface`.

### DTOs

Objetos de transferência de dados, imutáveis, que expõem uma projeção
somente-leitura das entidades de domínio para fora da camada de Aplicação.

### Exceptions

Exceções da camada de Aplicação (ex.: `ComandoInvalidoException`, lançada
quando dados primitivos de um Command não satisfazem os invariantes dos
Value Objects do domínio).

### Services

Serviços de Aplicação que compõem múltiplos Use Cases em um fluxo maior
(ex.: `CicloDeObservacaoService`, que encadeia Construir Memória
Longitudinal → Detectar Recorrências → Gerar Observações).

Também inclui, desde a Sprint 9, uma Application Service por agregado —
`SujeitoApplicationService`, `SessaoApplicationService`,
`DiscursoApplicationService` e `MemoriaApplicationService` — cada uma
recebendo por injeção de dependência apenas o(s) Repositório(s) de
Domínio correspondente(s) (`PsycheAI\Domain\Repositories\*`), nunca uma
implementação concreta de Infraestrutura. Expõem `criar`, `atualizar`,
`excluir`, `buscarPorId` e `listar`, delegando a montagem/validação das
Entidades aos Use Cases já existentes e a persistência ao Repositório
injetado. `EventoDiscursivo` não é raiz de agregado — seu registro é
exposto como uma operação de `DiscursoApplicationService`
(`adicionarEvento`), e não como um serviço próprio.

Desde a Sprint 12, `MensagemApplicationService` orquestra o caso de uso
"enviar mensagem": registra a mensagem do usuário e a resposta
automática do sistema como dois `EventoDiscursivo` dentro do único
`Discurso` da conversa (criado sob demanda no primeiro envio),
reaproveitando `RegistrarDiscursoHandler`/`RegistrarEventoDiscursivoHandler`
já existentes. Depende de `UuidGeneratorInterface` e da nova porta
`RespostaAutomaticaInterface` (ambas em `Infrastructure/Contracts/`)
apenas por interface, mantendo-se alheia à implementação concreta
injetada.

Desde a Sprint 13, `LinhaDoTempoApplicationService` e
`ConsolidacaoApplicationService` expõem as duas consultas somente-leitura
da Memória Discursiva Longitudinal: a Linha do Tempo (Sessões, Discursos,
Eventos Discursivos e Memórias de um Sujeito, ordenados
cronologicamente, com filtro por tipo/período/texto e paginação) e a
Consolidação (contagem pura desses quatro tipos por Sujeito). Nenhum dos
dois interpreta o conteúdo do discurso nem toca
`DetectarRecorrencias`/`GerarObservacoes` — apenas registram e organizam
o que já existe. Ambos dependem de `SujeitoRepository`, `MemoriaRepository`
e `SessaoRepository` (este último pela nova ponte
`sujeitoIdDaSessao()`), nunca de uma implementação concreta.

Desde a Sprint 14, `ObservacaoApplicationService` expõe o Discourse
Engine: carrega o Sujeito, monta uma `MemoriaLongitudinal` transitória
(nunca persistida — usa o próprio id do Sujeito como identificador) e
delega a `CicloDeObservacaoService::executar()`, já existente desde antes
da Sprint 7 e inalterado por esta Sprint. Mesmo padrão de recálculo em
memória de `LinhaDoTempoApplicationService`/`ConsolidacaoApplicationService`
— nenhuma `Recorrencia`/`Observacao` é gravada em repositório.

### UseCases

Cada caso de uso possui sua própria pasta com um `Command`, um `Handler`
e um `Result`.

---

## Domain

Representa o núcleo do sistema.

Contém exclusivamente as regras de negócio e os conceitos do domínio.

É a única camada que não depende das demais.

### Aggregates

Define os agregados do domínio e suas raízes.

---

### Contracts

Contém as interfaces oficiais do domínio.

Os contratos definem comportamentos esperados sem especificar implementações.

As implementações pertencem às camadas de Aplicação ou Infraestrutura.

---

### Entities

Entidades com identidade própria.

---

### Events

Eventos de domínio.

---

### Exceptions

Exceções específicas do domínio.

---

### Repositories

Interfaces dos repositórios do domínio.

Nenhum acesso ao banco de dados deve ser implementado nesta camada.

---

### Services

Serviços de domínio.

Responsáveis por comportamentos que envolvem múltiplas entidades.

---

### Specifications

Regras reutilizáveis de validação e consulta do domínio.

---

### ValueObjects

Objetos imutáveis definidos exclusivamente por seus valores.

---

## Infrastructure

Implementa todos os detalhes técnicos necessários ao funcionamento do sistema.

Inclui:

- persistência;
- banco de dados;
- provedores externos;
- logs;
- implementações de contratos;
- integrações.

Nenhuma regra de negócio pertence à infraestrutura.

### Contracts

Portas (interfaces) que isolam o núcleo do sistema (Domínio e Aplicação) de
qualquer tecnologia externa concreta: `ClockInterface`, `LoggerInterface`,
`PersistenceInterface`, `StorageInterface`, `UuidGeneratorInterface`,
`TransactionInterface`, `EventDispatcherInterface`, `MessageBusInterface`,
`TranscriptionInterface` e `LLMInterface`, além dos DTOs de entrada/saída
das portas de IA em `Contracts/DTOs/` (`LLMRequestDTO`, `LLMResponseDTO`,
`TranscriptionResultDTO`).

A maioria destas interfaces ainda não possui implementação concreta — cada
uma será implementada por um adaptador nas pastas abaixo apenas quando a
tecnologia correspondente for integrada. A Sprint 12 acrescentou
`RespostaAutomaticaInterface`, porta dedicada à resposta automática do
sistema numa conversa (`responder(string $mensagemUsuario): string`),
propositalmente separada de `LLMInterface` — que pressupõe negociação
real com um provedor de LLM, ainda não implementada — para que sua única
implementação desta Sprint seja honestamente descrita como uma resposta
fixa temporária, e não uma chamada de IA.

### Persistence

Contém a primeira implementação concreta de persistência do sistema, em
`Persistence/SQLite/`:

- `Connection.php`: conexão PDO com o banco SQLite, criando diretório e
  arquivo automaticamente quando inexistentes e habilitando
  `PRAGMA foreign_keys`.
- `TransactionManager.php`: implementação de `TransactionInterface` sobre
  transações nativas do PDO.
- `Migrations/`: uma classe `Migration` por tabela (`CreateSujeitosTable`,
  `CreateSessoesTable`, `CreateDiscursosTable`,
  `CreateEventosDiscursivosTable`, `CreateMemoriasLongitudinaisTable`,
  `CreateMemoriaSessoesTable`) e `MigrationRunner`, que as aplica de forma
  ordenada e idempotente, registrando o histórico em `schema_migrations`.
- `Repositories/`: adaptadores `SQLiteSujeitoRepository`,
  `SQLiteSessaoRepository`, `SQLiteDiscursoRepository` e
  `SQLiteMemoriaRepository`, que implementam os respectivos Repositórios do
  Domínio usando PDO puro. `SessaoMapper`, `DiscursoMapper` e
  `EventoDiscursivoMapper` são hidratadores internos, compartilhados entre
  os repositórios para persistir/carregar os agregados em cascata
  (Sujeito → Sessão → Discurso → Evento Discursivo). Desde a Sprint 13,
  `SessaoRepository::sujeitoIdDaSessao()` (via `SessaoMapper::sujeitoIdDe()`)
  expõe o vínculo persistido Sessao → Sujeito, no mesmo padrão de
  `DiscursoRepository::sessaoIdDoDiscurso()` (Sprint 11B) — necessário para
  localizar as Memórias Longitudinais de um Sujeito, já que
  `MemoriaLongitudinal` só guarda as sessões que consolida.

### Providers

Raiz de composição da aplicação. `ApplicationServiceProvider` monta a
conexão SQLite, aplica as migrations pendentes e injeta os Repositórios
concretos (`Persistence/SQLite/Repositories/*`) nas Application Services
correspondentes — é o único ponto do sistema autorizado a conhecer
Application e Infrastructure simultaneamente.

### Logging / Messaging / Storage / AI / Clock / UUID

Pastas reservadas para as futuras implementações concretas de cada
respectivo contrato. Permanecem vazias até que a tecnologia correspondente
seja efetivamente integrada — exceto `AI/` e `UUID/`, que a Sprint 12
passou a ocupar:

- `AI/RespostaFixaService.php`: implementação temporária de
  `RespostaAutomaticaInterface` — devolve sempre a mesma resposta fixa
  ("Recebi sua mensagem. Continue falando livremente."), independente do
  conteúdo recebido. Isolada para substituição futura pelos motores
  Freud e Lacan, que implementarão o mesmo contrato sem exigir mudanças
  em `MensagemApplicationService` nem em qualquer camada acima dela.
- `UUID/RandomUuidGenerator.php`: implementação de
  `UuidGeneratorInterface` via UUID v4 aleatório (`random_bytes`), sem
  dependências externas.

---

## Presentation

Responsável pela comunicação com usuários e sistemas externos.

Contém:

- controllers;
- requests;
- responses;
- views.

Não implementa regras de negócio.

Desde a Sprint 12, `MensagemController` (com `EnviarMensagemRequest` e
`MensagemEnviadaResponse`) expõe `POST /sessions/{id}/messages` sobre
`MensagemApplicationService` — único endpoint novo desta Sprint, criado
porque "enviar mensagem" (registrar a fala do usuário e devolver a
resposta automática numa única chamada) é um caso de uso que não existia
como composição dos endpoints CRUD já publicados.

Desde a Sprint 13, `LinhaDoTempoController` expõe os dois únicos
endpoints novos desta Sprint — `GET /subjects/{id}/timeline` (com
`ConsultarLinhaDoTempoRequest`, que valida os parâmetros de query string
sempre opcionais: `tipo`, `de`, `ate`, `q`, `pagina`, `porPagina`) e
`GET /subjects/{id}/consolidation` — sobre `LinhaDoTempoApplicationService`
e `ConsolidacaoApplicationService`. `Presentation\Http\Request::queries()`
foi acrescentado para expor a query string inteira aos Requests de
leitura, e `HttpRequestData` ganhou variantes opcionais
(`opcionalString`, `opcionalData`, `opcionalInteiroPositivo`) para
parâmetros que, ao contrário do corpo de escrita, nunca são
obrigatórios.

Desde a Sprint 14, `ObservacaoController` expõe o único endpoint novo
desta Sprint — `GET /subjects/{id}/observations` (com
`ConsultarObservacoesRequest`, parâmetro de query opcional
`minimoDeRecorrencia`) — sobre `ObservacaoApplicationService`, devolvendo
as Recorrências e Observações recalculadas via `ObservacaoResponse`.

### Web

Interface web (HTML) do PsycheAI, construída na Sprint 11A de forma
inteiramente independente da API REST — inclusive isolada em namespace
próprio (`PsycheAI\Presentation\Web`) para não colidir com os
Controllers/Requests/Responses/Http já existentes na raiz de
`Presentation/`, que pertencem à API REST.

- `Http/`: `Request`, `Response`, `Router` (com handler de "não
  encontrado" configurável) e `ViewRenderer` (renderiza uma view PHP
  isolada e, opcionalmente, a encaixa no layout principal).
- `Client/`: `HttpClientInterface` — porta de comunicação com a API
  REST (Sprint 10) — e `ApiHttpClient`, sua implementação de produção
  desde a Sprint 11B, que fala HTTP de verdade (cURL) com a API REST e
  traduz status HTTP reais para os quatro tipos de erro (`ErrorType`).
  `ApiResponse` é o envelope de retorno.
- `ViewModels/`: `SujeitoViewModel`, `SessaoViewModel`,
  `DiscursoViewModel`, `MemoriaViewModel`, `EventoDiscursivoViewModel`,
  `DashboardViewModel` e, desde a Sprint 12, `MensagemViewModel` —
  projeções somente-leitura construídas a partir do array devolvido pelo
  Cliente HTTP (`fromArray`/`fromArrayList`), nunca a partir de uma
  Entidade de Domínio. `MensagemViewModel` também expõe
  `historicoDaSessao()`, que filtra e ordena os eventos de `GET /events`
  por `sessaoId`/`posicao` para montar o histórico de uma conversa sem
  exigir um endpoint dedicado só para leitura. Desde a Sprint 13,
  `LinhaDoTempoItemViewModel` (com `rotulo()`, `resumo()` e
  `rotaDetalhe()`, todos derivados apenas da estrutura — tipo e
  contagens — nunca de uma leitura sobre o conteúdo) e
  `ConsolidacaoViewModel` projetam as respostas de
  `GET /subjects/{id}/timeline` e `GET /subjects/{id}/consolidation`.
  Desde a Sprint 14, `RecorrenciaViewModel` e `ObservacaoViewModel`
  projetam a resposta de `GET /subjects/{id}/observations`.
- `Navigation/`: `NavigationItem` e `NavigationMenu`, fonte única das
  sete seções do menu lateral (Dashboard, Conversa, Sujeitos, Sessões,
  Discursos, Memórias, Eventos Discursivos), compartilhada entre a
  Sidebar e `Routes.php`.
- `Components/`: componentes reutilizáveis orientados a dados —
  `TableComponent` (com estado vazio automático via
  `EmptyStateComponent`), `CardComponent`, `ButtonComponent`,
  `FormComponent`, `ModalComponent`, `AlertComponent` e
  `LoadingIndicatorComponent` — todos escapando HTML através do
  utilitário `Html`.
- `Errors/`: `ErrorType` (enum fechado com os tipos exigidos: comunicação,
  não encontrado, validação, interno e, desde a Sprint 13, timeout),
  `ErrorViewModel` e `ErrorViewModelFactory`. `ApiHttpClient` distingue
  timeout de falha de conexão por `CURLINFO_CONNECT_TIME`: maior que
  zero significa que o servidor aceitou a conexão e só demorou a
  responder (`ErrorType::TIMEOUT`, HTTP 504); igual a zero significa que
  a conexão em si falhou (`ErrorType::COMUNICACAO`, como antes) — ambos
  os casos disparam `curl_errno() === CURLE_OPERATION_TIMEDOUT`, por
  isso não bastava olhar apenas o código de erro do cURL.
- `Controllers/`: `DashboardController`, `AbstractResourceController`
  (ceremonial comum às cinco páginas de listagem) e suas cinco
  especializações (`SujeitosController`, `SessoesController`,
  `DiscursosController`, `MemoriasController`,
  `EventosDiscursivosController`), além de `ErrorController`.
  `SujeitosController` também expõe `novo`/`store`, único ponto que
  aplica validação básica de entrada (campo obrigatório), permitida
  pela Arquitetura em Camadas. Desde a Sprint 12, `ConversaController`
  implementa a tela de Conversa: inicia uma Sessao automaticamente sob
  um Sujeito "Visitante" padrão (não há autenticação nesta fase),
  mantém seu id em `$_SESSION` nativa do PHP entre requisições da mesma
  aba, envia mensagens através de `POST /sessions/{id}/messages` e se
  recupera sozinho quando a Sessao referenciada não existe mais
  (recria uma nova e reenvia a mensagem uma única vez). Desde a Sprint
  13, `HistoricoSujeitoController` implementa a tela de Histórico do
  Sujeito: combina `GET /subjects/{id}`, `GET /subjects/{id}/timeline` e
  `GET /subjects/{id}/consolidation` em uma única página — Linha do
  Tempo com filtro por tipo/período/texto e paginação, mais a
  Consolidação. Também não estende `AbstractResourceController`/
  `AbstractCrudResourceController`, pelo mesmo motivo de
  `ConversaController`: o ceremonial genérico não cobre uma página que
  combina três respostas de uma vez. Desde a Sprint 14,
  `ObservacoesSujeitoController` implementa a tela do Discourse Engine:
  combina `GET /subjects/{id}` e `GET /subjects/{id}/observations` em uma
  página própria (não embutida na de Histórico) — separação deliberada
  porque a Sprint 16 vai estendê-la com os rótulos do Motor Lacan lado a
  lado, evitando reabrir `HistoricoSujeitoController` para isso.
- `Views/`: `layout.php` (com `partials/header.php` e
  `partials/sidebar.php`), uma view por seção, `carregando.php`,
  `errors/error.php` e, desde a Sprint 12, `conversa/index.php` — monta
  o histórico com `TableComponent`, a caixa de mensagem com
  `FormComponent` (textarea) e eventuais alertas com `AlertComponent`,
  reaproveitando exclusivamente os Componentes da Sprint 11A. O único
  HTML fora desses Componentes é um `<script>` inline mínimo que rola o
  histórico até a última mensagem ao carregar a página — não há
  WebSocket nem polling, a atualização acontece porque a própria
  resposta ao POST de envio já é a página recarregada com o histórico
  atualizado. Desde a Sprint 13, `historico/mostrar.php` monta a tela de
  Histórico do Sujeito com `CardComponent` (contagens da Consolidação),
  um formulário de filtro (tipo/período/texto, GET) e `TableComponent`
  com paginação anterior/próxima — reaproveitando os Componentes já
  existentes, com um link "Ver Histórico" adicionado em
  `sujeitos/mostrar.php`. Desde a Sprint 14, `observacoes/mostrar.php`
  lista as Recorrências e Observações do Discourse Engine em duas
  `TableComponent`, com um link "Ver Observações" adicionado em
  `historico/mostrar.php`.
- `Routes.php`: registra todas as rotas internas sobre um `Router`,
  reaproveitando os mesmos caminhos do `NavigationMenu`.
- `public/web/index.php`: front controller da interface web,
  independente de `public/index.php` (API REST). Desde a Sprint 12,
  chama `session_start()` antes de despachar a requisição, para que
  `ConversaController` consiga associar uma conversa em andamento à
  mesma aba do navegador.

Desde a Sprint 11B, toda a comunicação passa por `ApiHttpClient`, que
fala HTTP de verdade com a API REST — nenhuma rota depende de dados
mockados.

---

# Storage

Armazena dados produzidos durante a execução da aplicação.

```text
storage/
├── cache/
├── data/
└── logs/
```

---

# Tests

Organização dos testes automatizados.

```text
tests/
├── Unit/
├── Integration/
└── Feature/
```

---

# Regras Arquiteturais

- O Domínio não depende de nenhuma outra camada.
- A Aplicação depende apenas do Domínio.
- A Infraestrutura implementa contratos definidos no Domínio.
- A Apresentação comunica-se apenas com a Aplicação.
- Nenhuma regra de negócio pertence à Infraestrutura.
- Interfaces pertencem ao Domínio.
- Implementações pertencem à Infraestrutura ou à Aplicação.
- Toda comunicação entre camadas deve respeitar as dependências arquiteturais.

---

# Objetivo

Esta estrutura constitui a organização física oficial do PsycheAI e deverá ser utilizada durante toda a implementação do sistema.

Toda evolução da arquitetura deverá preservar esta organização e os princípios definidos neste documento.