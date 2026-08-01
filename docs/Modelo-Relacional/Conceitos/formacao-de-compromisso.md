# Formação de compromisso — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a futura Ontologia computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md](../../Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md); fenômeno observável em [Modelo-Observacional/Conceitos/formacao-de-compromisso.md](../../Modelo-Observacional/Conceitos/formacao-de-compromisso.md).

## Relações Científicas

### Conceitos antecedentes

- **[Pulsão](pulsao.md)** — a formação de compromisso resulta de um conflito entre uma moção inconsciente ligada a uma pulsão e as forças defensivas do recalque ([Ontologia-Freud.md §3.5](../../Ontologia-Freud.md#35-formação-de-compromisso)).
- **[Recalque](recalque.md)** — a formação de compromisso é seu resultado típico ([Ontologia-Freud.md §3.2, §3.5](../../Ontologia-Freud.md#32-recalque)).

### Conceitos consequentes

Nenhum — a Formação de compromisso é categoria geral, não gera consequentes próprios além de suas espécies (relação estrutural, não consequente).

### Conceitos relacionados

Ato falho; Chiste; Sonhos — suas espécies (Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md, campo "Conceitos relacionados").

### Relações estruturais

Categoria geral sob a qual se organizam **Ato falho**, **Chiste** e **Sonhos**, tratados como suas espécies ([Ontologia-Freud.md §3.5, §4](../../Ontologia-Freud.md#35-formação-de-compromisso)).

### Relações temporais

Não descrita como fenômeno de tempo próprio nesta ontologia; suas espécies (Ato falho, Chiste, Sonhos) é que possuem manifestação temporal pontual ou processual.

### Relações observacionais

Observada e classificada indiretamente via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`, sempre por meio de suas quatro espécies — nunca como categoria abstrata isolada ([Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)).

### Relações de dependência

Depende de **Pulsão** e **Recalque** — é o resultado do conflito entre ambos.

### Relações bidirecionais

Nenhuma — a relação com Pulsão e Recalque é assimétrica (resultado, não constituição mútua); a relação com suas espécies é de categoria/instância, não de mutualidade.

### Relações não observáveis computacionalmente

O mecanismo psíquico de conflito e compromisso em si nunca é observável — apenas suas espécies são classificadas, sempre como candidatas, nunca como confirmação de que houve, de fato, formação de compromisso.

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Formação de compromisso → Pulsão, Recalque | *A Repressão* (1915); *Pulsões e seus Destinos* (1915) | Sigmund Freud | Ontologia-Freud.md §3.5 | Dependência (resultado de conflito) |
| Formação de compromisso → Ato falho, Chiste, Sonhos | *Psicopatologia da Vida Cotidiana* (1901); *A Interpretação dos Sonhos* (1900); *Os Chistes e sua Relação com o Inconsciente* (1905) | Sigmund Freud | Ontologia-Freud.md §3.5 | Estrutural (categoria/espécie) |
| Formação de compromisso → Metáfora, Metonímia (releitura lacaniana) | *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957) | Jacques Lacan | Ontologia-Lacan.md §4 | Reorganização estrutural (condensação→metáfora; deslocamento→metonímia) |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Formação de compromisso ↔ Pulsão / Recalque | Fundamental | Definição conceitual constitutiva |
| Formação de compromisso → Ato falho / Chiste / Sonhos | Fundamental | Relação de categoria/espécie, explícita em §4 |
| Formação de compromisso → Metáfora / Metonímia | Moderada | Releitura estrutural indireta, via suas espécies |

## Natureza

| Relação | Natureza |
|---|---|
| Formação de compromisso ↔ Pulsão / Recalque | Estrutural |
| Formação de compromisso → Ato falho / Chiste / Sonhos | Clínica |
| Formação de compromisso → Metáfora / Metonímia | Linguística |

## Observabilidade

- **Pode ser observada diretamente?** Não como categoria — apenas suas espécies são classificadas.
- **Pode ser inferida computacionalmente?** Sim, indiretamente, via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`, sempre como candidata.
- **Depende de validação do analista?** Sim.
- **Nunca poderá ser produzida automaticamente?** A classificação da espécie é produzida automaticamente como candidata; a confirmação de que se trata, de fato, de formação de compromisso nunca é automática.

## Motores envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` / `TipoFormacaoFreudiana` — classifica as quatro espécies.
- **Motor Lacan**: reclassifica Chiste e Sonhos via Metonímia (ver [Metonímia](metonimia.md)).
- **Memória Discursiva**: registra os Eventos Discursivos classificados.
- **Timeline**: exibe as ocorrências classificadas ao longo do tempo.
- **Circuito Pulsional**: nenhum registrado.
- **Interface do Analista**: exibe as classificações.
- **Interface do Sujeito**: nenhuma — Sujeito não visualiza classificações ([Arquitetura-Cientifica.md §2](../../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)).
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md](../../Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md)
- [Modelo-Observacional/Conceitos/formacao-de-compromisso.md](../../Modelo-Observacional/Conceitos/formacao-de-compromisso.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
