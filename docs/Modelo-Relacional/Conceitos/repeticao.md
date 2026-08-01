# Repetição — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a futura Ontologia computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/repeticao.md](../../Biblioteca-Teorica/Conceitos/repeticao.md); fenômeno observável em [Modelo-Observacional/Conceitos/repeticao.md](../../Modelo-Observacional/Conceitos/repeticao.md).

## Relações Científicas

### Conceitos antecedentes

- **[Pulsão](pulsao.md)** — a repetição é um de seus destinos possíveis ([Ontologia-Freud.md §3.9](../../Ontologia-Freud.md#39-repetição)).
- **[Desejo (Freud)](desejo-freud.md)** — relaciona-se como busca de reencontro de uma satisfação perdida ([Ontologia-Freud.md §3.4](../../Ontologia-Freud.md#34-desejo)).

### Conceitos consequentes

- **[Transferência](transferencia.md)** — intersecta-se com a transferência: repetição encenada na relação analítica em lugar de lembrada ([Ontologia-Freud.md §3.9](../../Ontologia-Freud.md#39-repetição)).
- **[Registro Real](registro-real.md)** — releitura lacaniana: a repetição, em sua dimensão que excede o princípio do prazer, antecipa o que Lacan formaliza como Real ([Ontologia-Lacan.md §3.7, §4](../../Ontologia-Lacan.md#37-registro-real)).
- **[Metonímia](metonimia.md)** — reclassificação computacional efetiva no sistema atual (Biblioteca-Teorica/Conceitos/repeticao.md).

### Conceitos relacionados

Transferência; Pulsão; Metonímia (Biblioteca-Teorica/Conceitos/repeticao.md, campo "Conceitos relacionados").

### Relações estruturais

Temporalidade e vínculo com **Transferência** ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais)).

### Relações temporais

Fundamenta diretamente o eixo temporal do modelo computacional ([Modelo-Computacional-Discurso.md §5](../../Modelo-Computacional-Discurso.md#5-temporalidade)): a própria recorrência ao longo do tempo — não apenas o conteúdo repetido — é objeto teórico relevante ([Ontologia-Freud.md §3.9](../../Ontologia-Freud.md#39-repetição)).

### Relações observacionais

**Único conceito observado, organizado e classificado automaticamente por conta própria** no sistema atual — `DetectorRecorrencias`, circuito, grafo D3 — auditado em [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md).

### Relações de dependência

Articula-se com **Pulsão** (um de seus destinos) e com **Desejo (Freud)** (busca de reencontro).

### Relações bidirecionais

Repetição ↔ Transferência: intersecção mútua explícita ([Ontologia-Freud.md §3.9, §3.10](../../Ontologia-Freud.md#39-repetição)) — "o que não é lembrado é repetido na transferência".

### Relações não observáveis computacionalmente

A dimensão "além do princípio do prazer" — a pulsão de morte como explicação última da repetição — nunca é confirmável computacionalmente; o sistema observa a forma (recorrência), nunca a causa pulsional última.

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Repetição → Pulsão | *Além do Princípio do Prazer* (1920) | Sigmund Freud | Ontologia-Freud.md §3.9 | Dependência |
| Repetição ↔ Transferência | *Além do Princípio do Prazer* (1920); *A Dinâmica da Transferência* (1912); *Recordar, Repetir e Elaborar* (1914) | Sigmund Freud | Ontologia-Freud.md §3.9, §3.10 | Bidirecional |
| Repetição → Registro Real (releitura lacaniana) | *O Seminário, Livro VII: A Ética da Psicanálise* (1959-60) | Jacques Lacan | Ontologia-Lacan.md §3.7, §4 | Reorganização estrutural |
| Repetição → Metonímia (reclassificação computacional) | *A Instância da Letra no Inconsciente ou a Razão desde Freud* | Jacques Lacan | Biblioteca-Teorica/Conceitos/repeticao.md | Reclassificação estrutural |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Repetição ↔ Transferência | Fundamental | Agrupamento "temporalidade e vínculo" explícito (§4) |
| Repetição → Pulsão | Forte | "Um de seus destinos possíveis", nomeado |
| Repetição → Registro Real | Forte | Antecipação nomeada explicitamente na reorganização lacaniana |
| Repetição → Metonímia | Fundamental | Única reclassificação lacaniana efetivamente produzida pelo sistema hoje |

## Natureza

| Relação | Natureza |
|---|---|
| Repetição ↔ Transferência | Temporal |
| Repetição → Pulsão | Estrutural |
| Repetição → Registro Real | Topológica |
| Repetição → Metonímia | Observacional |

## Observabilidade

- **Pode ser observada diretamente?** Sim — é o único conceito com observação direta e integral no sistema atual.
- **Pode ser inferida computacionalmente?** Sim — `DetectorRecorrencias`.
- **Depende de validação do analista?** Sim, para qualquer leitura clínica sobre a recorrência observada.
- **Nunca poderá ser produzida automaticamente?** A causa pulsional última (pulsão de morte) nunca é produzida automaticamente — apenas a forma observável (recorrência).

## Motores envolvidos

- **Motor Freud**: `DetectorRecorrencias` — observação, organização e classificação automáticas.
- **Motor Lacan**: reclassifica como Metonímia.
- **Memória Discursiva**: registra as recorrências detectadas.
- **Timeline**: exibe recorrências ao longo do tempo.
- **Circuito Pulsional**: grafo D3 do circuito/trajeto ([Roadmap.md, Sprint 19](../../Roadmap.md#sprint-19--camada-de-visualização-gráfica-fundação--grafo-do-circuitotrajeto)).
- **Interface do Analista**: exibe recorrências, circuito e reclassificação lacaniana.
- **Interface do Sujeito**: nenhuma — recorrências não são expostas ao Sujeito ([Arquitetura-Cientifica.md §2](../../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)).
- **Modo Socrático**: utiliza recorrências para gerar perguntas socráticas.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/repeticao.md](../../Biblioteca-Teorica/Conceitos/repeticao.md)
- [Modelo-Observacional/Conceitos/repeticao.md](../../Modelo-Observacional/Conceitos/repeticao.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [Modelo-Computacional-Discurso.md](../../Modelo-Computacional-Discurso.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
