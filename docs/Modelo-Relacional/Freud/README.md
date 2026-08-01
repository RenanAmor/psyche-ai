# Modelo Relacional — Freud

> Síntese das relações científicas entre os dez conceitos freudianos canônicos ([Ontologia-Freud.md §3](../../Ontologia-Freud.md#3-conceitos-fundamentais)). Cada relação individual, com fundamentação bibliográfica completa, está documentada em [../Conceitos/](../Conceitos/). Este documento agrupa e organiza essas relações por agrupamento conceitual, não substitui os documentos individuais.

## Os dez conceitos

Inconsciente; Recalque; Pulsão; Desejo (Freud); Formação de compromisso; Ato falho; Chiste; Sonhos; Repetição; Transferência.

## Os quatro agrupamentos ([Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais))

| Agrupamento | Conceitos | Lógica interna |
|---|---|---|
| Núcleo estrutural | [Inconsciente](../Conceitos/inconsciente.md), [Recalque](../Conceitos/recalque.md) | O inconsciente como sistema; o recalque como operação que o constitui dinamicamente |
| Força motriz | [Pulsão](../Conceitos/pulsao.md), [Desejo (Freud)](../Conceitos/desejo-freud.md) | O que impele a vida psíquica a buscar expressão e satisfação |
| Formações e vias de manifestação | [Formação de compromisso](../Conceitos/formacao-de-compromisso.md) (categoria), [Ato falho](../Conceitos/ato-falho.md), [Chiste](../Conceitos/chiste.md), [Sonhos](../Conceitos/sonhos.md) (espécies) | Pontos em que o conflito entre pulsão e recalque se torna observável no discurso |
| Temporalidade e vínculo | [Repetição](../Conceitos/repeticao.md), [Transferência](../Conceitos/transferencia.md) | As formas pelas quais tudo o que precede se inscreve no tempo e na relação com o outro |

## Encadeamento entre agrupamentos

Conforme [Ontologia-Freud.md §4](../../Ontologia-Freud.md#4-relações-conceituais): o recalque opera sobre representantes da pulsão; o resultado desse conflito se expressa como formação de compromisso; o desejo anima particularmente os sonhos; a repetição e a transferência situam todo esse funcionamento no tempo e na relação com um outro.

```
Inconsciente ←→ Recalque
                   │ (opera sobre representantes de)
                   ▼
                Pulsão ←→ Desejo (Freud)
                   │ (destinos possíveis / motor de)
                   ▼
        Formação de compromisso
          ├── Ato falho
          ├── Chiste ──── (compartilha técnicas) ──── Sonhos
          └── (modelo fundador: Sonhos)
                   │
                   ▼
          Repetição ←→ Transferência
```

## Auditoria contra o código real (observabilidade)

Reaproveita a auditoria já feita em [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md), organizada aqui por relação:

- **[Repetição](../Conceitos/repeticao.md)**: único conceito observado, organizado e classificado integralmente por conta própria (`DetectorRecorrencias`).
- **[Ato falho](../Conceitos/ato-falho.md), [Chiste](../Conceitos/chiste.md), [Sonhos](../Conceitos/sonhos.md), [Formação de compromisso](../Conceitos/formacao-de-compromisso.md)**: observados e classificados via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`, sempre como candidatos.
- **[Inconsciente](../Conceitos/inconsciente.md), [Recalque](../Conceitos/recalque.md), [Pulsão](../Conceitos/pulsao.md), [Desejo (Freud)](../Conceitos/desejo-freud.md), [Transferência](../Conceitos/transferencia.md)**: fundamentação teórica de fundo, sem observação computacional própria.

## Relações com a Ontologia Lacan

Todos os dez conceitos freudianos são relidos estruturalmente pela Ontologia Lacan, sem substituição — ver [Ontologia-Lacan.md §4, "Como a Ontologia Lacan reorganiza a Ontologia Freud"](../../Ontologia-Lacan.md#como-a-ontologia-lacan-reorganiza-a-ontologia-freud) e o detalhamento relação a relação em cada documento de [../Conceitos/](../Conceitos/).

## Referências cruzadas do projeto

- [../Conceitos/](../Conceitos/)
- [../Lacan/README.md](../Lacan/README.md)
- [../README.md](../README.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Modelo-Observacional/Freud/README.md](../../Modelo-Observacional/Freud/README.md)
- [Biblioteca-Teorica/Freud/](../../Biblioteca-Teorica/Freud/)
