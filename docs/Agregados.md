# Agregados — PsycheAI

> Versão 1.0

Este documento define os agregados do domínio do PsycheAI.

Um agregado é um conjunto de entidades e objetos de valor tratados como uma única unidade de consistência.

Cada agregado possui uma Raiz de Agregado (Aggregate Root), responsável por garantir a integridade do conjunto.

---

# Agregado: Sujeito

## Raiz

Sujeito

## Contém

- Sessões
- Memória Longitudinal

## Responsabilidades

- manter a identidade do sujeito;
- preservar o histórico de sessões;
- garantir a consistência da memória longitudinal.

---

# Agregado: Sessão

## Raiz

Sessão

## Contém

- Discursos
- Eventos Discursivos

## Responsabilidades

- organizar os discursos;
- preservar a ordem cronológica;
- registrar os eventos produzidos durante a sessão.

---

# Agregado: Memória Longitudinal

## Raiz

Memória Longitudinal

## Contém

- Recorrências

## Responsabilidades

- consolidar o histórico do sujeito;
- comparar sessões;
- identificar recorrências.

---

# Agregado: Observações

## Raiz

Observação

## Contém

- Evidências

## Responsabilidades

- registrar fatos observáveis;
- manter as evidências relacionadas;
- preservar a rastreabilidade das observações.

---

# Regras dos Agregados

- Toda modificação ocorre por meio da Raiz do Agregado.
- Nenhuma entidade interna pode ser modificada diretamente por outro agregado.
- Agregados comunicam-se por Eventos de Domínio.
- Cada agregado mantém sua própria consistência.
- Agregados nunca executam interpretações clínicas.

---

# Princípios

- Alta coesão.
- Baixo acoplamento.
- Consistência transacional.
- Independência entre agregados.
- Integridade do domínio.