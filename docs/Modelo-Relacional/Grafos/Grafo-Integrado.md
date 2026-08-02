# Grafo Integrado — Especificação

> Especificação, sem implementação, do grafo formado pelos 21 conceitos canônicos da Biblioteca Teórica (10 freudianos + 11 lacanianos) e todas as relações fundamentadas entre eles — a união de [Grafo-Freud.md](Grafo-Freud.md), [Grafo-Lacan.md](Grafo-Lacan.md) e as arestas de reorganização/reclassificação entre os dois polos. Fonte: [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md), os três blocos.

## Nós (21)

Os dez nós de [Grafo-Freud.md](Grafo-Freud.md) + os onze nós de [Grafo-Lacan.md](Grafo-Lacan.md), sem duplicação — ver tabelas de nós em cada um.

## Arestas (51)

- 22 arestas internas ao polo Freud (ver [Grafo-Freud.md](Grafo-Freud.md)).
- 18 arestas internas ao polo Lacan (ver [Grafo-Lacan.md](Grafo-Lacan.md); contagem corrigida na Sprint 30 — 22+18+11=51, reconciliando o total abaixo, que já estava correto).
- **11 arestas de reorganização/reclassificação entre os dois polos** (bloco "Freud × Lacan" de [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md#bloco-freud--lacan-reorganização-e-reclassificação)):

```
Inconsciente → Registro Simbólico
Recalque → Registro Simbólico
Sonhos → Metáfora
Sonhos → Metonímia
Pulsão → Objeto a
Desejo (Freud) → Desejo lacaniano
Repetição → Registro Real
Repetição → Metonímia
Chiste → Metonímia
Transferência → Outro
Falta → Desejo (Freud)
```

## Propriedades topológicas

- **Grafo conexo único**: as 11 arestas de ponte eliminam qualquer separação entre os subgrafos de Freud e de Lacan — o Grafo Integrado não é a simples justaposição dos dois grafos polares, mas uma estrutura única.
- **Nós-ponte** (participam de arestas de reorganização/reclassificação): Inconsciente, Recalque, Sonhos, Pulsão, Desejo (Freud), Repetição, Chiste, Transferência, Falta (Freud → Lacan) e Registro Simbólico, Metáfora, Metonímia, Objeto a, Desejo lacaniano, Outro (recebem de Freud). Sonhos e Repetição são os únicos nós com **duas** arestas de ponte cada.
- **Único ciclo de reclassificação fechado observável hoje**: Repetição → Metonímia → Desejo lacaniano é a única cadeia de três arestas que atravessa fundamentação teórica (Ontologia) e observação computacional real (Metonímia é produzida por reclassificação de Repetição) — nenhum outro caminho no grafo tem essa dupla natureza teórica-e-observacional.
- **Assimetria estrutural**: o polo Freud tem mais arestas internas por nó (2,2 arestas/nó em média) que o polo Lacan (1,55 arestas/nó em média) — reflexo de a Ontologia Freud descrever quatro agrupamentos encadeados linearmente, contra três agrupamentos lacanianos com maior densidade interna em "Sujeito e falta" mas menor acoplamento entre os três agrupamentos.

## O que este grafo não representa

Não representa o Freud Engine nem o Lacan Engine como pipeline de execução (isso é [Arquitetura.md §4](../../Arquitetura.md#4-visão-arquitetural-de-longo-prazo--motores-conceituais)); não representa o grafo D3 de circuito/trajeto já implementado (Sprint 19), que opera sobre ocorrências no tempo, não sobre conceitos; não atribui peso numérico às arestas além da classificação qualitativa de Intensidade já registrada em cada documento de [../Conceitos/](../Conceitos/).

## Restrição

Especificação apenas. Nenhuma estrutura de dados, biblioteca de grafo ou endpoint foi criado ou alterado nesta Sprint.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Grafo-Freud.md](Grafo-Freud.md)
- [Grafo-Lacan.md](Grafo-Lacan.md)
- [Grafo-Motores.md](Grafo-Motores.md)
- [Grafo-Conceitos.md](Grafo-Conceitos.md)
- [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md)
- [../README.md](../README.md)
