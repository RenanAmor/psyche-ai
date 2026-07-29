# Arquitetura — Psyche AI

> Versão 0.2 — Sprint 4 (Fundação Arquitetural)

Este documento define a arquitetura conceitual do PsycheAI. O detalhamento das camadas, componentes e implementações será realizado nas próximas sprints.

---

# 1. Stack Tecnológica

- PHP 8.2+
- Composer
- PHPUnit
- PSR-4
- Ecossistema L369

---

# 2. Estrutura do Projeto

```text
psyche-ai/
├── app/
├── config/
├── docs/
├── storage/
├── tests/
├── README.md
├── composer.json
├── .env.example
└── .gitignore
```

---

# 3. Convenções

- Namespace seguindo PSR-4.
- Configurações em `.env`.
- `storage/` contém somente dados produzidos pela aplicação.
- O domínio permanece independente da infraestrutura.

---

# 4. Arquitetura do Domínio

O PsycheAI é um sistema para observação longitudinal do discurso humano.

Seu domínio consiste em organizar, preservar e tornar observáveis as recorrências presentes na história do discurso.

O sistema não interpreta o sujeito.

O sistema não realiza diagnósticos.

O sistema não atribui significado clínico.

A interpretação permanece responsabilidade do analista.

---

## Fluxo Arquitetural

```text
Discurso
    │
    ▼
Sessão
    │
    ▼
Memória Longitudinal
    │
    ▼
Recorrências
    │
    ▼
Observações
```

---

### Discurso

Representa todo conteúdo produzido pelo sujeito.

---

### Sessão

Agrupa temporalmente os discursos produzidos em um mesmo contexto.

---

### Memória Longitudinal

Preserva toda a história discursiva do sujeito.

---

### Recorrências

Representam elementos que reaparecem ao longo da história do discurso.

São registradas sem interpretação clínica.

---

### Observações

São registros estruturados produzidos pelo sistema a partir das recorrências identificadas.

Observações nunca representam interpretações.

---

# 5. Princípios Arquiteturais

- O domínio do PsycheAI é o discurso.
- O sistema preserva a história do discurso.
- O sistema organiza eventos discursivos.
- O sistema constrói memória longitudinal.
- O sistema identifica recorrências.
- O sistema não interpreta o sujeito.
- O sistema não realiza diagnósticos.
- O sistema não identifica significantes.
- O sistema não produz conclusões clínicas.
- Toda interpretação permanece responsabilidade do analista.

---

# 6. Estado Atual

Nesta fase existe apenas a fundação arquitetural do projeto.

As camadas de domínio, aplicação e infraestrutura serão implementadas nas próximas sprints.

---

# 7. Próximos Passos

1. Definir os objetos fundamentais do domínio.
2. Implementar as entidades.
3. Implementar os Value Objects.
4. Implementar os Eventos de Domínio.
5. Implementar os Serviços de Domínio.
6. Implementar os Casos de Uso.
7. Implementar a Persistência.
8. Implementar os mecanismos computacionais de recorrência.
9. Implementar a interface da aplicação.