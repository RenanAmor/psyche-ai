# Casos de Uso — PsycheAI

> Versão 1.1

Este documento define os casos de uso do PsycheAI.

Os casos de uso representam as ações executadas pela aplicação sobre o domínio.

---

# UC-001 — Registrar Discurso

## Objetivo

Registrar um novo discurso produzido pelo sujeito.

## Resultado

O discurso passa a integrar a sessão correspondente.

---

# UC-002 — Criar Sessão

## Objetivo

Criar uma nova sessão para um sujeito.

## Resultado

A sessão fica disponível para receber discursos.

---

# UC-003 — Encerrar Sessão

## Objetivo

Finalizar uma sessão.

## Resultado

A sessão torna-se parte da memória longitudinal.

---

# UC-004 — Atualizar Memória Longitudinal

## Objetivo

Incorporar uma sessão ao histórico do sujeito.

## Resultado

A memória longitudinal permanece cronologicamente consistente.

---

# UC-005 — Identificar Recorrências

## Objetivo

Comparar a memória longitudinal em busca de recorrências.

## Resultado

As recorrências identificadas são registradas.

---

# UC-006 — Produzir Observações

## Objetivo

Gerar observações descritivas a partir das recorrências.

## Resultado

O sistema produz registros observáveis, sem interpretação clínica.

---

# UC-007 — Consultar Histórico

## Objetivo

Permitir a consulta da memória longitudinal de um sujeito.

## Resultado

O histórico é apresentado em ordem cronológica.

---

# UC-008 — Consultar Observações

## Objetivo

Permitir a visualização das observações produzidas pelo sistema.

## Resultado

O analista pode consultar os registros observacionais.

---

# Princípios

- Todo caso de uso representa uma ação da aplicação.
- Casos de uso orquestram o domínio.
- Casos de uso não contêm lógica de infraestrutura.
- Casos de uso nunca realizam interpretação clínica.
- Casos de uso preservam a integridade do domínio.

---

# Implementação — Sprint 6 (Camada Application)

A Sprint 6 implementou os seguintes Use Cases em `app/Application/UseCases/`,
cada um com `Command`, `Handler` e `Result`:

| Use Case implementado | UC relacionado |
|---|---|
| `CadastrarSujeito` | pré-requisito para UC-002 (não listado explicitamente acima) |
| `RegistrarSessao` | UC-002 — Criar Sessão |
| `RegistrarDiscurso` | UC-001 — Registrar Discurso |
| `RegistrarEventoDiscursivo` | pré-requisito para UC-005 (não listado explicitamente acima) |
| `ConstruirMemoriaLongitudinal` | UC-004 — Atualizar Memória Longitudinal |
| `DetectarRecorrencias` | UC-005 — Identificar Recorrências |
| `GerarObservacoes` | UC-006 — Produzir Observações |

`Application/Services/CicloDeObservacaoService` compõe os três últimos Use
Cases acima em um único fluxo, refletindo as etapas finais de
`docs/Ciclo-do-Sistema.md`.

Não implementados nesta Sprint (pendências para sprints futuras):

- **UC-003 — Encerrar Sessão**: `Sessao` não possui estado de
  encerramento no domínio atual; requer decisão de design antes de ser
  implementado (adicionar o conceito ao domínio ou modelá-lo apenas na
  camada de Aplicação).
- **UC-007 — Consultar Histórico** e **UC-008 — Consultar Observações**:
  são casos de uso de consulta (query), que dependem de um mecanismo de
  persistência/repositório ainda não implementado (fora do escopo desta
  Sprint, que exclui infraestrutura e persistência).