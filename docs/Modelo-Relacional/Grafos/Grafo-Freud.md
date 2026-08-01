# Grafo de Freud — Especificação

> Especificação, sem implementação, do grafo formado pelos dez conceitos freudianos canônicos e suas relações fundamentadas. Fonte das arestas: [../Matrizes/Conceito-x-Conceito.md, bloco "Freud × Freud"](../Matrizes/Conceito-x-Conceito.md#bloco-freud--freud). Ver [README.md](README.md) para a notação comum.

## Nós (10)

| Nó | Agrupamento ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais)) | Documento |
|---|---|---|
| Inconsciente | Núcleo estrutural | [../Conceitos/inconsciente.md](../Conceitos/inconsciente.md) |
| Recalque | Núcleo estrutural | [../Conceitos/recalque.md](../Conceitos/recalque.md) |
| Pulsão | Força motriz | [../Conceitos/pulsao.md](../Conceitos/pulsao.md) |
| Desejo (Freud) | Força motriz | [../Conceitos/desejo-freud.md](../Conceitos/desejo-freud.md) |
| Formação de compromisso | Formações e vias de manifestação | [../Conceitos/formacao-de-compromisso.md](../Conceitos/formacao-de-compromisso.md) |
| Ato falho | Formações e vias de manifestação | [../Conceitos/ato-falho.md](../Conceitos/ato-falho.md) |
| Chiste | Formações e vias de manifestação | [../Conceitos/chiste.md](../Conceitos/chiste.md) |
| Sonhos | Formações e vias de manifestação | [../Conceitos/sonhos.md](../Conceitos/sonhos.md) |
| Repetição | Temporalidade e vínculo | [../Conceitos/repeticao.md](../Conceitos/repeticao.md) |
| Transferência | Temporalidade e vínculo | [../Conceitos/transferencia.md](../Conceitos/transferencia.md) |

## Arestas (22)

Ver tabela completa em [../Matrizes/Conceito-x-Conceito.md, bloco "Freud × Freud"](../Matrizes/Conceito-x-Conceito.md#bloco-freud--freud) — reproduzida aqui apenas como topologia (sem repetir Natureza/Intensidade):

```
Inconsciente ↔ Recalque
Inconsciente → Formação de compromisso, Ato falho, Chiste, Sonhos
Inconsciente → Repetição, Transferência
Recalque → Pulsão
Recalque → Formação de compromisso, Ato falho, Chiste, Sonhos
Pulsão ↔ Desejo (Freud)
Pulsão → Formação de compromisso, Repetição
Desejo (Freud) → Sonhos, Repetição
Formação de compromisso → Ato falho, Chiste, Sonhos
Chiste ↔ Sonhos
Repetição ↔ Transferência
```

## Propriedades topológicas

- **Grafo conexo**: todo nó é alcançável a partir de qualquer outro (não há componente isolado) — Inconsciente é o nó de maior grau de saída (7 arestas), consistente com sua posição de conceito fundador ([Ontologia-Freud.md §3.1](../../Ontologia-Freud.md#31-inconsciente)).
- **Nó terminal**: Formação de compromisso tem o maior grau de entrada (3: de Inconsciente, Recalque, Pulsão) e é, ele mesmo, origem de 3 arestas de saída (para suas espécies) — funciona como articulador central do agrupamento "formações".
- **Sub-clique**: {Formação de compromisso, Ato falho, Chiste, Sonhos} forma uma estrutura de categoria/espécie, não um clique simétrico — as arestas são direcionais da categoria geral às espécies, exceto Chiste↔Sonhos, que é a única aresta bidirecional entre espécies.
- **Encadeamento linear entre agrupamentos**: Núcleo estrutural → Força motriz → Formações → Temporalidade, conforme descrito em [Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais) — mas não estritamente linear, já que Inconsciente also conecta diretamente às Formações e à Temporalidade.

## Restrição

Especificação apenas. Nenhuma estrutura de dados, biblioteca de grafo ou endpoint foi criado ou alterado nesta Sprint.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Matrizes/Conceito-x-Conceito.md](../Matrizes/Conceito-x-Conceito.md)
- [../Freud/README.md](../Freud/README.md)
- [Grafo-Integrado.md](Grafo-Integrado.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
