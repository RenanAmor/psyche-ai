# Ato falho — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a futura Ontologia computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/ato-falho.md](../../Biblioteca-Teorica/Conceitos/ato-falho.md); fenômeno observável em [Modelo-Observacional/Conceitos/ato-falho.md](../../Modelo-Observacional/Conceitos/ato-falho.md).

## Relações Científicas

### Conceitos antecedentes

- **[Formação de compromisso](formacao-de-compromisso.md)** — o ato falho é espécie de formação de compromisso ([Ontologia-Freud.md §3.6](../../Ontologia-Freud.md#36-ato-falho)).
- **[Recalque](recalque.md)** — ligado ao que retorna ([Ontologia-Freud.md §3.6](../../Ontologia-Freud.md#36-ato-falho)).
- **[Desejo (Freud)](desejo-freud.md)** — ligado ao que busca expressão ([Ontologia-Freud.md §3.6](../../Ontologia-Freud.md#36-ato-falho)).

### Conceitos consequentes

Nenhum registrado — o ato falho é ele mesmo uma das vias de manifestação terminais nesta ontologia.

### Conceitos relacionados

Formação de compromisso; Recalque; Desejo (Freud) (Biblioteca-Teorica/Conceitos/ato-falho.md, campo "Conceitos relacionados").

### Relações estruturais

Espécie de **Formação de compromisso**, ao lado de Chiste e Sonhos ([Ontologia-Freud.md §3.5, §4](../../Ontologia-Freud.md#35-formação-de-compromisso)).

### Relações temporais

Manifestação pontual — erro de fala, ação, memória ou escrita interrompendo um ato conscientemente pretendido, no momento em que ocorre ([Ontologia-Freud.md §3.6](../../Ontologia-Freud.md#36-ato-falho)).

### Relações observacionais

Observado e classificado via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana` — uma das quatro espécies efetivamente auditadas contra o código ([Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)).

### Relações de dependência

Depende de **Formação de compromisso** (é sua espécie), que por sua vez depende de Pulsão e Recalque.

### Relações bidirecionais

Nenhuma — relação de espécie/categoria com Formação de compromisso, não mútua.

### Relações não observáveis computacionalmente

A intenção inconsciente subjacente ao ato falho nunca é inferível pelo sistema — apenas a ocorrência formal (interrupção, autocorreção, desvio) é preservada como classe de Evento Discursivo ([Ontologia-Freud.md §3.6](../../Ontologia-Freud.md#36-ato-falho)).

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Ato falho → Formação de compromisso | *Psicopatologia da Vida Cotidiana* (1901) | Sigmund Freud | Ontologia-Freud.md §3.5, §3.6 | Estrutural (espécie) |
| Ato falho → Recalque | *A Repressão* (1915) | Sigmund Freud | Ontologia-Freud.md §3.6 | Dependência |
| Ato falho → Desejo (Freud) | *Psicopatologia da Vida Cotidiana* (1901) | Sigmund Freud | Ontologia-Freud.md §3.6 | Dependência |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Ato falho → Formação de compromisso | Fundamental | Relação de espécie explícita em §4 |
| Ato falho → Recalque | Forte | Nomeada diretamente na definição conceitual |
| Ato falho → Desejo (Freud) | Moderada | Ligação secundária ("o que busca expressão") |

## Natureza

| Relação | Natureza |
|---|---|
| Ato falho → Formação de compromisso | Estrutural |
| Ato falho → Recalque | Clínica |
| Ato falho → Desejo (Freud) | Clínica |

## Observabilidade

- **Pode ser observada diretamente?** Parcialmente — a ocorrência formal (interrupção, autocorreção, desvio) é observável no discurso registrado; a intenção inconsciente subjacente, não.
- **Pode ser inferida computacionalmente?** Sim, como candidata, via `ClassificadorFreudianoLLM`.
- **Depende de validação do analista?** Sim.
- **Nunca poderá ser produzida automaticamente?** A intenção inconsciente subjacente nunca é produzida automaticamente — apenas a classificação formal candidata.

## Motores envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` / `TipoFormacaoFreudiana`.
- **Motor Lacan**: nenhum registrado.
- **Memória Discursiva**: registra o Evento Discursivo classificado.
- **Timeline**: exibe ocorrências.
- **Circuito Pulsional**: nenhum registrado.
- **Interface do Analista**: exibe a classificação.
- **Interface do Sujeito**: nenhuma.
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/ato-falho.md](../../Biblioteca-Teorica/Conceitos/ato-falho.md)
- [Modelo-Observacional/Conceitos/ato-falho.md](../../Modelo-Observacional/Conceitos/ato-falho.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
