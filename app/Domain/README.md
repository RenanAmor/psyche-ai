# Domain — PsycheAI

> Versão 1.0

A pasta `Domain` representa o núcleo do PsycheAI.

Todo o conhecimento do sistema está concentrado nesta camada.

O domínio é completamente independente da interface, da infraestrutura e de qualquer tecnologia externa.

---

# Estrutura

```text
Domain/
├── Aggregates/
├── Contracts/
├── Entities/
├── Events/
├── Exceptions/
├── Repositories/
├── Services/
├── Specifications/
└── ValueObjects/
```

---

# Responsabilidades

O domínio é responsável por:

- representar o modelo de negócio;
- preservar as regras do sistema;
- definir contratos;
- definir entidades;
- definir objetos de valor;
- definir eventos de domínio;
- definir agregados;
- definir serviços de domínio.

---

# Restrições

O domínio não pode:

- acessar banco de dados;
- acessar arquivos;
- acessar APIs;
- acessar HTTP;
- acessar interface gráfica;
- acessar infraestrutura.

---

# Dependências

O domínio não depende de nenhuma outra camada.

Ele representa o centro da arquitetura.

---

# Convenções

- Um arquivo por classe.
- Um namespace por diretório.
- Classes em PascalCase.
- Interfaces terminadas em `Interface`.
- Value Objects imutáveis.
- Entidades possuem identidade.
- Serviços não armazenam estado.
- Eventos representam fatos consumados.

---

# Objetivo

Toda implementação futura deverá respeitar estas convenções para manter a integridade arquitetural do PsycheAI.