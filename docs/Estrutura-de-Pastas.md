# Estrutura de Pastas — PsycheAI

> Versão 1.1

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
│   │   ├── Commands/
│   │   ├── DTO/
│   │   ├── Queries/
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
│   │   ├── Database/
│   │   ├── Logging/
│   │   ├── Persistence/
│   │   ├── Providers/
│   │   └── Repositories/
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