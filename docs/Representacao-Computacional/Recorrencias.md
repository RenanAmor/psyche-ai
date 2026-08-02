# Recorrências — Representação Computacional

> Sprint 29. Especifica a representação de Recorrências ao Analista: frequência, primeira ocorrência, última ocorrência, intensidade, duração e persistência — cada uma auditada contra o código real do Motor Freud.

## Objetivo

Apresentar ao Analista o fato de que um mesmo conteúdo discursivo, normalizado, reapareceu no discurso do Sujeito — nunca o que essa repetição significaria (Regra 7, [Regras-Dominio.md](../Regras-Dominio.md): "o sistema registra recorrências, não interpreta recorrências").

## Rastreabilidade

```
Biblioteca Teórica: Repetição (Ontologia-Freud.md §3) — via Freud/Além do Princípio do Prazer
Modelo Observacional: Modelo-Observacional/Conceitos/repeticao.md — único conceito observado, organizado e classificado automaticamente hoje
Modelo Relacional: Modelo-Relacional/Conceitos/repeticao.md — único conceito com observação relacional direta e integral
Representação Computacional: este documento
```

## As seis dimensões

| Dimensão | Estado | Fundamentação |
|---|---|---|
| **Frequência** | Implementado | `Recorrencia::frequencia()` (`Frequencia` Value Object), incrementada por `DetectorRecorrencias::detectar()` a cada nova ocorrência do mesmo conteúdo normalizado (trim + minúsculas, Sprint 15) |
| **Primeira ocorrência** | Implementado indiretamente | `CircuitoRecorrenciaDTO::$ocorrencias` traz a lista de `OcorrenciaCircuitoDTO` em ordem cronológica; a primeira do array é a primeira ocorrência — não é um campo nomeado próprio, é a posição inicial da lista já ordenada por `detectarCircuito()` |
| **Última ocorrência** | Implementado indiretamente | Mesma fonte — o último item de `CircuitoRecorrenciaDTO::$ocorrencias` |
| **Intensidade** | Não implementado — especificação para sprint futura | Nenhum componente do sistema atribui um valor de intensidade a uma Recorrência hoje; `Frequencia` mede contagem, não intensidade. Não deve ser confundida com a "Intensidade" do Modelo Relacional (classificação Fundamental/Forte/Moderada/Fraca/Contextual da força de uma *relação entre conceitos*, [Modelo-Relacional/README.md](../Modelo-Relacional/README.md#modelo-único-de-documento-conceitos) — categoria distinta, aplicada a outro objeto) |
| **Duração** | Implementado indiretamente | Calculável como a diferença entre `momento` da primeira e da última `OcorrenciaCircuitoDTO` — não é um campo persistido ou exposto diretamente na resposta |
| **Persistência** | Implementado | `ReclassificadorLacaniano::reclassificarComTrajeto()` já calcula a quantidade de sessões distintas atravessadas por uma Recorrência para decidir o rótulo de circuito (`count($sessoesDistintas) >= 2`) — ver [Circuitos.md](Circuitos.md) |

## Dados necessários

Dois ou mais `EventoDiscursivo` com o mesmo conteúdo normalizado (`RecorrenciaMinimaSpecification`: uma única ocorrência não é recorrência).

## Dados opcionais

`vocabulario=lacan` (query param) para reclassificação lacaniana — ver [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md).

## Componentes envolvidos

`DetectorRecorrencias`, `Recorrencia`, `Frequencia`, `RecorrenciaMinimaSpecification`, `RecorrenciaDTO`, `CircuitoRecorrenciaDTO`, `OcorrenciaCircuitoDTO`, `ObservacaoApplicationService::consultar()`/`consultarCircuito()`.

## Evidências que sustentam esta representação

A comparação determinística de conteúdo normalizado entre `EventoDiscursivo` distintos — nunca similaridade semântica, NLP ou julgamento de importância (Sprint 15). Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista, atrás de `PortaoDeAnalista::proteger()`. `Recorrencia` é citada explicitamente em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) como estrutura que o Sujeito nunca visualiza. Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Circuitos.md](Circuitos.md)
- [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)
- [Evidencias.md](Evidencias.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Modelo-Relacional/Conceitos/repeticao.md](../Modelo-Relacional/Conceitos/repeticao.md)
