# Formações Freudianas — Representação Computacional

> Sprint 29. Especifica a representação das cinco formações freudianas ao Analista: ato falho, sonho, chiste, formação de compromisso e repetição — sempre como evidência de forma, nunca como interpretação de conteúdo.

## Objetivo

Apresentar ao Analista a classificação estrutural (forma), nunca o sentido, de um conteúdo discursivo já registrado — limite reforçado pelo próprio desenho técnico do classificador (ver "Evidências", abaixo), não apenas por disciplina de prompt.

## Rastreabilidade

```
Biblioteca Teórica: Ato falho, Chiste, Sonhos, Formação de compromisso, Repetição (Ontologia-Freud.md §3)
Modelo Observacional: Modelo-Observacional/Conceitos/{ato-falho,chiste,sonhos,formacao-de-compromisso,repeticao}.md — "observado, organizado e classificado automaticamente hoje"
Modelo Relacional: Modelo-Relacional/Conceitos/{mesmos cinco} — únicos, junto de Metonímia, com pelo menos uma relação computacionalmente observável ou inferível
Representação Computacional: este documento
```

## As cinco formações

| Formação | Estado | Fundamentação |
|---|---|---|
| **Ato falho** | Implementado | `TipoFormacaoFreudiana::AtoFalho`, classificado por `ClassificadorFreudianoLLM` |
| **Sonho** | Implementado | `TipoFormacaoFreudiana::Sonho` |
| **Chiste** | Implementado | `TipoFormacaoFreudiana::Chiste` |
| **Formação de compromisso** | Implementado (categoria geral, indeterminada por definição) | `TipoFormacaoFreudiana::FormacaoDeCompromisso` — "outra produção que combina dois sentidos ou impulsos em conflito, sem se encaixar nas anteriores" (prompt do classificador); por ser a categoria geral (Ontologia-Freud.md §3.5), sua reclassificação lacaniana permanece indeterminada entre metáfora e metonímia (ver [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)) |
| **Repetição** | Implementado, com caminho próprio | `TipoFormacaoFreudiana::Repeticao` é uma das seis categorias do classificador via LLM, mas a Repetição também é observada por via inteiramente distinta e determinística — `DetectorRecorrencias` (ver [Recorrencias.md](Recorrencias.md)) — sem depender de LLM |

Um sexto valor, `TipoFormacaoFreudiana::NaoClassificado`, existe como resultado padrão sempre que o classificador não consegue decidir com clareza, ou quando qualquer falha técnica (rede, JSON inválido, valor fora do enum) ocorre — nunca uma sétima formação teórica.

## Dados necessários

Um `EventoDiscursivo` com conteúdo textual não vazio.

## Dados opcionais

Nenhum — a classificação opera sobre o conteúdo isolado do evento, sem contexto adicional de sessão nesta versão.

## Componentes envolvidos

`TipoFormacaoFreudiana` (Domain, enum fechado de 6 valores), `ClassificarFormacaoFreudianaHandler`, `ClassificadorFreudianoLLM` (Infrastructure), `AnthropicLLMService`.

## Evidências que sustentam esta representação — guardrail de sistema, não de prompt

O contrato de saída do LLM (`output_config.format`) restringe a resposta a um único campo (`tipo`) preso a um enum fechado de seis strings — não há espaço estrutural para o modelo produzir justificativa, análise ou interpretação. A resposta bruta nunca é aceita sem validação: `TipoFormacaoFreudiana::tryFrom()` recusa qualquer valor fora do enum, caindo em `NaoClassificado`. Nenhum texto livre do modelo chega ao Analista — apenas a categoria fechada. Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista. `TipoFormacaoFreudiana` está listada explicitamente em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) como classificação nunca exposta ao Sujeito. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Recorrencias.md](Recorrencias.md)
- [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)
- [Evidencias.md](Evidencias.md)
- [../Ontologia-Freud.md](../Ontologia-Freud.md)
- [../Documento-Mestre.md](../Documento-Mestre.md#65-limites-do-sistema)
