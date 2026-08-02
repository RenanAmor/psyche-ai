# Evidências — Representação Computacional

> Sprint 29. Consolida, para as oito representações desta pasta, quais evidências sustentam cada uma e sua rastreabilidade obrigatória até a Biblioteca Teórica. Nenhuma representação é admitida sem esta cadeia completa.

## O princípio

Toda representação computacional deve informar explicitamente quais evidências sustentam sua existência — nunca ser apresentada como um fato isolado. E toda representação deve possuir rastreabilidade até:

```
Biblioteca Teórica
    ↓
Modelo Observacional
    ↓
Modelo Relacional
    ↓
Representação Computacional
```

Esta cadeia é a mesma exigida para qualquer implementação futura em [Arquitetura-Cientifica.md §1](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) — aplicada aqui, retroativamente, a cada uma das oito representações já especificadas nesta pasta.

## O que conta como "evidência" no PsycheAI

Uma evidência nunca é uma interpretação — é o dado bruto ou a derivação determinística de um dado bruto que sustenta uma representação. Distinção já formalizada pela Regra 8 ([Regras-Dominio.md](../Regras-Dominio.md): "observações descrevem fatos encontrados; observações nunca apresentam interpretações clínicas"):

- **Evidência primária**: um registro persistido diretamente (`Sessao`, `Discurso`, `EventoDiscursivo`).
- **Evidência derivada determinística**: o resultado de uma operação puramente mecânica sobre evidência primária (comparação de string normalizada, contagem, ordenação cronológica) — nunca de julgamento semântico.
- **Evidência classificada**: o resultado de uma classificação estrutural fechada (enum de 6 valores, `TipoFormacaoFreudiana`), sempre validada contra o enum antes de ser aceita — nunca texto livre.

## Tabela de rastreabilidade por representação

| Representação | Evidência | Biblioteca Teórica | Modelo Observacional | Modelo Relacional |
|---|---|---|---|---|
| [Timeline.md](Timeline.md) | Registros primários (Sessão/Discurso/Evento/Memória) | — (infraestrutura temporal) | Modelo-Computacional-Discurso.md | Relações de Natureza = Temporal |
| [Memoria-Longitudinal.md](Memoria-Longitudinal.md) | Sequência de `Sessao` | Repetição | Conceitos/repeticao.md | Conceitos/repeticao.md |
| [Recorrencias.md](Recorrencias.md) | Comparação normalizada de conteúdo | Repetição | Conceitos/repeticao.md | Conceitos/repeticao.md |
| [Formacoes-Freudianas.md](Formacoes-Freudianas.md) | Classificação fechada (enum, 6 valores) | Ato falho, Chiste, Sonhos, Formação de compromisso, Repetição | Conceitos/{5} | Conceitos/{5} |
| [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md) | Tabela de lookup determinística sobre Recorrência/classificação freudiana | Metonímia, Metáfora, os demais sem representação | Conceitos/metonimia.md, metafora.md | Conceitos/metonimia.md, metafora.md |
| [Circuitos.md](Circuitos.md) | Sequência de `OcorrenciaRecorrencia` em ≥2 Sessões | Repetição, lida como Real | Conceitos/repeticao.md | Conceitos/repeticao.md, pulsao.md |
| [Grafos.md](Grafos.md) | Idêntica à de Circuitos (grafo real); matriz Conceito×Conceito (grafos especificados) | Repetição (real); os 21 conceitos (especificados) | Conceitos/repeticao.md | Matrizes/Conceito-x-Conceito.md |
| [Indicadores.md](Indicadores.md) | Contagem direta sobre registros persistidos | — (infraestrutura de consolidação) | Modelo-Observacional.md §1 | não aplicável |

## O que acontece quando a evidência não existe

Cinco das oito representações têm dimensões documentadas como "Não implementado" (ver cada documento próprio) — nestes casos, a ausência de evidência é declarada explicitamente, nunca preenchida com inferência. Uma representação sem evidência correspondente no código não é descrita como parcialmente disponível de forma vaga: é marcada, item a item, como especificação para sprint futura.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Principios.md](Principios.md)
- [Interface-Analista.md](Interface-Analista.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Modelo-Observacional/README.md](../Modelo-Observacional/README.md)
- [../Modelo-Relacional/README.md](../Modelo-Relacional/README.md)
