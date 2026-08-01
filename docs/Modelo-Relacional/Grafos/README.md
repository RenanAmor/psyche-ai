# Grafos — Modelo Relacional

> Especificação científica de cinco grafos — **sem implementação**. Nenhum destes documentos define estrutura de dados, biblioteca de grafo, esquema de banco ou componente visual; cada um especifica nós, arestas e propriedades topológicas a partir das relações já fundamentadas em [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md) e [../Conceitos/](../Conceitos/). A decisão de como (ou se) qualquer um destes grafos se torna representação computacional real pertence a uma sprint técnica futura, sujeita à mesma [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) já exigida para todo o resto do sistema — ver precedente em [Roadmap.md, "Sprints futuras"](../../Roadmap.md#sprints-futuras-não-planejadas-em-detalhe-nesta-fase), que já registra a "Cadeia de significantes (Lacan) como matema formal" como pendente de sprint própria.

## Os cinco grafos

| Grafo | Escopo | Nós | Arestas (fonte) |
|---|---|---|---|
| [Grafo-Freud.md](Grafo-Freud.md) | Os dez conceitos freudianos | 10 | Bloco "Freud × Freud" da matriz Conceito×Conceito |
| [Grafo-Lacan.md](Grafo-Lacan.md) | Os onze conceitos lacanianos | 11 | Bloco "Lacan × Lacan" da matriz Conceito×Conceito |
| [Grafo-Integrado.md](Grafo-Integrado.md) | Os 21 conceitos canônicos | 21 | Todos os três blocos da matriz Conceito×Conceito |
| [Grafo-Motores.md](Grafo-Motores.md) | Conceitos × componentes do PsycheAI | 21 conceitos + 8 componentes | Matriz Motor×Conceito |
| [Grafo-Conceitos.md](Grafo-Conceitos.md) | Os sete agrupamentos conceituais (nível acima do Integrado) | 7 | §4 de [Ontologia-Freud.md](../../Ontologia-Freud.md#4-relações-conceituais) e [Ontologia-Lacan.md](../../Ontologia-Lacan.md#4-relações-conceituais) |

## Notação comum

- **Nó**: um conceito canônico (ou, no Grafo dos Motores, também um componente do sistema; no Grafo dos Conceitos, um agrupamento).
- **Aresta direcional** (`→`): relação de antecedência/consequência, conforme a seção "Conceitos antecedentes"/"Conceitos consequentes" de cada documento em [../Conceitos/](../Conceitos/).
- **Aresta bidirecional** (`↔`): relação mutuamente constitutiva, conforme a seção "Relações bidirecionais".
- **Peso da aresta**: a Intensidade já classificada (Fundamental > Forte > Moderada > Fraca > Contextual), preservada como propriedade da aresta, nunca recalculada.
- **Rótulo da aresta**: a Natureza já classificada (Estrutural, Clínica, Observacional, Linguística, Temporal, Topológica).

## Por que "especificação", nunca "implementação"

Esta Sprint é exclusivamente científica: nenhum grafo aqui documentado corresponde a uma estrutura de dados real, biblioteca (D3, Neo4j, networkx) ou endpoint. O único grafo hoje efetivamente implementado no PsycheAI — o grafo D3 do circuito/trajeto de recorrências (Sprint 19) — representa **ocorrências no tempo**, não a estrutura relacional entre conceitos aqui especificada; a relação entre os dois está registrada no [Grafo-Motores.md](Grafo-Motores.md).

## Referências cruzadas do projeto

- [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md)
- [../Matrizes/Motor-x-Conceito.md](../Matrizes/Motor-x-Conceito.md)
- [../Conceitos/](../Conceitos/)
- [../Freud/README.md](../Freud/README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [../README.md](../README.md)
- [Roadmap.md](../../Roadmap.md)
