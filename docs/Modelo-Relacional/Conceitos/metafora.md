# Metáfora — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md); fenômeno observável em [Modelo-Observacional/Conceitos/metafora.md](../../Modelo-Observacional/Conceitos/metafora.md).

## Relações Científicas

### Conceitos antecedentes

- **[Cadeia significante](cadeia-significante.md)** — a metáfora opera sobre a cadeia significante ([Ontologia-Lacan.md §3.3](../../Ontologia-Lacan.md#33-metáfora)).
- **[Sonhos](sonhos.md)** — releitura estrutural da condensação (*Verdichtung*) do trabalho do sonho ([Ontologia-Lacan.md §3.3](../../Ontologia-Lacan.md#33-metáfora)).

### Conceitos consequentes

Nenhum registrado diretamente — "o sintoma tem estrutura de metáfora" é apontado como consequência clínica geral, sem conceito canônico próprio nesta ontologia ([Ontologia-Lacan.md §3.3](../../Ontologia-Lacan.md#33-metáfora)).

### Conceitos relacionados

Metonímia; Formação de compromisso; Significante (Biblioteca-Teorica/Conceitos/metafora.md, campo "Conceitos relacionados").

### Relações estruturais

Uma das duas leis fundamentais do funcionamento da cadeia significante, ao lado da **Metonímia** ([Ontologia-Lacan.md §3.3, §4](../../Ontologia-Lacan.md#33-metáfora)).

### Relações temporais

Operação de substituição: "o significante substituído não desaparece, mas passa a atuar de forma latente" ([Ontologia-Lacan.md §3.3](../../Ontologia-Lacan.md#33-metáfora)) — dimensão temporal de latência, não de eliminação.

### Relações observacionais

**Efetivamente produzida, por reclassificação indireta** (corrigido na Sprint 30): sempre que uma Recorrência sem circuito tem seu conteúdo classificado como Chiste ou Sonho pelo Motor Freud, `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` devolve o rótulo de metáfora. A observação *direta* do fenômeno de substituição entre dois conteúdos distintos continua fora do alcance do detector atual ([Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)).

### Relações de dependência

Depende de **Cadeia significante**.

### Relações bidirecionais

Metáfora ↔ Metonímia: as duas leis fundamentais, irmãs e complementares, do funcionamento da cadeia significante ([Ontologia-Lacan.md §3.3, §3.4](../../Ontologia-Lacan.md#33-metáfora)).

### Relações não observáveis computacionalmente

A confirmação de que uma substituição discursiva é, de fato, metáfora nunca é automática — apenas candidata; o sistema nunca atribui o estatuto de metáfora confirmada ([Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md)).

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Metáfora → Cadeia significante | *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957) | Jacques Lacan | Ontologia-Lacan.md §3.3 | Dependência |
| Metáfora ↔ Metonímia | *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957) | Jacques Lacan | Ontologia-Lacan.md §3.3, §3.4 | Bidirecional |
| Metáfora → Sonhos (condensação) | *A Interpretação dos Sonhos* (1900); *As Formações do Inconsciente* (Seminário V, 1957-58) | Sigmund Freud / Jacques Lacan | Ontologia-Lacan.md §3.3 | Reorganização estrutural |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Metáfora → Cadeia significante | Fundamental | "Opera sobre a cadeia significante", constitutivo |
| Metáfora ↔ Metonímia | Fundamental | "Duas leis fundamentais", agrupamento explícito (§4) |
| Metáfora → Sonhos (condensação) | Forte | Releitura direta e nomeada da condensação freudiana |

## Natureza

| Relação | Natureza |
|---|---|
| Metáfora → Cadeia significante | Linguística |
| Metáfora ↔ Metonímia | Linguística |
| Metáfora → Sonhos | Clínica |

## Observabilidade

- **Pode ser observada diretamente?** Não.
- **Pode ser inferida computacionalmente?** Sim, indiretamente — por reclassificação de uma classificação freudiana (Chiste/Sonho) já produzida.
- **Depende de validação do analista?** Sim, sempre que sugerida como apoio à escuta.
- **Nunca poderá ser produzida automaticamente?** A metáfora como estrutura confirmada nunca poderá ser produzida automaticamente — apenas candidata; o rótulo indireto já é produzido hoje, mas a confirmação permanece exclusiva do sujeito/analista.

## Motores envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` — fornece a classificação de origem (Chiste/Sonho) que a reclassificação lacaniana consome.
- **Motor Lacan**: `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — produz o rótulo por reclassificação.
- **Memória Discursiva**: nenhum.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum registrado — o rótulo é produzido justamente quando NÃO há circuito.
- **Interface do Analista**: exibe o rótulo e sua fundamentação, quando disparado.
- **Interface do Sujeito**: nenhuma.
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md)
- [Modelo-Observacional/Conceitos/metafora.md](../../Modelo-Observacional/Conceitos/metafora.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [../README.md](../README.md)
- [../Lacan/README.md](../Lacan/README.md)
