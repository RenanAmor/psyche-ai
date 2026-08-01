# Grafo dos Conceitos (Agrupamentos) — Especificação

> Especificação, sem implementação, do grafo de segundo nível formado pelos **sete agrupamentos conceituais** já nomeados em [Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais) e [Ontologia-Lacan.md §4](../../Ontologia-Lacan.md#4-relações-conceituais) — não os 21 conceitos individuais (isso é [Grafo-Integrado.md](Grafo-Integrado.md)), mas os clusters aos quais pertencem. Este grafo responde a uma pergunta distinta: como as grandes divisões teóricas se encadeiam entre si, independentemente do detalhe de cada conceito.

## Nós (7)

| Nó (agrupamento) | Polo | Conceitos membros |
|---|---|---|
| Núcleo estrutural | Freud | Inconsciente, Recalque |
| Força motriz | Freud | Pulsão, Desejo (Freud) |
| Formações e vias de manifestação | Freud | Formação de compromisso, Ato falho, Chiste, Sonhos |
| Temporalidade e vínculo | Freud | Repetição, Transferência |
| Estrutura da linguagem | Lacan | Significante, Cadeia significante, Metáfora, Metonímia |
| Registros (RSI) | Lacan | Registro Simbólico, Registro Imaginário, Registro Real |
| Sujeito e falta | Lacan | Outro, Falta, Objeto a, Desejo lacaniano |

## Arestas

### Internas ao polo Freud ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais))

```
Núcleo estrutural → Força motriz          (recalque opera sobre representantes da pulsão)
Força motriz → Formações e vias de manifestação   (formação de compromisso resulta do conflito pulsão/recalque)
Núcleo estrutural → Formações e vias de manifestação   (pressuposto teórico direto)
Formações e vias de manifestação → Temporalidade e vínculo   (repetição e transferência situam o funcionamento no tempo)
Núcleo estrutural → Temporalidade e vínculo   (retorno do inconsciente no tempo e na relação)
```

### Internas ao polo Lacan ([Ontologia-Lacan.md §4](../../Ontologia-Lacan.md#4-relações-conceituais))

```
Estrutura da linguagem → Registros (RSI)   (a cadeia significante opera no registro Simbólico)
Estrutura da linguagem → Sujeito e falta   (todo significante deixa um resto: a falta)
Sujeito e falta → Estrutura da linguagem   (o Outro é o tesouro do qual a cadeia se extrai)
```

### Entre os dois polos (reorganização, [Ontologia-Lacan.md §4, "Como a Ontologia Lacan reorganiza a Ontologia Freud"](../../Ontologia-Lacan.md#como-a-ontologia-lacan-reorganiza-a-ontologia-freud))

```
Núcleo estrutural (Freud) → Registros (RSI, Lacan)               (Inconsciente/Recalque lidos como efeito do Simbólico)
Formações e vias de manifestação (Freud) → Estrutura da linguagem (Lacan)   (condensação→Metáfora; deslocamento→Metonímia)
Força motriz (Freud) → Sujeito e falta (Lacan)                    (Pulsão→Objeto a; Desejo→Falta+Desejo lacaniano)
Temporalidade e vínculo (Freud) → Registros (RSI, Lacan)          (Repetição antecipa o Real)
Temporalidade e vínculo (Freud) → Sujeito e falta (Lacan)         (Transferência lida à luz do Outro)
```

## Propriedades topológicas

- **Grafo conexo, direcionado predominantemente Freud → Lacan**: das 5 arestas entre polos, todas partem de um agrupamento freudiano para um lacaniano — reflexo direto de [Ontologia-Lacan.md §1](../../Ontologia-Lacan.md#1-objetivo-da-ontologia): "esta ontologia complementa, reorganiza e amplia a Ontologia Freud — não a substitui".
- **Agrupamento de maior grau de saída entre polos**: Formações e vias de manifestação e Temporalidade e vínculo (2 arestas cada) — os dois agrupamentos freudianos mais amplamente relidos estruturalmente por Lacan.
- **Único ciclo do grafo**: Estrutura da linguagem ↔ Sujeito e falta (via Registros como intermediário indireto) — não há ciclo de dois nós direto, mas um ciclo de três (Estrutura da linguagem → Sujeito e falta → Estrutura da linguagem, com Registros participando da primeira perna).
- **Nível de abstração**: este grafo tem 1/3 dos nós do [Grafo-Integrado.md](Grafo-Integrado.md) (7 vs. 21) e é obtido dele por contração de cada agrupamento em um único nó — toda aresta aqui documentada é a generalização de uma ou mais arestas específicas já listadas em [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md).

## Restrição

Especificação apenas. Este grafo é uma camada de leitura sobre relações já fundamentadas — nenhuma relação nova entre agrupamentos foi proposta além do que já está registrado, em prosa, nos §4 das duas Ontologias.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Grafo-Integrado.md](Grafo-Integrado.md)
- [../Freud/README.md](../Freud/README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
