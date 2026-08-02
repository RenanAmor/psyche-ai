# Memória Longitudinal — Representação Computacional

> Sprint 29. Especifica a representação da Memória Longitudinal ao Analista: evolução temporal, histórico completo, continuidade e consolidação.

## Objetivo

Apresentar a consolidação de todas as Sessões de um Sujeito ao longo do tempo — a estrutura de dados que sustenta a Regra 5 ([Regras-Dominio.md](../Regras-Dominio.md): "a memória longitudinal é construída pela sequência cronológica das sessões") e a Regra 6 (recorrências só podem ser identificadas comparando diferentes momentos da memória longitudinal).

## Rastreabilidade

```
Biblioteca Teórica: Repetição (Ontologia-Freud.md §3) — pressupõe comparação entre momentos distintos
Modelo Observacional: Modelo-Observacional/Conceitos/repeticao.md
Modelo Relacional: Modelo-Relacional/Conceitos/repeticao.md — conceito com observação relacional direta e integral
Representação Computacional: este documento
```

## As quatro dimensões

| Dimensão | Estado | Fundamentação |
|---|---|---|
| **Evolução temporal** | Implementado | `MemoriaLongitudinal::adicionarSessao()` acumula Sessões na ordem em que são registradas; `LinhaDoTempoApplicationService` ancora cada Memória na data da última Sessão que consolida |
| **Histórico completo** | Implementado | `MemoriaLongitudinal::sessoes()` devolve todas as Sessões consolidadas, sem truncamento — nenhuma Sessão é descartada por qualquer critério, inclusive casos interrompidos ([Princípio da Neutralidade Observacional](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional)) |
| **Continuidade** | Implementado | `ConstruirMemoriaLongitudinalHandler` (usado por `RespostaEcoRecorrenciaService`) reconstrói a Memória a cada consulta, garantindo que nenhuma Sessão fique fora da cadeia — mesma decisão arquitetural de recalcular em vez de persistir estado derivado (Sprint 14) |
| **Consolidação** | Implementado | `MemoriaLongitudinal::quantidadeDeSessoes()`; `ConsolidacaoApplicationService::consolidar()` soma Sessões, Discursos, Eventos Discursivos e Memórias — ver [Indicadores.md](Indicadores.md) |

## Dados necessários

`Sujeito` com uma ou mais `Sessao`. Nenhuma Memória Longitudinal é construída sem pelo menos uma Sessão associada.

## Dados opcionais

Nenhum — a Memória Longitudinal é, por definição, a soma do que já existe; não há campo enriquecedor além das próprias Sessões.

## Componentes envolvidos

`MemoriaLongitudinal` (Domain), `ConstruirMemoriaLongitudinalHandler`, `MemoriaApplicationService`, `ConsolidacaoApplicationService`, `LinhaDoTempoApplicationService` (que a incorpora como item da Timeline — ver [Timeline.md](Timeline.md)).

## Evidências que sustentam esta representação

A própria sequência de `Sessao` registrada — sem inferência. Cada Sessão presente na Memória Longitudinal é auditável até o registro original (ver [Evidencias.md](Evidencias.md)).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista. O Sujeito nunca visualiza `MemoriaLongitudinal` como objeto estruturado — apenas, quando implementado (ver [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md)), seu próprio histórico de sessões em linguagem natural, sem a consolidação analítica. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Timeline.md](Timeline.md)
- [Indicadores.md](Indicadores.md)
- [Recorrencias.md](Recorrencias.md)
- [Evidencias.md](Evidencias.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
