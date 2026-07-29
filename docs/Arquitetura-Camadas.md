# Arquitetura em Camadas — PsycheAI

> Versão 1.0

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