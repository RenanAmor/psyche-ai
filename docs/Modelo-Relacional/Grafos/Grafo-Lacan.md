# Grafo de Lacan — Especificação

> Especificação, sem implementação, do grafo formado pelos onze conceitos lacanianos canônicos e suas relações fundamentadas. Fonte das arestas: [../Matrizes/Conceito-x-Conceito.md, bloco "Lacan × Lacan"](../Matrizes/Conceito-x-Conceito.md#bloco-lacan--lacan). Ver [README.md](README.md) para a notação comum.

## Nós (11)

| Nó | Agrupamento ([Ontologia-Lacan.md §4](../../Ontologia-Lacan.md#4-relações-conceituais)) | Documento |
|---|---|---|
| Significante | Estrutura da linguagem | [../Conceitos/significante.md](../Conceitos/significante.md) |
| Cadeia significante | Estrutura da linguagem | [../Conceitos/cadeia-significante.md](../Conceitos/cadeia-significante.md) |
| Metáfora | Estrutura da linguagem | [../Conceitos/metafora.md](../Conceitos/metafora.md) |
| Metonímia | Estrutura da linguagem | [../Conceitos/metonimia.md](../Conceitos/metonimia.md) |
| Registro Simbólico | Registros (RSI) | [../Conceitos/registro-simbolico.md](../Conceitos/registro-simbolico.md) |
| Registro Imaginário | Registros (RSI) | [../Conceitos/registro-imaginario.md](../Conceitos/registro-imaginario.md) |
| Registro Real | Registros (RSI) | [../Conceitos/registro-real.md](../Conceitos/registro-real.md) |
| Outro | Sujeito e falta | [../Conceitos/outro.md](../Conceitos/outro.md) |
| Falta | Sujeito e falta | [../Conceitos/falta.md](../Conceitos/falta.md) |
| Objeto a | Sujeito e falta | [../Conceitos/objeto-a.md](../Conceitos/objeto-a.md) |
| Desejo lacaniano | Sujeito e falta | [../Conceitos/desejo-lacaniano.md](../Conceitos/desejo-lacaniano.md) |

## Arestas (17)

Ver tabela completa em [../Matrizes/Conceito-x-Conceito.md, bloco "Lacan × Lacan"](../Matrizes/Conceito-x-Conceito.md#bloco-lacan--lacan):

```
Significante ↔ Cadeia significante
Significante → Falta, Objeto a, Outro
Cadeia significante → Metáfora, Metonímia
Metáfora ↔ Metonímia
Registro Simbólico ↔ Registro Imaginário
Registro Simbólico → Registro Real, Cadeia significante
Registro Real ↔ Objeto a
Outro → Cadeia significante
Outro ↔ Desejo lacaniano
Outro → Falta
Objeto a ↔ Falta
Objeto a → Desejo lacaniano
Falta → Desejo lacaniano
Metonímia → Desejo lacaniano
```

## Propriedades topológicas

- **Grafo conexo**: todo nó é alcançável a partir de qualquer outro.
- **Nó de maior grau de saída**: Significante (4 arestas) — consistente com sua posição de unidade fundante da ordem simbólica ([Ontologia-Lacan.md §3.1](../../Ontologia-Lacan.md#31-significante)).
- **Nó de maior grau de entrada**: Desejo lacaniano (4 arestas: de Outro, Objeto a, Falta, Metonímia) — o conceito mais dependente da Ontologia Lacan, reunindo as quatro condições descritas em [../Conceitos/desejo-lacaniano.md](../Conceitos/desejo-lacaniano.md).
- **Ponte entre agrupamentos**: Registro Simbólico → Cadeia significante e Outro → Cadeia significante são as únicas arestas que ligam o agrupamento "Registros" e o agrupamento "Sujeito e falta" ao agrupamento "Estrutura da linguagem" — sem essas duas arestas, os três agrupamentos formariam componentes desconexos.
- **Triângulo Falta–Objeto a–Desejo lacaniano**: os três nós do núcleo do agrupamento "Sujeito e falta" formam um subgrafo totalmente conectado (considerando arestas direcionais convergentes), refletindo a articulação descrita em [Ontologia-Lacan.md §4](../../Ontologia-Lacan.md#4-relações-conceituais): "é dessa falta, articulada ao Outro, que o desejo se sustenta".

## Restrição

Especificação apenas. [Roadmap.md, "Sprints futuras"](../../Roadmap.md#sprints-futuras-não-planejadas-em-detalhe-nesta-fase) já registra que a formalização matemática desta estrutura (S1↔S2, grafo do desejo) permanece pendente de sprint técnica própria, distinta desta especificação relacional.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Grafo-Integrado.md](Grafo-Integrado.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
