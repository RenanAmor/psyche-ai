# Arquitetura Científica — Psyche AI

> Versão 1.1 — criado na Sprint 25 (Biblioteca Teórica), estendido na Sprint 26 (Modelo Observacional), na Sprint 28 (ECO — Estrutura Computacional de Observação, §5), na Sprint 29 (Representação Computacional, §6), na Sprint 30 (§7, certificação da Base Científica v1.0) e por decisão de arquitetura permanente pós-Sprint 30 (§8, Modos de Operação da Plataforma). Documento consolidado dos princípios científicos permanentes da arquitetura do PsycheAI — distinto de [Arquitetura.md](Arquitetura.md), que trata da arquitetura técnica (camadas, componentes, stack). Este documento trata da arquitetura da fundamentação científica: como a Biblioteca Teórica se conecta à implementação, e quais princípios éticos/epistemológicos nenhuma sprint futura pode contradizer.

## 1. Cadeia de rastreabilidade obrigatória

Nenhuma camada abaixo pode ser pulada ao implementar ou estender qualquer motor (registrado também em [Documento-Mestre.md §6.0](Documento-Mestre.md#60-objetivo-científico-do-psycheai) e [Arquitetura.md §9.1](Arquitetura.md#91-cadeia-de-rastreabilidade-obrigatória)):

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

- **Biblioteca Teórica**: [Biblioteca-Teorica/](Biblioteca-Teorica/README.md).
- **Modelo Observacional**: [Modelo-Observacional.md](Modelo-Observacional.md) — princípios gerais: o que, do discurso registrado, pode em princípio ser observado, e o que conta como sucesso científico da observação (distinto de sucesso clínico). Complementa, sem substituir, [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md), que define a estrutura de dados do discurso registrado. Detalhamento conceito a conceito — fenômeno observado, evidências observáveis, dados necessários/opcionais, limites explícitos — em [Modelo-Observacional/](Modelo-Observacional/README.md) (Sprint 26), um documento por cada um dos 21 conceitos canônicos da Biblioteca Teórica. Nenhum motor novo pode ser desenvolvido sem que essa camada esteja documentada primeiro.
- **Modelo Relacional**: [Modelo-Relacional/](Modelo-Relacional/README.md) (Sprint 27) — como os 21 conceitos canônicos se relacionam entre si: conceitos antecedentes, consequentes, relacionados, relações estruturais/temporais/observacionais/de dependência/bidirecionais/não observáveis computacionalmente, cada uma com fundamentação bibliográfica, intensidade e natureza. Inclui seis matrizes (Conceito×Conceito, Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência, Conceito×Observabilidade) e a especificação — sem implementação — de cinco grafos científicos. Nenhum Motor de Representação pode ser desenvolvido sem que essa camada esteja documentada primeiro.
- **Representação Computacional**: seção obrigatória de todo documento de Conceito em [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/), consolidada no modelo oficial de [Representacao-Computacional/](Representacao-Computacional/README.md) (Sprint 29) — ver §6 abaixo.
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

## 6. Representação Computacional — modelo oficial

Adicionado na Sprint 29 — princípio permanente. O modelo oficial de Representação Computacional, documentado em [Representacao-Computacional/](Representacao-Computacional/README.md), define como toda observação produzida pelos motores é apresentada ao Analista e, de forma estritamente distinta, ao Sujeito. Cinco atributos obrigatórios (observacional, rastreável, auditável, reproduzível, fundamentada na Biblioteca Teórica) e quatro proibições permanentes (interpretar, diagnosticar, concluir, produzir hipótese clínica) regem as oito representações catalogadas: Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos e Indicadores — ver [Representacao-Computacional/Principios.md](Representacao-Computacional/Principios.md).

Nenhum Motor de Representação (visualização, indicador, grafo renderizado) pode ser desenvolvido sem que esta camada esteja documentada primeiro — mesma obrigatoriedade já estabelecida para o Modelo Observacional e o Modelo Relacional em §1 acima. A separação de interface entre Sujeito e Analista (§2) e a exclusividade da escrita lacaniana ao Analista (§3) valem integralmente para esta camada — §6 não os substitui, apenas consolida sua aplicação a cada uma das oito representações, em [Representacao-Computacional/Interface-Sujeito.md](Representacao-Computacional/Interface-Sujeito.md) e [Representacao-Computacional/Interface-Analista.md](Representacao-Computacional/Interface-Analista.md).

## 7. Base Científica v1.0 — certificação

Adicionado na Sprint 30 — princípio permanente. A Base Científica do PsycheAI, consolidando tudo produzido entre a Sprint 25 e a Sprint 29, foi auditada, corrigida e certificada como versão 1.0 em [Base-Cientifica-v1.0.md](Base-Cientifica-v1.0.md). A partir desta certificação, a **Fase 1 — Fundação Científica** está oficialmente encerrada e a **Fase 2 — Desenvolvimento Experimental** tem início.

Qualquer alteração aos princípios permanentes registrados neste documento (§1-6) exige o processo descrito em [Base-Cientifica-v1.0.md, "Critérios para futuras alterações"](Base-Cientifica-v1.0.md#critérios-para-futuras-alterações) — decisão explícita do usuário, atualização coordenada de todos os documentos afetados, revalidação de links e da cadeia de rastreabilidade, e registro no Roadmap.

## 8. Modos de Operação da Plataforma

Adicionado como decisão de arquitetura permanente, imediatamente após a certificação da Base Científica v1.0 (§7) — não vinculada a uma sprint numerada, registrada como evolução permanente da arquitetura da plataforma. Consolida também em [Documento-Mestre.md §8](Documento-Mestre.md#8-modos-de-operação-da-plataforma).

### 8.0 Redefinição do PsycheAI

O PsycheAI deixa de ser definido como uma plataforma conversacional. Passa a ser definido como uma **Plataforma de Observação Computacional do Discurso**. A conversa deixa de ser a única forma de entrada de dados. Os Motores do PsycheAI tornam-se independentes da origem do material analisado.

O objeto científico do PsycheAI é o discurso. Não importa como esse discurso chega à plataforma. A plataforma deve ser capaz de produzir a mesma representação computacional independentemente da origem dos dados.

### 8.1 Modo 1 — ECO Conversacional

- **Público**: Sujeito.
- **Fluxo**: Sujeito → ECO → Motores → Representação Computacional → Interface do Analista.
- **Objetivo**: sustentar a associação livre.

Modo já em produção — nenhuma mudança de comportamento nesta decisão. Identidade completa em [ECO/README.md](ECO/README.md).

### 8.2 Modo 2 — ECO Clínica

- **Público**: Psicólogos, Psicanalistas, Profissionais autorizados.
- **Fluxo**: Sessão Clínica → Áudio → Transcrição → Motores → Representação Computacional → Interface do Analista.
- Neste modo, **a ECO não participa da sessão**. O profissional conduz integralmente o atendimento. O PsycheAI realiza apenas a observação posterior. **Nenhuma intervenção ocorre durante a sessão clínica.**

Modo especificado nesta decisão — **sem implementação**. Sujeito a toda a cadeia de rastreabilidade e aos princípios permanentes já registrados (§1-7). A captação de áudio de sessão já existe desde a Sprint 22 (`StorageInterface`, `TranscriptionInterface`, `StatusTranscricao` — Infrastructure/Contracts), mas sua aplicação a uma sessão clínica conduzida integralmente por um profissional, fora da conversa com a ECO, é especificação nova nesta decisão, sem componente de Aplicação ou Apresentação implementado.

### 8.3 Modo 3 — ECO Pesquisa

- **Público**: Pesquisadores.
- **Fluxo**: Sessões anonimizadas → Motores → Representação Computacional → Produção Científica.
- Este modo deve respeitar integralmente: anonimização; consentimento; ética em pesquisa; proteção de dados.

Modo especificado nesta decisão — **sem implementação**. Nenhum mecanismo de anonimização, consentimento de pesquisa ou exportação para produção científica existe no código nesta data.

### 8.4 Princípio arquitetônico permanente — independência de origem

Os Motores do PsycheAI não dependem da origem do discurso. Os Motores poderão receber futuramente:

- conversa da ECO;
- gravação de áudio;
- vídeo;
- transcrição;
- texto;
- documentos clínicos autorizados;
- outras formas estruturadas de discurso.

Todos os formatos deverão convergir para a mesma representação computacional — ver [Representacao-Computacional/README.md, "Origem dos Dados"](Representacao-Computacional/README.md#origem-dos-dados).

### 8.5 Os três modos utilizam exatamente a mesma cadeia científica

```
Biblioteca Teórica
        │
        ▼
Modelo Observacional
        │
        ▼
Modelo Relacional
        │
        ▼
Representação Computacional
        │
        ▼
Ontologias
        │
        ▼
Modelo Computacional
        │
        ▼
Motores
```

Este diagrama **não substitui nem contradiz** a cadeia de rastreabilidade completa de [§1](#1-cadeia-de-rastreabilidade-obrigatória) (que segue até Implementação e Testes) — descreve o ponto em que os três modos convergem: independentemente de qual dos três alimenta os Motores, a fundamentação científica que sustenta a observação é idêntica, auditável pela mesma cadeia. A partir dos Motores, cada modo segue seu próprio fluxo de entrega (§8.1-8.3).

### 8.6 A ECO não é toda a arquitetura do PsycheAI

A ECO Conversacional é apenas uma interface da plataforma — a interface do Modo 1. Ela não representa toda a arquitetura do PsycheAI, que também compreende os Modos 2 (Clínica) e 3 (Pesquisa), nos quais a ECO não participa. Ver [ECO/README.md, "A ECO não é toda a arquitetura do PsycheAI"](ECO/README.md#a-eco-não-é-toda-a-arquitetura-do-psycheai).

## Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura.md](Arquitetura.md)
- [Modelo-Observacional.md](Modelo-Observacional.md)
- [Modelo-Observacional/README.md](Modelo-Observacional/README.md)
- [Modelo-Relacional/README.md](Modelo-Relacional/README.md)
- [Representacao-Computacional/README.md](Representacao-Computacional/README.md)
- [ECO/README.md](ECO/README.md)
- [Biblioteca-Teorica/README.md](Biblioteca-Teorica/README.md)
- [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md)
- [Base-Cientifica-v1.0.md](Base-Cientifica-v1.0.md)
- [Regras-Dominio.md](Regras-Dominio.md)
- [Roadmap.md](Roadmap.md)
