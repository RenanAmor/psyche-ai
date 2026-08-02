# Circuitos — Representação Computacional

> Sprint 29. Especifica a representação do Circuito ao Analista: circuito pulsional, retornos, mudanças, persistências e encerramentos.

## Objetivo

Apresentar ao Analista quando uma Recorrência não se limita a uma única Sessão, mas atravessa ≥2 Sessões distintas — o "trajeto" de um mesmo conteúdo normalizado ao longo do tempo, sempre como constatação estrutural contável, nunca como leitura de sentido.

## Nota terminológica obrigatória — "Circuito Pulsional" é nome, não operacionalização de Pulsão

O componente listado nas matrizes Motor×Conceito ([Modelo-Relacional/Matrizes/Motor-x-Conceito.md](../Modelo-Relacional/Matrizes/Motor-x-Conceito.md)) e no [Grafo-Motores.md](../Modelo-Relacional/Grafos/Grafo-Motores.md) como "Circuito Pulsional" evoca o conceito freudiano de Pulsão, mas — como já registrado com precisão em [Modelo-Relacional/Conceitos/pulsao.md](../Modelo-Relacional/Conceitos/pulsao.md) e [Modelo-Relacional/Conceitos/objeto-a.md](../Modelo-Relacional/Conceitos/objeto-a.md) — **o nome é inspirado no conceito, sem operacionalizar a teoria pulsional**. O que o componente de fato implementa é o circuito/trajeto de uma `Recorrencia` através de Sessões distintas — a mesma base de dados de [Recorrencias.md](Recorrencias.md), reconstruída em ordem cronológica. Este documento preserva essa distinção em toda a sua extensão.

## Rastreabilidade

```
Biblioteca Teórica: Repetição (Ontologia-Freud.md §3), lida por Lacan como Real que insiste e retorna ao mesmo lugar (Ontologia-Lacan.md §3.7/§4)
Modelo Observacional: Modelo-Observacional/Conceitos/repeticao.md — "Circuito Pulsional: grafo do circuito (D3)"
Modelo Relacional: Modelo-Relacional/Conceitos/repeticao.md, Modelo-Relacional/Conceitos/pulsao.md (nota de não-operacionalização)
Representação Computacional: este documento
```

## As cinco dimensões

| Dimensão | Estado | Fundamentação |
|---|---|---|
| **Circuito pulsional** (nome do componente) | Implementado como circuito de recorrências | `DetectorRecorrencias::detectarCircuito()` (revisão pós-Sprint 16), `ObservacaoApplicationService::consultarCircuito()`, expostos via `GET subjects/{id}/observations/circuito?vocabulario=lacan` |
| **Retornos** | Implementado | `ReclassificadorLacaniano::reclassificarComTrajeto()` atribui o rótulo de circuito quando `count($sessoesDistintas) >= 2` — o "retorno" é, computacionalmente, a Recorrência reaparecendo em uma nova Sessão distinta das anteriores |
| **Mudanças** | Não implementado — especificação para sprint futura | Mesma lacuna já registrada em [Timeline.md](Timeline.md#as-sete-dimensões): nenhum componente compara o que muda no conteúdo entre ocorrências de um mesmo circuito, apenas constata a repetição |
| **Persistências** | Implementado | Quantidade de sessões distintas atravessadas por uma mesma Recorrência (`count($sessoesDistintas)`), disponível via `CircuitoRecorrenciaDTO::$ocorrencias` — ver também [Recorrencias.md](Recorrencias.md#as-seis-dimensões) |
| **Encerramentos** | Não implementado — especificação para sprint futura | Nenhum componente detecta a ausência prolongada de uma Recorrência anteriormente ativa como "encerramento" — exigiria uma janela temporal de referência que não existe hoje; a Timeline mostra apenas o que está registrado, nunca infere o que deixou de ocorrer |

## Dados necessários

Uma `Recorrencia` (`frequencia` ≥ 2) com `OcorrenciaRecorrencia[]` reconstruídas em ≥2 `sessaoId` distintos.

## Dados opcionais

`vocabulario=lacan` para o rótulo de circuito ("Estrutura candidata: circuito...") e sua fundamentação teórica.

## Componentes envolvidos

`DetectorRecorrencias::detectarCircuito()`, `ReclassificadorLacaniano::reclassificarComTrajeto()`, `CircuitoRecorrenciaDTO`, `OcorrenciaCircuitoDTO`, `ObservacaoApplicationService::consultarCircuito()`, `CircuitoTrajetoComponent` (fallback textual), `GrafoCircuitoViewModel` (visualização D3 — ver [Grafos.md](Grafos.md)).

## Evidências que sustentam esta representação

A sequência real de `OcorrenciaRecorrencia`, ordenada cronologicamente por `momento` — cada ocorrência é rastreável até o `EventoDiscursivo`, `Discurso` e `Sessao` de origem. Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista, atrás de `PortaoDeAnalista::proteger()` — a rota do grafo de circuito é citada explicitamente como protegida desde a Sprint 19. O "circuito pulsional" é citado nominalmente em [Arquitetura.md §9.2](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista) como algo que o Sujeito nunca visualiza. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Recorrencias.md](Recorrencias.md)
- [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)
- [Grafos.md](Grafos.md)
- [Evidencias.md](Evidencias.md)
- [../Modelo-Relacional/Conceitos/pulsao.md](../Modelo-Relacional/Conceitos/pulsao.md)
- [../Modelo-Relacional/Grafos/Grafo-Motores.md](../Modelo-Relacional/Grafos/Grafo-Motores.md)
