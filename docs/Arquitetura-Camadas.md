# Arquitetura em Camadas — PsycheAI

> Versão 1.2

Este documento define a arquitetura em camadas do PsycheAI.

A arquitetura separa responsabilidades e garante independência entre domínio, aplicação e infraestrutura.

---

# Visão Geral

```text
Apresentação
        │
        ▼
Aplicação
        │
        ▼
Domínio
        │
        ▼
Infraestrutura
```

---

# Camada de Apresentação

Responsável pela interação com usuários e sistemas externos.

Responsabilidades:

- receber requisições;
- apresentar respostas;
- validar dados básicos de entrada;
- nunca conter regras de negócio.

## Interface Web

Desde a Sprint 11A, a Apresentação inclui uma interface web
(`Presentation/Web/`) construída de forma inteiramente independente da
API REST, para não bloquear sua evolução em paralelo. Toda comunicação
passa por `HttpClientInterface`, cuja única implementação nesta fase é
`MockApiHttpClient` — nenhuma rota web acessa a API REST, SQLite,
Application Services ou Domain. Quando a API REST estiver disponível,
apenas uma nova implementação de `HttpClientInterface` precisará ser
escrita; Controllers, ViewModels, Componentes e Views permanecem
inalterados.

---

# Camada de Aplicação

Responsável por coordenar os casos de uso.

Responsabilidades:

- executar casos de uso;
- controlar o fluxo da aplicação;
- utilizar serviços do domínio;
- não conter lógica clínica.

---

# Camada de Domínio

Representa o núcleo do PsycheAI.

Contém:

- entidades;
- value objects;
- eventos de domínio;
- agregados;
- serviços de domínio;
- regras de negócio.

Esta camada não depende de nenhuma outra.

---

# Camada de Infraestrutura

Responsável pelos detalhes técnicos.

Contém:

- persistência;
- banco de dados;
- arquivos;
- APIs externas;
- logs;
- configurações.

A infraestrutura depende do domínio, nunca o contrário.

## Contratos de Infraestrutura (Ports)

A Infraestrutura define, em `app/Infrastructure/Contracts/`, as portas que
isolam Domínio e Aplicação de qualquer tecnologia externa concreta (relógio,
log, persistência, armazenamento de arquivos, geração de UUID, transações,
despacho de eventos e mensagens, transcrição de áudio e provedores de LLM).

Apenas as interfaces e os DTOs de entrada/saída são definidos nesta camada;
as implementações concretas (SQLite, filas, provedores de IA, etc.) serão
adicionadas em sprints futuras, uma tecnologia de cada vez, sem alterar os
contratos publicados.

---

# Dependências

```text
Apresentação
      │
      ▼
Aplicação
      │
      ▼
Domínio

Infraestrutura
      │
      └────────► Domínio
```

---

# Princípios

- O domínio é independente.
- As dependências apontam para o domínio.
- Infraestrutura não contém regras de negócio.
- Casos de uso pertencem à aplicação.
- A apresentação apenas comunica com o usuário.