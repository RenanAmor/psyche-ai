# Pulsão — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a futura Ontologia computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/pulsao.md](../../Biblioteca-Teorica/Conceitos/pulsao.md); fenômeno observável em [Modelo-Observacional/Conceitos/pulsao.md](../../Modelo-Observacional/Conceitos/pulsao.md).

## Relações Científicas

### Conceitos antecedentes

Nenhum. A Pulsão é conceito-limite entre o somático e o psíquico, tomado em sua formulação própria ([Ontologia-Freud.md §3.3](../../Ontologia-Freud.md#33-pulsão)).

### Conceitos consequentes

- **[Desejo (Freud)](desejo-freud.md)** — articula-se à pulsão como sua expressão psíquica, sem se confundir com ela ([Ontologia-Freud.md §3.3](../../Ontologia-Freud.md#33-pulsão)).
- **[Recalque](recalque.md)** — atua sobre representantes psíquicos da pulsão (relação inversa de dependência).
- **[Formação de compromisso](formacao-de-compromisso.md)** — formações de compromisso, sintomas e demais formações são destinos possíveis da pulsão ([Ontologia-Freud.md §3.3](../../Ontologia-Freud.md#33-pulsão)).
- **[Repetição](repeticao.md)** — um de seus destinos possíveis ([Ontologia-Freud.md §3.9](../../Ontologia-Freud.md#39-repetição)).
- **[Objeto a](objeto-a.md)** — releitura lacaniana: o objeto contingente que caracteriza a pulsão é o antecedente do objeto a ([Ontologia-Lacan.md §4](../../Ontologia-Lacan.md#4-relações-conceituais)).

### Conceitos relacionados

Desejo (Freud); Repetição; Formação de compromisso (Biblioteca-Teorica/Conceitos/pulsao.md, campo "Conceitos relacionados").

### Relações estruturais

Força motriz com **Desejo (Freud)** ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais)): "o que impele a vida psíquica a buscar expressão e satisfação".

### Relações temporais

A pulsão é força **constante** (não instintiva/pontual) — sustenta teoricamente a ideia de elementos discursivos que se apresentam com insistência ao longo do tempo, retornando e se deslocando ([Biblioteca-Teorica/Conceitos/pulsao.md](../../Biblioteca-Teorica/Conceitos/pulsao.md)).

### Relações observacionais

Nenhuma direta — fundamentação teórica de fundo para `DetectorRecorrencias`, sem que o conceito seja nomeado na saída do sistema (Biblioteca-Teorica/Conceitos/pulsao.md, campo "Motores impactados").

### Relações de dependência

Nenhuma antecedente direta; é conceito-limite fundante. O Recalque depende dela (opera sobre seus representantes).

### Relações bidirecionais

Pulsão ↔ Desejo (Freud): força motriz mútua ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais)).

### Relações não observáveis computacionalmente

O objeto pulsional — "o elemento mais variável e contingente da pulsão" ([Ontologia-Freud.md §3.3](../../Ontologia-Freud.md#33-pulsão)) — nunca é identificável computacionalmente; apenas a insistência formal (recorrência) é tratável, sem nomear a pulsão.

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Pulsão → Desejo (Freud) | *Pulsões e seus Destinos* (1915); *A Interpretação dos Sonhos* (1900) | Sigmund Freud | Ontologia-Freud.md §3.3, §3.4 | Força motriz |
| Pulsão → Recalque | *Pulsões e seus Destinos* (1915) | Sigmund Freud | Ontologia-Freud.md §3.2 | Dependência (objeto de operação) |
| Pulsão → Formação de compromisso, Repetição | *Três Ensaios sobre a Teoria da Sexualidade* (1905); *Além do Princípio do Prazer* (1920) | Sigmund Freud | Ontologia-Freud.md §3.3, §3.9 | Destino possível |
| Pulsão → Objeto a (releitura lacaniana) | *O Seminário, Livro X: A Angústia* (1962-63) | Jacques Lacan | Ontologia-Lacan.md §3.9, §4 | Reorganização estrutural |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Pulsão ↔ Desejo (Freud) | Fundamental | Agrupamento "força motriz" explícito (§4) |
| Pulsão → Recalque | Forte | Objeto direto da operação do recalque |
| Pulsão → Formação de compromisso / Repetição | Moderada | "Destinos possíveis", não únicos |
| Pulsão → Objeto a | Contextual | Releitura de outra tradição teórica |

## Natureza

| Relação | Natureza |
|---|---|
| Pulsão ↔ Desejo (Freud) | Estrutural |
| Pulsão → Recalque | Estrutural |
| Pulsão → Formação de compromisso / Repetição | Clínica |
| Pulsão → Objeto a | Linguística |

## Observabilidade

- **Pode ser observada diretamente?** Não.
- **Pode ser inferida computacionalmente?** Não — apenas sua insistência formal (recorrência), nunca a pulsão como tal.
- **Depende de validação do analista?** Sim, quando associada indiretamente a padrões de recorrência.
- **Nunca poderá ser produzida automaticamente?** Correto — o objeto pulsional nunca é identificável pelo sistema.

## Motores envolvidos

- **Motor Freud**: nenhum implementado que nomeie o conceito — fundamentação teórica de fundo para `DetectorRecorrencias`.
- **Motor Lacan**: nenhum.
- **Memória Discursiva**: nenhum.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nome do componente inspirado no conceito, mas sem operacionalização direta do conceito teórico — ver [Roadmap.md, Sprint 19](../../Roadmap.md#sprint-19--camada-de-visualização-gráfica-fundação--grafo-do-circuitotrajeto).
- **Interface do Analista**: nenhuma.
- **Interface do Sujeito**: nenhuma.
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/pulsao.md](../../Biblioteca-Teorica/Conceitos/pulsao.md)
- [Modelo-Observacional/Conceitos/pulsao.md](../../Modelo-Observacional/Conceitos/pulsao.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
