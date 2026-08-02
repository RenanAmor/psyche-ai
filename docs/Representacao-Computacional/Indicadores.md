# Indicadores — Representação Computacional

> Sprint 29. Define os indicadores observacionais disponíveis ao Analista. Nunca indicadores clínicos — nenhum indicador aqui documentado mede desfecho terapêutico, gravidade, risco ou qualquer variável de julgamento clínico.

## Objetivo

Apresentar contagens e consolidações simples sobre o que já foi registrado — nunca uma métrica de sucesso, progresso ou resultado. Implementa diretamente o [Princípio da Neutralidade Observacional](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional): um indicador de "poucas Sessões" não é lido como "caso malsucedido", apenas como fato quantitativo.

## Rastreabilidade

```
Biblioteca Teórica: nenhum conceito psicanalítico específico — indicadores são infraestrutura de consolidação, não teoria
Modelo Observacional: Modelo-Observacional.md §1 (objetivo da observação, distinto de sucesso terapêutico)
Modelo Relacional: não aplicável — indicadores não são um conceito canônico, mas uma agregação sobre eles
Representação Computacional: este documento
```

## Indicadores implementados

Todos produzidos por `ConsolidacaoApplicationService::consolidar()` (Sprint 13), somando o que já está persistido — sem comparar Sessões entre si, sem identificar recorrências, sem julgar qualidade:

| Indicador | Fonte |
|---|---|
| Quantidade de Sessões | `count($sujeito->sessoes())` |
| Quantidade de Discursos | soma de `count($sessao->discursos())` por Sessão |
| Quantidade de Eventos Discursivos | soma de `count($discurso->eventos())` por Discurso |
| Quantidade de Memórias Longitudinais | `quantidadeDeMemoriasDoSujeito()` — Memórias cuja primeira Sessão pertence ao Sujeito |

## Indicadores não implementados — especificação para sprint futura

Nenhum destes existe no código nesta versão; todos exigiriam nova agregação sobre `Recorrencia`/`OcorrenciaRecorrencia`, hoje calculadas sob demanda e nunca persistidas (Sprint 14, decisão arquitetural de recalcular a cada consulta):

- Quantidade de Recorrências detectadas por Sujeito.
- Quantidade de circuitos (Recorrências que atravessam ≥2 Sessões).
- Distribuição de Formações Freudianas por tipo (`TipoFormacaoFreudiana`).
- Quantidade de reclassificações lacanianas por rótulo.

## Por que nenhum indicador clínico existe nem poderá existir

Um "indicador clínico" pressuporia uma escala de julgamento sobre o estado do Sujeito (gravidade, risco, progresso terapêutico) — proibido pela Regra 9 ([Regras-Dominio.md](../Regras-Dominio.md): "nenhum algoritmo do sistema pode produzir diagnósticos") e pelo [Princípio da Neutralidade Observacional](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional): a plataforma não mede sucesso pelo desfecho clínico. Todo indicador documentado nesta pasta é, e permanecerá, uma contagem factual sobre o que foi registrado — nunca uma leitura sobre o que essa contagem significaria para o Sujeito.

## Dados necessários

`Sujeito` com pelo menos uma `Sessao` registrada (mesmo requisito de [Timeline.md](Timeline.md)).

## Componentes envolvidos

`ConsolidacaoApplicationService`, `ConsolidacaoMemoriaDTO`, `ConsolidacaoResponse`, tela `sujeitos/{id}/historico`.

## Evidências que sustentam esta representação

Contagem direta sobre registros persistidos — nenhuma inferência. Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Memoria-Longitudinal.md](Memoria-Longitudinal.md)
- [Timeline.md](Timeline.md)
- [Evidencias.md](Evidencias.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional)
- [../Regras-Dominio.md](../Regras-Dominio.md)
