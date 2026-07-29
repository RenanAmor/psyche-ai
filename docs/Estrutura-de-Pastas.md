# Estrutura de Pastas — PsycheAI

> Versão 1.6

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

Nenhuma destas interfaces possui implementação concreta nesta fase — cada
uma será implementada por um adaptador nas pastas abaixo apenas quando a
tecnologia correspondente for integrada.

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
  (Sujeito → Sessão → Discurso → Evento Discursivo).

### Providers

Raiz de composição da aplicação. `ApplicationServiceProvider` monta a
conexão SQLite, aplica as migrations pendentes e injeta os Repositórios
concretos (`Persistence/SQLite/Repositories/*`) nas Application Services
correspondentes — é o único ponto do sistema autorizado a conhecer
Application e Infrastructure simultaneamente.

### Logging / Messaging / Storage / AI / Clock / UUID

Pastas reservadas para as futuras implementações concretas de cada
respectivo contrato. Permanecem vazias até que a tecnologia correspondente
seja efetivamente integrada.

---

## Presentation

Responsável pela comunicação com usuários e sistemas externos.

Contém:

- controllers;
- requests;
- responses;
- views.

Não implementa regras de negócio.

### Web

Interface web (HTML) do PsycheAI, construída na Sprint 11A de forma
inteiramente independente da API REST — inclusive isolada em namespace
próprio (`PsycheAI\Presentation\Web`) para não colidir com os
Controllers/Requests/Responses/Http já existentes na raiz de
`Presentation/`, que pertencem à API REST.

- `Http/`: `Request`, `Response`, `Router` (com handler de "não
  encontrado" configurável) e `ViewRenderer` (renderiza uma view PHP
  isolada e, opcionalmente, a encaixa no layout principal).
- `Client/`: `HttpClientInterface` — porta de comunicação com a futura
  API REST — e `MockApiHttpClient`, sua única implementação nesta
  Sprint, que devolve dados fixos simulados e permite forçar qualquer
  um dos quatro tipos de erro (`ErrorType`) para fins de teste e
  demonstração. `ApiResponse` é o envelope de retorno.
- `ViewModels/`: `SujeitoViewModel`, `SessaoViewModel`,
  `DiscursoViewModel`, `MemoriaViewModel`, `EventoDiscursivoViewModel` e
  `DashboardViewModel` — projeções somente-leitura construídas a partir
  do array devolvido pelo Cliente HTTP (`fromArray`/`fromArrayList`),
  nunca a partir de uma Entidade de Domínio.
- `Navigation/`: `NavigationItem` e `NavigationMenu`, fonte única das
  seis seções do menu lateral (Dashboard, Sujeitos, Sessões, Discursos,
  Memórias, Eventos Discursivos), compartilhada entre a Sidebar e
  `Routes.php`.
- `Components/`: componentes reutilizáveis orientados a dados —
  `TableComponent` (com estado vazio automático via
  `EmptyStateComponent`), `CardComponent`, `ButtonComponent`,
  `FormComponent`, `ModalComponent`, `AlertComponent` e
  `LoadingIndicatorComponent` — todos escapando HTML através do
  utilitário `Html`.
- `Errors/`: `ErrorType` (enum fechado com os quatro tipos exigidos:
  comunicação, não encontrado, validação, interno), `ErrorViewModel` e
  `ErrorViewModelFactory`.
- `Controllers/`: `DashboardController`, `AbstractResourceController`
  (ceremonial comum às cinco páginas de listagem) e suas cinco
  especializações (`SujeitosController`, `SessoesController`,
  `DiscursosController`, `MemoriasController`,
  `EventosDiscursivosController`), além de `ErrorController`.
  `SujeitosController` também expõe `novo`/`store`, único ponto que
  aplica validação básica de entrada (campo obrigatório), permitida
  pela Arquitetura em Camadas.
- `Views/`: `layout.php` (com `partials/header.php` e
  `partials/sidebar.php`), uma view por seção, `carregando.php` e
  `errors/error.php`.
- `Routes.php`: registra todas as rotas internas sobre um `Router`,
  reaproveitando os mesmos caminhos do `NavigationMenu`.
- `public/web/index.php`: front controller da interface web,
  independente de `public/index.php` (API REST).

Nesta Sprint toda a comunicação é mockada: nenhuma rota consome a API
REST, acessa SQLite, Application Services ou Domain.

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