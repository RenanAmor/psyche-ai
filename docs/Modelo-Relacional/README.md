# Modelo Relacional — Psyche AI

> Base científica que documenta como todos os conceitos da [Biblioteca Teórica](../Biblioteca-Teorica/README.md) se relacionam entre si, sustentando o Motor de Representação. Camada da [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) entre o [Modelo Observacional](../Modelo-Observacional/README.md) (o que pode ser observado, conceito a conceito) e a [Representação Computacional](../Representacao-Computacional/README.md) (Sprint 29 — como as observações e relações chegam a ser apresentadas ao Analista e ao Sujeito). Nenhuma relação neste Modelo foi criada sem fundamentação explícita na literatura já catalogada pela Biblioteca Teórica.

## O que este Modelo é

Uma tradução disciplinada das relações já registradas em [Ontologia-Freud.md §3–4](../Ontologia-Freud.md#3-conceitos-fundamentais) e [Ontologia-Lacan.md §3–4](../Ontologia-Lacan.md#3-conceitos-fundamentais) — e no campo "Conceitos relacionados" de cada documento de [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/) — em um modelo relacional explícito, classificado por tipo, intensidade, natureza e observabilidade. **Não é** interpretação nova, nem resumo opinativo de obra: toda relação aqui documentada já estava registrada, em prosa, nas duas Ontologias ou nos metadados da Biblioteca Teórica; esta Sprint organiza, classifica e torna essas relações auditáveis e consultáveis em matrizes e grafos.

## Por que esta camada existe separada do Modelo Observacional

O Modelo Observacional responde "o que, deste conceito, é fenômeno observável". O Modelo Relacional responde uma pergunta distinta: "como este conceito se conecta aos demais 20 conceitos canônicos — e o que, dessas conexões, o sistema pode ou nunca poderá evidenciar computacionalmente". Nenhum motor de representação (grafo, esquema de dados, matema formal) pode ser desenvolvido sem que essa camada relacional esteja documentada primeiro — mesma obrigatoriedade já estabelecida para o Modelo Observacional em [Arquitetura-Cientifica.md §1](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## Estrutura

| Pasta | Conteúdo |
|---|---|
| [Conceitos/](Conceitos/) | 21 documentos de Modelo Relacional, um por conceito canônico — mesmo escopo 1:1 de [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/) e [Modelo-Observacional/Conceitos/](../Modelo-Observacional/Conceitos/) |
| [Freud/](Freud/README.md) | Síntese das relações entre os dez conceitos freudianos, por agrupamento conceitual |
| [Lacan/](Lacan/README.md) | Síntese das relações entre os onze conceitos lacanianos, por agrupamento conceitual, e tabela completa de como a Ontologia Lacan reorganiza a Ontologia Freud |
| [Matrizes/](Matrizes/README.md) | Seis matrizes: Conceito×Conceito, Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência, Conceito×Observabilidade |
| [Grafos/](Grafos/README.md) | Especificação (sem implementação) de cinco grafos científicos: Freud, Lacan, Integrado, Motores, Conceitos |

## Modelo único de documento (Conceitos/)

Todo documento de `Conceitos/` segue a mesma estrutura fixa, exigida pela Sprint 27:

1. **Relações Científicas** — conceitos antecedentes, consequentes, relacionados; relações estruturais, temporais, observacionais, de dependência, bidirecionais e não observáveis computacionalmente.
2. **Fundamentação** — para cada relação: obra, autor, capítulo (quando aplicável), tipo da relação.
3. **Intensidade** — Fundamental, Forte, Moderada, Fraca ou Contextual.
4. **Natureza** — Estrutural, Clínica, Observacional, Linguística, Temporal ou Topológica.
5. **Observabilidade** — pode ser observada diretamente? pode ser inferida computacionalmente? depende de validação do analista? nunca poderá ser produzida automaticamente?
6. **Motores envolvidos** — Motor Freud, Motor Lacan, Memória Discursiva, Timeline, Circuito Pulsional, Interface do Analista, Interface do Sujeito, demais componentes.

## Critério de relacionamento

Nenhuma relação foi criada por interpretação. Toda relação documentada nesta Sprint decorre de uma das duas fontes primárias:

- **Prosa relacional já registrada** em [Ontologia-Freud.md §3–4](../Ontologia-Freud.md#3-conceitos-fundamentais) (por conceito, o campo "Relação com os demais conceitos", e o §4 "Relações conceituais") e em [Ontologia-Lacan.md §3–4](../Ontologia-Lacan.md#3-conceitos-fundamentais) (por conceito, os campos "Relação com os demais conceitos" e "Relação com a Ontologia Freud", e o §4, incluindo "Como a Ontologia Lacan reorganiza a Ontologia Freud").
- **Metadados já catalogados** no campo "Conceitos relacionados" de cada documento de [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/).

Nenhuma obra nova foi consultada ou citada nesta Sprint além das já catalogadas em [Biblioteca-Teorica/](../Biblioteca-Teorica/README.md); nenhuma relação foi inferida sem essa origem explícita.

## Panorama desta Sprint

- **21/21 conceitos** com Modelo Relacional completo.
- **Relação bidirecional mais forte da Biblioteca**: Significante ↔ Cadeia significante, Metáfora ↔ Metonímia, Inconsciente ↔ Recalque, Repetição ↔ Transferência, Falta ↔ Objeto a, Outro ↔ Desejo lacaniano — seis pares mutuamente constitutivos, todos classificados como "Fundamental".
- **Único conceito com observação relacional direta e integral**: [Repetição](Conceitos/repeticao.md) — as demais 20 relações de observabilidade permanecem "Não" ou "Parcial", reproduzindo fielmente o panorama já auditado em [Modelo-Observacional/README.md](../Modelo-Observacional/README.md).
- **Relações "Não observáveis computacionalmente" são maioria absoluta**: dos 21 conceitos, 15 têm todas as suas relações científicas marcadas como computacionalmente não observáveis (as exceções são [Formação de compromisso](Conceitos/formacao-de-compromisso.md), [Ato falho](Conceitos/ato-falho.md), [Chiste](Conceitos/chiste.md), [Sonhos](Conceitos/sonhos.md), [Repetição](Conceitos/repeticao.md) e [Metonímia](Conceitos/metonimia.md), todos com pelo menos uma relação observável ou inferível) — reflexo direto dos limites já estabelecidos em [Documento-Mestre.md §6.5](../Documento-Mestre.md#65-limites-do-sistema) e [Ontologia-Lacan.md §5](../Ontologia-Lacan.md#5-limites).

## Restrições desta Sprint

Nenhuma interpretação foi escrita. Nenhuma obra foi resumida de forma opinativa. Nenhum motor foi implementado. Nenhum código, API, banco de dados ou teste foi alterado. Esta Sprint é exclusivamente documental — Sprint científica de organização relacional, não uma sprint de engenharia.

## Representação Computacional (camada seguinte)

Adicionado na Sprint 29 — [Representacao-Computacional/](../Representacao-Computacional/README.md) documenta como as relações aqui classificadas (matrizes e grafos científicos) chegam a ser efetivamente apresentadas ao Analista, através de oito representações (Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos, Indicadores), e por que nenhuma delas alcança o Sujeito. Nenhum Motor de Representação pode ser desenvolvido sem que essa camada esteja documentada primeiro, mesma obrigatoriedade já registrada em [Arquitetura-Cientifica.md §1](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## Referências cruzadas do projeto

- [Conceitos/](Conceitos/)
- [Freud/README.md](Freud/README.md)
- [Lacan/README.md](Lacan/README.md)
- [Matrizes/README.md](Matrizes/README.md)
- [Grafos/README.md](Grafos/README.md)
- [../Biblioteca-Teorica/README.md](../Biblioteca-Teorica/README.md)
- [../Modelo-Observacional/README.md](../Modelo-Observacional/README.md)
- [../Representacao-Computacional/README.md](../Representacao-Computacional/README.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Ontologia-Freud.md](../Ontologia-Freud.md)
- [../Ontologia-Lacan.md](../Ontologia-Lacan.md)
- [../Roadmap.md](../Roadmap.md)
