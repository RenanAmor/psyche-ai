# Estrutura de Pastas — PsycheAI

> Versão 1.11

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
│           ├── Security/
│           ├── ViewModels/
│           ├── Views/
│           └── Routes.php
│
├── bin/
│   └── criar-analista.php
├── config/
├── docs/
├── public/
│   └── web/
│       ├── index.php
│       └── assets/
│           └── js/
│               └── grafo-circuito.js
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

Desde a revisão pós-Sprint 16 (docs/Roadmap.md), `ObservacaoApplicationService::consultarCircuito()`
expõe o circuito/trajeto de cada Recorrencia: usa o resultado já
filtrado (limiar ≥2) de `CicloDeObservacaoService::executar()` como
única fonte de quais Recorrencias existem, cruzando com
`DetectorRecorrencias::detectarCircuito()` através da nova tríade de
Use Case `UseCases/DetectarCircuitoRecorrencia/` (`Command`, `Handler`,
`Result`) — o `Result` expõe `array<string, OcorrenciaRecorrencia[]>`
por id de Recorrencia, mesmo formato já usado por
`ReclassificadorLacaniano::reclassificar()`.

Desde a revisão do Motor Freud (2026-07-30, docs/Roadmap.md), a nova
tríade `UseCases/ClassificarFormacaoFreudiana/` (`Command`, `Handler`,
`Result`) expõe a classificação estrutural de um conteúdo discursivo em
`TipoFormacaoFreudiana`. Diferente dos demais Handlers desta pasta, o
construtor de `ClassificarFormacaoFreudianaHandler` não tem valor
default para `ClassificadorEstruturalInterface` — a implementação
concreta fala com uma API externa e precisa de credencial, então
instanciá-la implicitamente esconderia essa dependência. Ainda sem
wiring em `ApplicationServiceProvider`/endpoint — ver Infrastructure
abaixo.

Desde a Sprint 18 (Plataforma), `AnalistaApplicationService` (`criar`,
`buscarPorEmail`, `autenticar`) segue o mesmo desenho — depende só de
`AnalistaRepository` e `UuidGeneratorInterface` — usando a nova tríade
`UseCases/CadastrarAnalista/` para a construção validada da Entidade.

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

Desde a Sprint 18 (Plataforma), `Analista` é a primeira Entidade que não
representa um conceito do Modelo Computacional do Discurso — é a conta de
acesso ao sistema (analista/administrador), sem nenhum significado
psicanalítico, ao lado de Sujeito/Sessao/Discurso/EventoDiscursivo.
`verificarSenha()` (`password_verify`) vive na própria Entidade.

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

Desde a Sprint 18, `AnalistaRepository` (`findById`, `findByEmail`,
`save`) segue o mesmo desenho de `SujeitoRepository` — implementado por
`SQLiteAnalistaRepository`.

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

Desde a revisão pós-Sprint 16, `OcorrenciaRecorrencia` (`sessaoId`,
`discursoId`, `eventoId`, `momento`, `posicao`) representa uma única
ocorrência de um conteúdo normalizado dentro da Memória Longitudinal —
usado por `DetectorRecorrencias::detectarCircuito()` para reconstruir o
circuito/trajeto de uma Recorrencia através das Sessões.

Desde a revisão do Motor Freud (2026-07-30), `TipoFormacaoFreudiana` é
um enum nativo (`^8.2`) — vocabulário fechado de
[Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais):
`AtoFalho`, `Chiste`, `Sonho`, `Repeticao`, `FormacaoDeCompromisso` e
`NaoClassificado` (fallback determinístico). Puro vocabulário, sem
lógica nem dependência — mesmo precedente de `Presentation/Web/Errors/ErrorType`,
mas o primeiro enum na camada de Domínio.

Desde a Sprint 18, `Email` valida formato via `FILTER_VALIDATE_EMAIL` —
usado por `Analista`, mesmo padrão de validação simples de
`Identificador`/`NomeSujeito`.

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
real com um provedor de LLM, ainda não implementada até a Sprint 17 —
para que sua única implementação desta Sprint fosse honestamente
descrita como uma resposta fixa temporária, e não uma chamada de IA.

Desde a revisão do Motor Freud (2026-07-30), `LLMInterface` deixa de
estar sem implementação (ver `AI/AnthropicLLMService` abaixo) e uma
nova porta é acrescentada: `ClassificadorEstruturalInterface`
(`classificar(string): TipoFormacaoFreudiana`) — o contrato que a
Application conhece para classificação estrutural, nunca a
implementação concreta.

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
  `CreateMemoriaSessoesTable`, `AddCriadoEmToEventosDiscursivosTable`,
  `CreateAnalistasTable` desde a Sprint 18) e `MigrationRunner`, que as
  aplica de forma ordenada e idempotente, registrando o histórico em
  `schema_migrations`.
- `Repositories/`: adaptadores `SQLiteSujeitoRepository`,
  `SQLiteSessaoRepository`, `SQLiteDiscursoRepository`,
  `SQLiteMemoriaRepository` e, desde a Sprint 18, `SQLiteAnalistaRepository`
  — que implementam os respectivos Repositórios do
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
  conteúdo recebido. Deixou de ser o binding padrão na Sprint 17, mas
  continua em uso como resposta de reserva de
  `RespostaEcoRecorrenciaService`.
- `AI/RespostaEcoRecorrenciaService.php`: binding padrão de
  `RespostaAutomaticaInterface` desde a Sprint 17 — reaproveita
  `ConstruirMemoriaLongitudinalHandler` + `DetectarRecorrenciasHandler`
  (os mesmos Use Cases do Discourse Engine/Motor Freud, Sprints 14-15)
  para checar se o conteúdo normalizado da mensagem recebida já apareceu
  antes no histórico persistido do Sujeito; se sim, devolve uma
  pergunta-eco que só nomeia a repetição e convida a continuar falando
  (nunca uma afirmação ou hipótese sobre a causa — Regra 7,
  `docs/Regras-Dominio.md`); senão, delega a `RespostaFixaService`.
  Único ponto em que os motores das Sprints 15-16 passam a tocar a
  conversa — decisão fechada com o usuário ao planejar a Sprint 17.
- `UUID/RandomUuidGenerator.php`: implementação de
  `UuidGeneratorInterface` via UUID v4 aleatório (`random_bytes`), sem
  dependências externas.
- `AI/AnthropicLLMService.php`: desde a revisão do Motor Freud
  (2026-07-30), **primeira implementação concreta de `LLMInterface`**
  no projeto (a interface existe desde a Sprint 1). Usa o SDK oficial
  `anthropic-ai/sdk` (primeira dependência de runtime do `composer.json`
  — até aqui só listava `php`) e o modelo `claude-haiku-4-5`. Adapter
  fino: só fala com a API e devolve o texto bruto da resposta — não
  valida contra nenhum vocabulário de domínio.
- `AI/ClassificadorFreudianoLLM.php`: implementação de
  `ClassificadorEstruturalInterface` desde a mesma revisão. Monta o
  prompt grounded em `Ontologia-Freud.md §3`, chama `LLMInterface` com
  `output_config.format` restrito a um enum fechado de 6 strings (sem
  campo de "justificativa" no schema), e valida a resposta contra
  `TipoFormacaoFreudiana::tryFrom()` — qualquer coisa fora do esperado
  (JSON inválido, valor desconhecido, falha de rede/API) cai em
  `NaoClassificado`, nunca um valor solto.

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

Desde a Sprint 18 (Plataforma), `AutenticacaoController` expõe o primeiro
endpoint de autenticação da API — `POST /auth/login` (com
`AutenticarAnalistaRequest`, `email`/`senha` obrigatórios) — sobre
`AnalistaApplicationService::autenticar()`, devolvendo `{id, email}` via
`AnalistaResponse` (200) ou 401 (`HttpException::naoAutorizado()`, novo)
quando a credencial é inválida. Sem endpoint de cadastro exposto por
HTTP — ver `bin/criar-analista.php` na raiz do projeto.

### Web

Interface web (HTML) do PsycheAI, construída na Sprint 11A de forma
inteiramente independente da API REST — inclusive isolada em namespace
próprio (`PsycheAI\Presentation\Web`) para não colidir com os
Controllers/Requests/Responses/Http já existentes na raiz de
`Presentation/`, que pertencem à API REST.

- `Http/`: `Request`, `Response`, `Router` (com handler de "não
  encontrado" configurável) e `ViewRenderer` (renderiza uma view PHP
  isolada e, opcionalmente, a encaixa no layout principal). Desde a
  Sprint 17, `Response::json()` devolve uma variante JSON (usada só pelo
  fetch() de atualização incremental da Conversa) ao lado da `Response`
  HTML padrão. Desde a revisão pós-Sprint 16, `Response::redirecionar()`
  devolve um 302 com o cabeçalho `Location`, usado pelo Portão do
  Analista.
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
  projetam a resposta de `GET /subjects/{id}/observations`. Desde a
  Sprint 19, `GrafoCircuitoViewModel` reformata
  `CircuitoRecorrenciaViewModel[]` (o mesmo dado do circuito/trajeto) em
  nós/arestas para o grafo desenhado em D3 no navegador.
- `Navigation/`: `NavigationItem` e `NavigationMenu`, fonte única das
  sete seções do menu lateral (Dashboard, Conversa, Sujeitos, Sessões,
  Discursos, Memórias, Eventos Discursivos), compartilhada entre a
  Sidebar e `Routes.php`.
- `Security/`: `PortaoDeAnalista` — `estaAutenticado()`,
  `abrirSessao(string $analistaId)`, `analistaId()`, `sair()` e
  `proteger(callable $handler): Closure`, que embrulha um handler para
  redirecionar (302) a `/entrar` quando a sessão
  (`psyche_analista_autenticado`) não está autenticada. Sem entidade de
  Domínio nem persistência própria — continua um portão de sessão
  simples. Até a Sprint 18, `autenticar(string $senha)` comparava com
  `getenv('PSYCHEAI_SENHA_ANALISTA')` via `hash_equals()`; desde a
  Sprint 18 (Plataforma), a verificação de credencial saiu daqui —
  `AutenticacaoAnalistaController` chama `POST /auth/login` na API REST
  (contas reais, `AnalistaApplicationService`) e só então chama
  `abrirSessao()`.
- `Components/`: componentes reutilizáveis orientados a dados —
  `TableComponent` (com estado vazio automático via
  `EmptyStateComponent`), `CardComponent`, `ButtonComponent`,
  `FormComponent`, `ModalComponent`, `AlertComponent` e
  `LoadingIndicatorComponent` — todos escapando HTML através do
  utilitário `Html`. Desde a Sprint 17, `ConversaAreaComponent` combina
  `AlertComponent` + `TableComponent` no bloco de alerta/histórico da
  Conversa, para que o mesmo HTML sirva tanto a página cheia quanto o
  fragmento JSON de `POST /conversa/mensagens`. Desde a revisão
  pós-Sprint 16, `CircuitoTrajetoComponent` lista, por Recorrencia, o
  trajeto cronológico "Sessão {data} → Sessão {data} → …".
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
  implementa a tela de Conversa: inicia uma Sessao automaticamente,
  mantém seu id em `$_SESSION` nativa do PHP entre requisições da mesma
  aba, envia mensagens através de `POST /sessions/{id}/messages` e se
  recupera sozinho quando a Sessao referenciada não existe mais
  (recria uma nova e reenvia a mensagem uma única vez). A Sprint 17
  substitui o Sujeito "visitante" fixo compartilhado por todo mundo por
  uma identidade pseudônima por navegador: um cookie de longa duração
  (`psyche_pessoa_id`, 1 ano, `HttpOnly`) é gerado na primeira vez que
  uma nova Sessao precisa ser criada e reaproveitado nas visitas
  seguintes, mesmo depois que `$_SESSION` expira — ou seja, cada
  navegador acumula seu próprio Sujeito ao longo de várias Sessões
  (visitas), isolado dos demais, sem exigir login (isso continua
  reservado para a Sprint 18). A Sprint 17 também acrescenta o método
  `mensagens()` (rota `POST /conversa/mensagens`): mesma lógica de
  `enviar()`, mas devolve só o fragmento HTML de
  `ConversaAreaComponent` em JSON, para o fetch() de
  `conversa/index.php` atualizar o histórico sem recarregar a página —
  `enviar()`/`POST /conversa/enviar` continuam existindo tal como antes,
  como caminho funcional sem JavaScript. Desde a Sprint
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
  lado, evitando reabrir `HistoricoSujeitoController` para isso. Desde a
  revisão pós-Sprint 16, o mesmo Controller também combina
  `GET /subjects/{id}/observations/circuito`, exibindo o circuito/trajeto
  de cada Recorrencia. Desde a Sprint 19, o mesmo Controller ganha
  `grafoCircuito()` (rota `GET
  /sujeitos/{id}/observacoes/grafo-circuito`), que serve o circuito
  reformatado em nós/arestas (JSON) para o grafo em D3 da view. Também
  desde a revisão pós-Sprint 16,
  `AutenticacaoAnalistaController` implementa a tela de
  entrada/saída do Portão do Analista (`GET/POST /entrar`,
  `POST /sair`) — não protegida por `PortaoDeAnalista::proteger()`, por
  ser justamente a porta de acesso a ele. Desde a Sprint 18, recebe
  `HttpClientInterface` (mesmo cliente de todo Controller Web) e chama
  `POST /auth/login` para verificar a credencial, em vez de decidir isso
  localmente.
- `Views/`: `layout.php` (com `partials/header.php` e
  `partials/sidebar.php`), uma view por seção, `carregando.php`,
  `errors/error.php` e, desde a Sprint 12, `conversa/index.php` — monta
  a caixa de mensagem com `FormComponent` (textarea) em torno de um
  `<div id="conversa-area">`, cujo conteúdo (alerta + histórico) vem
  pronto de `ConversaAreaComponent`. Até a Sprint 16 a atualização
  acontecia porque a própria resposta ao POST de envio já era a página
  recarregada; a partir da Sprint 17, um `<script>` inline (ainda sem
  bundler/framework — só `fetch()` nativo, no mesmo espírito minimalista
  do restante do projeto) intercepta o submit do formulário, envia
  `POST /conversa/mensagens` e troca só o `innerHTML` de
  `#conversa-area` pelo fragmento devolvido, sem recarregar a página
  inteira. Não há WebSocket, polling nem SSE — a resposta do sistema é
  sempre determinística e instantânea (Regra 1-10,
  `docs/Regras-Dominio.md`), então não há nada para transmitir em
  chunks; o `fetch()` é só uma troca de HTML pronto por HTML pronto. Se
  `fetch()` falhar (ou JavaScript estiver desabilitado), o formulário
  cai de volta no POST clássico a `/conversa/enviar` — a Sprint 17 não
  introduz nenhum caminho que dependa de JavaScript para funcionar.
  Desde a Sprint 13, `historico/mostrar.php` monta a tela de
  Histórico do Sujeito com `CardComponent` (contagens da Consolidação),
  um formulário de filtro (tipo/período/texto, GET) e `TableComponent`
  com paginação anterior/próxima — reaproveitando os Componentes já
  existentes, com um link "Ver Histórico" adicionado em
  `sujeitos/mostrar.php`. Desde a Sprint 14, `observacoes/mostrar.php`
  lista as Recorrências e Observações do Discourse Engine em duas
  `TableComponent`, com um link "Ver Observações" adicionado em
  `historico/mostrar.php`. Desde a revisão pós-Sprint 16, a mesma view
  ganha uma terceira seção com `CircuitoTrajetoComponent`. Desde a
  Sprint 19, a mesma view acrescenta, quando há circuitos, um container
  para o grafo D3 (`<div id="grafo-circuito">`) e os `<script>` do CDN
  do D3 e de `grafo-circuito.js` — a lista de `CircuitoTrajetoComponent`
  permanece como fallback textual sempre renderizado pelo servidor.
  Também desde a revisão pós-Sprint 16, `autenticacao/entrar.php` monta
  o formulário de senha do Portão do Analista com
  `FormComponent`/`AlertComponent`.
- `Routes.php`: registra todas as rotas internas sobre um `Router`,
  reaproveitando os mesmos caminhos do `NavigationMenu`. Desde a revisão
  pós-Sprint 16, todo handler de coleta/análise (`/`, `/sujeitos*`,
  `/sessoes*`, `/discursos*`, `/memorias*`, `/eventos-discursivos`) é
  envolvido por `Security\PortaoDeAnalista::proteger()` no momento do
  registro; `/conversa*`, `/erros/*`, `/entrar` e `/sair` ficam de fora.
  Desde a Sprint 19, `/sujeitos/{id}/observacoes/grafo-circuito` (JSON do
  grafo, consumido pelo `fetch()` de `grafo-circuito.js`) entra no mesmo
  grupo protegido.
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