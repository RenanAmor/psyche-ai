# Sonhos — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a futura Ontologia computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/sonhos.md](../../Biblioteca-Teorica/Conceitos/sonhos.md); fenômeno observável em [Modelo-Observacional/Conceitos/sonhos.md](../../Modelo-Observacional/Conceitos/sonhos.md).

## Relações Científicas

### Conceitos antecedentes

- **[Desejo (Freud)](desejo-freud.md)** — os sonhos são realização de desejo ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).
- **[Recalque](recalque.md)** — razão da distorção entre conteúdo latente e manifesto ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).

### Conceitos consequentes

- **[Chiste](chiste.md)** — os sonhos são modelo para o chiste como formação de compromisso ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).
- **[Formação de compromisso](formacao-de-compromisso.md)** — os sonhos são o modelo fundador de todo o mecanismo de formação de compromisso ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).
- **[Metáfora](metafora.md)** — a condensação do trabalho do sonho é formalizada por Lacan como metáfora ([Ontologia-Lacan.md §3.3](../../Ontologia-Lacan.md#33-metáfora)).
- **[Metonímia](metonimia.md)** — o deslocamento do trabalho do sonho é formalizado por Lacan como metonímia ([Ontologia-Lacan.md §3.4](../../Ontologia-Lacan.md#34-metonímia)).

### Conceitos relacionados

Formação de compromisso; Desejo (Freud); Chiste (Biblioteca-Teorica/Conceitos/sonhos.md, campo "Conceitos relacionados").

### Relações estruturais

Modelo fundador de todo o mecanismo de **Formação de compromisso** e do funcionamento do processo primário ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).

### Relações temporais

Processo com antes/depois — transforma pensamentos oníricos latentes em conteúdo manifesto, via condensação, deslocamento, consideração pela figurabilidade e elaboração secundária ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).

### Relações observacionais

Observado e classificado via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana` — uma das quatro espécies efetivamente auditadas contra o código ([Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)); o sistema apenas registra o relato como material discursivo, nunca interpreta o sonho relatado.

### Relações de dependência

Depende de **Desejo (Freud)** e **Recalque**.

### Relações bidirecionais

Sonhos ↔ Chiste: modelo mútuo de técnicas compartilhadas (ver [Chiste](chiste.md)).

### Relações não observáveis computacionalmente

O conteúdo onírico latente nunca é interpretável pelo sistema — apenas o relato manifesto é registrado como material discursivo, sem que o sistema jamais interprete o sonho relatado ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md#38-sonhos)).

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Sonhos → Desejo (Freud) | *A Interpretação dos Sonhos* (1900) | Sigmund Freud | Ontologia-Freud.md §3.4, §3.8 | Dependência |
| Sonhos → Recalque | *A Interpretação dos Sonhos* (1900) | Sigmund Freud | Ontologia-Freud.md §3.8 | Dependência |
| Sonhos → Chiste, Formação de compromisso | *A Interpretação dos Sonhos* (1900); *Os Chistes e sua Relação com o Inconsciente* (1905) | Sigmund Freud | Ontologia-Freud.md §3.8 | Estrutural (modelo fundador) |
| Sonhos → Metáfora (condensação) | *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957) | Jacques Lacan | Ontologia-Lacan.md §3.3 | Reorganização estrutural |
| Sonhos → Metonímia (deslocamento) | *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957) | Jacques Lacan | Ontologia-Lacan.md §3.4 | Reorganização estrutural |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Sonhos ↔ Desejo (Freud) | Fundamental | "Realização de desejo", núcleo definitório |
| Sonhos → Recalque | Forte | "Razão da distorção", nomeada diretamente |
| Sonhos → Formação de compromisso | Fundamental | "Modelo fundador", relação estrutural explícita |
| Sonhos → Chiste | Forte | Técnicas compartilhadas nomeadas |
| Sonhos → Metáfora / Metonímia | Forte | Releitura direta e nomeada das duas operações do trabalho do sonho (§3.3, §3.4) |

## Natureza

| Relação | Natureza |
|---|---|
| Sonhos ↔ Desejo (Freud) | Clínica |
| Sonhos → Recalque | Clínica |
| Sonhos → Formação de compromisso | Estrutural |
| Sonhos → Chiste | Clínica |
| Sonhos → Metáfora / Metonímia | Linguística |

## Observabilidade

- **Pode ser observada diretamente?** Parcialmente — o relato manifesto é observável; o conteúdo latente, não.
- **Pode ser inferida computacionalmente?** Sim, como candidata, via `ClassificadorFreudianoLLM`.
- **Depende de validação do analista?** Sim.
- **Nunca poderá ser produzida automaticamente?** A interpretação do conteúdo onírico latente nunca é produzida automaticamente.

## Motores envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` / `TipoFormacaoFreudiana`.
- **Motor Lacan**: fundamenta teoricamente Metáfora e Metonímia (mapeamento na tabela de reclassificação; Metáfora nunca efetivamente disparada, Metonímia só via Repetição/Chiste).
- **Memória Discursiva**: registra o Evento Discursivo classificado.
- **Timeline**: exibe ocorrências.
- **Circuito Pulsional**: nenhum registrado.
- **Interface do Analista**: exibe a classificação.
- **Interface do Sujeito**: nenhuma.
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/sonhos.md](../../Biblioteca-Teorica/Conceitos/sonhos.md)
- [Modelo-Observacional/Conceitos/sonhos.md](../../Modelo-Observacional/Conceitos/sonhos.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
