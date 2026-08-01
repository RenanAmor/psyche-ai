# Arquitetura Científica — Psyche AI

> Versão 0.3 — criado na Sprint 25 (Biblioteca Teórica), estendido na Sprint 26 (Modelo Observacional) e na Sprint 28 (ECO — Estrutura Computacional de Observação, §5). Documento consolidado dos princípios científicos permanentes da arquitetura do PsycheAI — distinto de [Arquitetura.md](Arquitetura.md), que trata da arquitetura técnica (camadas, componentes, stack). Este documento trata da arquitetura da fundamentação científica: como a Biblioteca Teórica se conecta à implementação, e quais princípios éticos/epistemológicos nenhuma sprint futura pode contradizer.

## 1. Cadeia de rastreabilidade obrigatória

Nenhuma camada abaixo pode ser pulada ao implementar ou estender qualquer motor (registrado também em [Documento-Mestre.md §6.0](Documento-Mestre.md#60-objetivo-científico-do-psycheai) e [Arquitetura.md §9.1](Arquitetura.md#91-cadeia-de-rastreabilidade-obrigatória)):

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

- **Biblioteca Teórica**: [Biblioteca-Teorica/](Biblioteca-Teorica/README.md).
- **Modelo Observacional**: [Modelo-Observacional.md](Modelo-Observacional.md) — princípios gerais: o que, do discurso registrado, pode em princípio ser observado, e o que conta como sucesso científico da observação (distinto de sucesso clínico). Complementa, sem substituir, [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md), que define a estrutura de dados do discurso registrado. Detalhamento conceito a conceito — fenômeno observado, evidências observáveis, dados necessários/opcionais, limites explícitos — em [Modelo-Observacional/](Modelo-Observacional/README.md) (Sprint 26), um documento por cada um dos 21 conceitos canônicos da Biblioteca Teórica. Nenhum motor novo pode ser desenvolvido sem que essa camada esteja documentada primeiro.
- **Modelo Relacional**: [Modelo-Relacional/](Modelo-Relacional/README.md) (Sprint 27) — como os 21 conceitos canônicos se relacionam entre si: conceitos antecedentes, consequentes, relacionados, relações estruturais/temporais/observacionais/de dependência/bidirecionais/não observáveis computacionalmente, cada uma com fundamentação bibliográfica, intensidade e natureza. Inclui seis matrizes (Conceito×Conceito, Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência, Conceito×Observabilidade) e a especificação — sem implementação — de cinco grafos científicos. Nenhum Motor de Representação pode ser desenvolvido sem que essa camada esteja documentada primeiro.
- **Representação Computacional**: seção obrigatória de todo documento de Conceito em [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/).
- **Ontologia**: [Ontologia-Freud.md](Ontologia-Freud.md) / [Ontologia-Lacan.md](Ontologia-Lacan.md).
- **Modelo Computacional**: seção "Aplicação Computacional" de cada documento de Conceito.
- **Implementação**: código real em `app/`.
- **Testes**: suíte automatizada correspondente.

## 2. Separação de interface entre Sujeito e Analista

Já em prática desde `PortaoDeAnalista` (Sprint 18) e a Regra 11 de [Regras-Dominio.md](Regras-Dominio.md), formalizado como princípio permanente em [Documento-Mestre.md §5](Documento-Mestre.md#5-princípios-éticos) e [Arquitetura.md §9.2](Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista): a interface do Sujeito e a interface do Analista são sistemas distintos. O Sujeito nunca visualiza significantes, recorrências, circuito pulsional, hipóteses, classificações, escrita lacaniana ou qualquer estrutura produzida pelos motores. O Analista pode visualizar essas estruturas como apoio à escuta clínica, nunca como diagnóstico automático.

## 3. A escrita lacaniana pertence ao analista

A capacidade do sistema de representar estruturalmente o discurso segundo a teoria lacaniana existe exclusivamente para a interface do Analista — nunca para compor a resposta ao Sujeito (ver [Arquitetura.md §9.3](Arquitetura.md#93-a-escrita-lacaniana-pertence-ao-analista)).

## 4. Princípio da Neutralidade Observacional

Adicionado nesta Sprint, a partir de decisão do usuário — princípio permanente.

### Fundamentação

Durante a construção da teoria psicanalítica, Sigmund Freud publicou diversos casos clínicos que não representavam "casos de sucesso", mas situações de impasse, interrupção, abandono de tratamento ou resultado parcial — ver [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md) para o detalhamento com as obras correspondentes já catalogadas em [Biblioteca-Teorica/Freud/Obras/](Biblioteca-Teorica/Freud/Obras/). O valor científico desses casos não estava no desfecho terapêutico — estava na qualidade da observação. Essa característica passa a fazer parte da filosofia do PsycheAI.

### O princípio

- O PsycheAI não mede o sucesso de sua operação pelo desfecho clínico.
- Sua finalidade é produzir observações computacionais rigorosas do discurso humano.
- Casos concluídos, interrompidos, abandonados, inconclusivos ou considerados fracassos clínicos possuem igualmente valor científico para a plataforma.
- A qualidade científica do PsycheAI é medida pela qualidade dos dados observados, organizados, representados e preservados — nunca pelo resultado clínico.

### Consequências arquitetônicas

Todos os componentes do sistema devem respeitar este princípio. A plataforma preserva, independentemente do encerramento do caso:

- histórico completo das Sessões;
- linha do tempo (`LinhaDoTempoApplicationService`);
- recorrências (`Recorrencia`, `DetectorRecorrencias`);
- formações discursivas (`TipoFormacaoFreudiana`);
- eventos (`EventoDiscursivo`);
- memória longitudinal (`MemoriaLongitudinal`);
- observações produzidas pelos motores (`Observacao`).

Nenhum dado é descartado por representar um caso interrompido. Ver [Modelo-Observacional.md](Modelo-Observacional.md) para o "Status do Caso" que formaliza esse princípio na representação computacional.

## 5. ECO — identidade da interface conversacional

Adicionado na Sprint 28 — princípio permanente. A ECO (Estrutura Computacional de Observação) é a interface conversacional exclusiva do PsycheAI diante do sujeito — a materialização do "modo de enunciação" já registrado em [Documento-Mestre.md §6.7/§7](Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático). O sujeito nunca conversa diretamente com o Discourse Engine, o Motor Freud ou o Motor Lacan — apenas com a ECO, que aplica o método socrático (maiêutica) para sustentar a associação livre, sem jamais interpretar, aconselhar, diagnosticar ou substituir o analista.

Identidade completa — manifesto, princípios permanentes, método socrático, posição clínica, fluxo conversacional, separação Sujeito/Analista aplicada à conversa e ética — documentada em [ECO/README.md](ECO/README.md). Os princípios de separação de interface (§2 acima) e a proibição de escrita lacaniana na conversa com o sujeito (§3 acima) valem integralmente para a ECO — este §5 não os substitui, apenas consolida sua aplicação específica à camada conversacional.

## Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura.md](Arquitetura.md)
- [Modelo-Observacional.md](Modelo-Observacional.md)
- [Modelo-Observacional/README.md](Modelo-Observacional/README.md)
- [Modelo-Relacional/README.md](Modelo-Relacional/README.md)
- [ECO/README.md](ECO/README.md)
- [Biblioteca-Teorica/README.md](Biblioteca-Teorica/README.md)
- [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md)
- [Regras-Dominio.md](Regras-Dominio.md)
- [Roadmap.md](Roadmap.md)
