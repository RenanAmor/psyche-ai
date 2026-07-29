# Estrutura de Pastas — PsycheAI

> Versão 1.3

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
│       └── Views/
│
├── config/
├── docs/
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

### Persistence / Logging / Messaging / Storage / AI / Clock / UUID

Pastas reservadas para as futuras implementações concretas de cada
respectivo contrato (ex.: um adaptador SQLite implementará
`PersistenceInterface` dentro de `Persistence/`). Permanecem vazias até que
a tecnologia correspondente seja efetivamente integrada.

---

## Presentation

Responsável pela comunicação com usuários e sistemas externos.

Contém:

- controllers;
- requests;
- responses;
- views.

Não implementa regras de negócio.

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