# Casos de Uso — PsycheAI

> Versão 1.0

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