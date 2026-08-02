# Chiste — Modelo Relacional

> Camada de Modelo Relacional, entre o Modelo Observacional e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica completa em [Biblioteca-Teorica/Conceitos/chiste.md](../../Biblioteca-Teorica/Conceitos/chiste.md); fenômeno observável em [Modelo-Observacional/Conceitos/chiste.md](../../Modelo-Observacional/Conceitos/chiste.md).

## Relações Científicas

### Conceitos antecedentes

- **[Formação de compromisso](formacao-de-compromisso.md)** — o chiste é formação de compromisso ([Ontologia-Freud.md §3.7](../../Ontologia-Freud.md#37-chiste)).
- **[Sonhos](sonhos.md)** — compartilha técnicas com os sonhos (condensação, deslocamento) ([Ontologia-Freud.md §3.7](../../Ontologia-Freud.md#37-chiste)).

### Conceitos consequentes

Nenhum registrado nesta ontologia.

### Conceitos relacionados

Formação de compromisso; Sonhos; Metonímia (Biblioteca-Teorica/Conceitos/chiste.md, campo "Conceitos relacionados").

### Relações estruturais

Espécie de **Formação de compromisso**, ao lado de Ato falho e Sonhos ([Ontologia-Freud.md §3.5, §4](../../Ontologia-Freud.md#35-formação-de-compromisso)).

### Relações temporais

Relacional/dependente de um interlocutor no momento em que ocorre — ao contrário do sonho, privado, depende de um outro para se realizar plenamente ([Ontologia-Freud.md §3.7](../../Ontologia-Freud.md#37-chiste)).

### Relações observacionais

Observado e classificado via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`; único, junto com Repetição, também reclassificado pelo Lacan Engine sob o rótulo de **Metonímia** ([Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)).

### Relações de dependência

Depende de **Formação de compromisso**; compartilha técnicas com **Sonhos**.

### Relações bidirecionais

Chiste ↔ Sonhos: "compartilha técnicas com os sonhos" ([Ontologia-Freud.md §3.7](../../Ontologia-Freud.md#37-chiste)) — relação mútua de parentesco técnico, embora distintas quanto à exigência de interlocutor.

### Relações não observáveis computacionalmente

O conteúdo agressivo ou obsceno recalcado que o chiste expressa de forma disfarçada nunca é identificável pelo sistema — apenas a ocorrência formal é classificada, sempre como candidata.

## Fundamentação

| Relação | Obra | Autor | Capítulo/Seção | Tipo de relação |
|---|---|---|---|---|
| Chiste → Formação de compromisso | *Os Chistes e sua Relação com o Inconsciente* (1905) | Sigmund Freud | Ontologia-Freud.md §3.5, §3.7 | Estrutural (espécie) |
| Chiste ↔ Sonhos | *Os Chistes e sua Relação com o Inconsciente* (1905); *A Interpretação dos Sonhos* (1900) | Sigmund Freud | Ontologia-Freud.md §3.7 | Bidirecional (técnicas compartilhadas) |
| Chiste → Metonímia (reclassificação lacaniana) | *A Instância da Letra no Inconsciente ou a Razão desde Freud* | Jacques Lacan | Biblioteca-Teorica/Conceitos/chiste.md | Reclassificação estrutural |

## Intensidade

| Relação | Classificação | Justificativa |
|---|---|---|
| Chiste → Formação de compromisso | Fundamental | Relação de espécie explícita |
| Chiste ↔ Sonhos | Forte | "Compartilha técnicas", nomeado diretamente |
| Chiste → Metonímia | Forte | Único rótulo lacaniano efetivamente produzido pelo Lacan Engine, ainda que por reclassificação |

## Natureza

| Relação | Natureza |
|---|---|
| Chiste → Formação de compromisso | Estrutural |
| Chiste ↔ Sonhos | Clínica |
| Chiste → Metonímia | Linguística |

## Observabilidade

- **Pode ser observada diretamente?** Parcialmente — a ocorrência formal é observável; o conteúdo recalcado, não.
- **Pode ser inferida computacionalmente?** Sim, como candidata, via `ClassificadorFreudianoLLM`; reclassificada pelo Lacan Engine como Metonímia.
- **Depende de validação do analista?** Sim.
- **Nunca poderá ser produzida automaticamente?** O conteúdo recalcado que o chiste expressa nunca é produzido automaticamente.

## Motores envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` / `TipoFormacaoFreudiana`.
- **Motor Lacan**: reclassifica como Metonímia.
- **Memória Discursiva**: registra o Evento Discursivo classificado.
- **Timeline**: exibe ocorrências.
- **Circuito Pulsional**: nenhum registrado.
- **Interface do Analista**: exibe a classificação e a reclassificação.
- **Interface do Sujeito**: nenhuma.
- **Demais componentes impactados**: nenhum registrado nesta versão.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/chiste.md](../../Biblioteca-Teorica/Conceitos/chiste.md)
- [Modelo-Observacional/Conceitos/chiste.md](../../Modelo-Observacional/Conceitos/chiste.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
