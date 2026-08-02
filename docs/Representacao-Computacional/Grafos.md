# Grafos — Representação Computacional

> Sprint 29. Especifica cinco tipos de grafo para a interface do Analista: discursivo, conceitual, temporal, de recorrências e de relações. Sem implementação — apenas documentação, salvo o único grafo já real (circuito/trajeto, Sprint 19), citado explicitamente onde se sobrepõe.

## Objetivo

Distinguir, sem ambiguidade, os grafos que já existem no PsycheAI dos que são apenas especificação científica — e situar ambos dentro do mesmo vocabulário, para que nenhuma sprint futura de implementação confunda "documentado" com "implementado".

## Rastreabilidade

```
Biblioteca Teórica: conforme o tipo de grafo — ver cada seção abaixo
Modelo Observacional: Modelo-Observacional/Conceitos/repeticao.md (único fenômeno com grafo real)
Modelo Relacional: Modelo-Relacional/Grafos/README.md — cinco grafos científicos especificados, sem implementação
Representação Computacional: este documento
```

## Os cinco tipos

### 1. Grafo discursivo

Nós: unidades de discurso de um Sujeito (Sessão, Discurso, Evento Discursivo). Arestas: sucessão cronológica ou vínculo de recorrência entre unidades.

**Parcialmente implementado.** O único grafo discursivo real do PsycheAI é o **Grafo do Circuito/Trajeto** (D3, Sprint 19, `GrafoCircuitoViewModel`): nós = Sessões distintas (deduplicadas por `sessaoId`), arestas = pares consecutivos de ocorrências de uma mesma Recorrência, rotuladas com `rotuloLacaniano` quando disponível. Representa **ocorrências no tempo**, não a estrutura conceitual entre teorias — distinção já registrada em [Modelo-Relacional/Grafos/README.md](../Modelo-Relacional/Grafos/README.md#por-que-especificação-nunca-implementação). Ver [Circuitos.md](Circuitos.md).

### 2. Grafo conceitual

Nós: os 21 conceitos canônicos da Biblioteca Teórica. Arestas: as relações entre eles (antecedência, consequência, bidirecionalidade), classificadas por Intensidade e Natureza.

**Especificação apenas — equivalente ao já documentado.** Este tipo de grafo já está inteiramente especificado, sem implementação, em [Modelo-Relacional/Grafos/](../Modelo-Relacional/Grafos/README.md): [Grafo-Freud.md](../Modelo-Relacional/Grafos/Grafo-Freud.md) (10 nós), [Grafo-Lacan.md](../Modelo-Relacional/Grafos/Grafo-Lacan.md) (11 nós) e [Grafo-Integrado.md](../Modelo-Relacional/Grafos/Grafo-Integrado.md) (21 nós, 51 arestas). Este documento não os reespecifica — apenas os reconhece como a instância do "grafo conceitual" do briefing desta Sprint.

### 3. Grafo temporal

Nós: momentos discretos da produção discursiva de um Sujeito (cada item da Timeline). Arestas: adjacência cronológica entre dois momentos consecutivos, independente de conteúdo ou recorrência.

**Não implementado — especificação nova desta Sprint.** Distinto do Grafo discursivo (que conecta por recorrência) e da Timeline em si (que é uma lista ordenada, não um grafo — ver [Timeline.md](Timeline.md)): o Grafo temporal representaria a Linha do Tempo como estrutura de nós e arestas, útil para visualizar lacunas/intervalos entre sessões. `LinhaDoTempoApplicationService` já produz os nós ordenados (`LinhaDoTempoItemDTO`); nenhum componente hoje monta as arestas de adjacência como grafo.

### 4. Grafo de recorrências

Nós: as `Recorrencia` distintas de um Sujeito. Arestas: co-ocorrência — duas Recorrências que aparecem na mesma Sessão ou no mesmo Discurso.

**Não implementado — especificação nova desta Sprint.** `DetectorRecorrencias` agrupa ocorrências de um mesmo conteúdo em uma única `Recorrencia`, mas não relaciona Recorrências distintas entre si. Diferente do Grafo do Circuito (que conecta ocorrências de uma **mesma** Recorrência ao longo do tempo), este grafo conectaria Recorrências **diferentes** que compartilham contexto — nenhum dado, hoje, sustenta essa aresta.

### 5. Grafo de relações

Nós: os 21 conceitos canônicos. Arestas: todas as relações científicas já fundamentadas (estruturais, temporais, observacionais, de dependência, bidirecionais, não observáveis computacionalmente).

**Especificação apenas — mesmo objeto do Grafo Integrado.** Este tipo coincide, por definição, com [Grafo-Integrado.md](../Modelo-Relacional/Grafos/Grafo-Integrado.md) — a união de todas as relações classificadas na matriz [Conceito×Conceito](../Modelo-Relacional/Matrizes/Conceito-x-Conceito.md). Não é redocumentado aqui; a Sprint 29 reconhece-o como a instância do "grafo de relações" do briefing.

## Tabela-resumo

| Tipo | Nós | Arestas | Estado |
|---|---|---|---|
| Discursivo | Sessões de um Sujeito | Trajeto de uma Recorrência | Implementado (D3, Sprint 19) |
| Conceitual | 21 conceitos canônicos | Relações Freud/Lacan | Especificado (Modelo-Relacional/Grafos) |
| Temporal | Itens da Timeline | Adjacência cronológica | Não implementado |
| De recorrências | Recorrências distintas | Co-ocorrência | Não implementado |
| De relações | 21 conceitos canônicos | Todas as relações científicas | Especificado (Grafo Integrado) |

## Dados necessários

Variam por tipo — ver seção própria de cada um acima.

## Componentes envolvidos

`GrafoCircuitoViewModel`, `ObservacoesSujeitoController::grafoCircuito()` (grafo discursivo real); nenhum componente para os quatro grafos restantes.

## Evidências que sustentam esta representação

Idênticas às de [Circuitos.md](Circuitos.md) para o grafo discursivo real; para os demais, a fundamentação bibliográfica já auditada em [Modelo-Relacional/Matrizes/Conceito-x-Conceito.md](../Modelo-Relacional/Matrizes/Conceito-x-Conceito.md). Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista. Grafos estão listados explicitamente em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) como algo que o Sujeito nunca visualiza, em nenhuma hipótese. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Circuitos.md](Circuitos.md)
- [Timeline.md](Timeline.md)
- [Recorrencias.md](Recorrencias.md)
- [Evidencias.md](Evidencias.md)
- [../Modelo-Relacional/Grafos/README.md](../Modelo-Relacional/Grafos/README.md)
- [../Modelo-Relacional/Matrizes/Conceito-x-Conceito.md](../Modelo-Relacional/Matrizes/Conceito-x-Conceito.md)
