# Arquitetura Científica — Psyche AI

> Versão 1.2 — criado na Sprint 25 (Biblioteca Teórica), estendido na Sprint 26 (Modelo Observacional), na Sprint 28 (ECO — Estrutura Computacional de Observação, §5), na Sprint 29 (Representação Computacional, §6), na Sprint 30 (§7, certificação da Base Científica v1.0), por decisão de arquitetura permanente pós-Sprint 30 (§8, Modos de Operação da Plataforma) e por nova decisão de arquitetura permanente (§5.1, Ética da Psicanálise). Documento consolidado dos princípios científicos permanentes da arquitetura do PsycheAI — distinto de [Arquitetura.md](Arquitetura.md), que trata da arquitetura técnica (camadas, componentes, stack). Este documento trata da arquitetura da fundamentação científica: como a Biblioteca Teórica se conecta à implementação, e quais princípios éticos/epistemológicos nenhuma sprint futura pode contradizer.

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

### 5.1 Fundamentação ética: a Ética da Psicanálise

Adicionado por decisão de arquitetura permanente, fora de sprint numerada — pilar permanente, complementar ao método socrático já registrado acima. A posição da ECO diante do sujeito é fundamentada, além do método (maiêutica socrática), na **Ética da Psicanálise** desenvolvida por Jacques Lacan no Seminário 7 (*A Ética da Psicanálise*, catalogado em [Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md](Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md)): a ECO não ocupa o lugar do mestre, do especialista ou de quem sabe; não conduz o sujeito a um ideal; não busca adaptação social; não oferece conselho nem produz interpretação — sustentando, em vez disso, a possibilidade de emergência do próprio discurso do sujeito. Método e ética são complementares, nunca substitutos um do outro. Detalhamento completo em [ECO/Etica-da-Psicanalise.md](ECO/Etica-da-Psicanalise.md).

## 6. Representação Computacional — modelo oficial

Adicionado na Sprint 29 — princípio permanente. O modelo oficial de Representação Computacional, documentado em [Representacao-Computacional/](Representacao-Computacional/README.md), define como toda observação produzida pelos motores é apresentada ao Analista e, de forma estritamente distinta, ao Sujeito. Cinco atributos obrigatórios (observacional, rastreável, auditável, reproduzível, fundamentada na Biblioteca Teórica) e quatro proibições permanentes (interpretar, diagnosticar, concluir, produzir hipótese clínica) regem as oito representações catalogadas: Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos e Indicadores — ver [Representacao-Computacional/Principios.md](Representacao-Computacional/Principios.md).

Nenhum Motor de Representação (visualização, indicador, grafo renderizado) pode ser desenvolvido sem que esta camada esteja documentada primeiro — mesma obrigatoriedade já estabelecida para o Modelo Observacional e o Modelo Relacional em §1 acima. A separação de interface entre Sujeito e Analista (§2) e a exclusividade da escrita lacaniana ao Analista (§3) valem integralmente para esta camada — §6 não os substitui, apenas consolida sua aplicação a cada uma das oito representações, em [Representacao-Computacional/Interface-Sujeito.md](Representacao-Computacional/Interface-Sujeito.md) e [Representacao-Computacional/Interface-Analista.md](Representacao-Computacional/Interface-Analista.md).

## 7. Base Científica v1.0 — certificação

Adicionado na Sprint 30 — princípio permanente. A Base Científica do PsycheAI, consolidando tudo produzido entre a Sprint 25 e a Sprint 29, foi auditada, corrigida e certificada como versão 1.0 em [Base-Cientifica-v1.0.md](Base-Cientifica-v1.0.md). A partir desta certificação, a **Fase 1 — Fundação Científica** está oficialmente encerrada e a **Fase 2 — Desenvolvimento Experimental** tem início.

Qualquer alteração aos princípios permanentes registrados neste documento (§1-6) exige o processo descrito em [Base-Cientifica-v1.0.md, "Critérios para futuras alterações"](Base-Cientifica-v1.0.md#critérios-para-futuras-alterações) — decisão explícita do usuário, atualização coordenada de todos os documentos afetados, revalidação de links e da cadeia de rastreabilidade, e registro no Roadmap.

## 8. Modos de Operação da Plataforma

Adicionado como decisão de arquitetura permanente, imediatamente após a certificação da Base Científica v1.0 (§7) — não vinculada a uma sprint numerada, registrada como evolução permanente da arquitetura da plataforma. Consolida também em [Documento-Mestre.md §8](Documento-Mestre.md#8-modos-de-operação-da-plataforma). **Ajustado** por decisão de arquitetura permanente posterior: a organização original em três modos (ECO Conversacional, ECO Clínica, ECO Pesquisa) foi consolidada em **dois modos de operação** — o princípio de fundo (independência dos Motores em relação à origem do discurso, cadeia científica única) permanece inteiramente válido; o ajuste esclarece que a diferença entre os modos está exclusivamente na origem do discurso e no público que utiliza a plataforma, nunca em uma fundamentação científica paralela. **Ajustado novamente** por uma terceira decisão de arquitetura permanente, exclusivamente documental, que nomeia oficialmente o Modo 2 como **Laboratório** (§8.2) e registra a política de acesso a cada modo (§8.6-8.8) — nenhum dos dois modos, seu fluxo ou sua fundamentação científica foi alterado; apenas quem pode utilizá-los.

### 8.0 Redefinição do PsycheAI

O PsycheAI deixa de ser definido como uma plataforma conversacional. Passa a ser definido como uma **Plataforma de Observação Computacional do Discurso**. A conversa deixa de ser a única forma de entrada de dados. Os Motores do PsycheAI tornam-se independentes da origem do material analisado.

O objeto científico do PsycheAI é o discurso. Não importa como esse discurso chega à plataforma. A arquitetura computacional permanece única. A plataforma deve ser capaz de produzir a mesma representação computacional independentemente da origem dos dados. A única diferença entre os modos de operação é a origem do discurso e o público que utiliza a plataforma.

### 8.1 Modo 1 — destinado ao Sujeito

- **Público**: Sujeito.
- **Fluxo**: Sujeito → ECO → Captura de áudio → Transcrição → Motores → Representação Computacional → Interface do Analista.
- **Objetivo**: sustentar a associação livre.
- O discurso **nasce dentro da própria plataforma**, na conversa com a ECO.
- **O Sujeito nunca acessa as representações produzidas** — mesmo princípio permanente de separação Sujeito/Analista já registrado em [§2](#2-separação-de-interface-entre-sujeito-e-analista).

Modo já em produção — nenhuma mudança de comportamento nesta decisão. A conversa com a ECO em si é implementada desde a Sprint 12; a captura de áudio de entrada, desde a Sprint 22 (`StorageInterface`/`TranscriptionInterface`); a voz de saída, desde a Sprint 24; a interface exclusivamente por voz, desde a Sprint 32. Identidade completa em [ECO/README.md](ECO/README.md).

**Registrado oficialmente**: o Modo 1 constitui a interface pública da plataforma — a única que qualquer visitante alcança sem autenticação de Analista/Administrador. O Sujeito **nunca terá acesso** às representações computacionais produzidas pela plataforma, nomeadamente: Biblioteca Teórica; Modelo Observacional; Modelo Relacional; Representação Computacional; Motores; Memória Discursiva; Grafos; Indicadores; Recorrências; Ferramentas de pesquisa. Esta lista opera no nível arquitetônico (camadas/documentação científica e os componentes que as materializam); a enumeração equivalente no nível de interface (o que a tela em si nunca renderiza — significantes, classificações, hipóteses, estruturas lacanianas etc.) já está registrada, em detalhe, em [ECO/Interface-Sujeito.md, "O que o sujeito nunca visualiza"](ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) — as duas enumerações são complementares, não contraditórias, e nenhuma delas é revista por esta decisão.

### 8.2 Modo 2 — Laboratório (destinado a profissionais e pesquisadores)

**Registrado oficialmente**: o Modo 2 recebe o nome oficial de **Laboratório** — o ambiente interno do PsycheAI. Seu objetivo é apoiar desenvolvimento, validação científica, pesquisa, observação computacional do discurso e análise profissional. Todo material discursivo produzido fora da plataforma é processado exclusivamente neste ambiente.

- **Público**: Psicólogos, Psicanalistas, Profissionais autorizados, Pesquisadores — sujeito à política de acesso registrada em §8.6.
- **Fluxo**: Material Discursivo → Importação → Transcrição (quando necessária) → Motores → Representação Computacional → Interface do Analista.
- **Entradas previstas**: gravações de áudio; vídeos; transcrições; textos; outros registros discursivos autorizados.
- O discurso é **produzido fora da plataforma**. A plataforma recebe material já existente — **não participa da produção do discurso**, apenas realiza sua observação computacional.

Duas exigências éticas permanentes, preservadas do desenho anterior deste modo, aplicam-se conforme a natureza do material importado — não são fluxos separados, mas condições que acompanham a mesma entrada "Material Discursivo" conforme sua origem:

- Quando o material tem origem em **sessão clínica**: a plataforma não participa da condução do atendimento; o profissional conduz integralmente; a observação computacional é sempre posterior; nenhuma intervenção do PsycheAI ocorre durante a sessão.
- Quando o material tem origem em **pesquisa**: deve respeitar integralmente anonimização, consentimento, ética em pesquisa e proteção de dados.

Modo especificado nesta decisão — **sem implementação**. Sujeito a toda a cadeia de rastreabilidade e aos princípios permanentes já registrados (§1-7). Nenhum componente de importação de material discursivo externo, transcrição desvinculada da conversa da ECO, anonimização de pesquisa, sistema de permissões ou exportação científica existe no código nesta data.

### 8.3 Princípio arquitetônico permanente — independência de origem

Os Motores do PsycheAI não dependem da origem do discurso. Os Motores poderão receber, pelos dois modos acima, discurso oriundo de:

- conversa da ECO (Modo 1);
- captura de áudio da própria conversa (Modo 1);
- gravações de áudio importadas (Modo 2);
- vídeos (Modo 2);
- transcrições (Modo 1 ou 2);
- textos (Modo 2);
- outros registros discursivos autorizados (Modo 2).

Todos os formatos deverão convergir para a mesma representação computacional — ver [Representacao-Computacional/README.md, "Origem dos Dados"](Representacao-Computacional/README.md#origem-dos-dados).

### 8.4 Ambos os modos utilizam exatamente a mesma cadeia científica

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

Este diagrama **não substitui nem contradiz** a cadeia de rastreabilidade completa de [§1](#1-cadeia-de-rastreabilidade-obrigatória) (que segue até Implementação e Testes) — descreve o ponto em que os dois modos convergem: independentemente de qual dos dois alimenta os Motores, a fundamentação científica que sustenta a observação é idêntica, auditável pela mesma cadeia, sem nenhum componente científico duplicado. A partir dos Motores, cada modo segue seu próprio fluxo de entrega (§8.1-8.2).

### 8.5 A ECO não é toda a arquitetura do PsycheAI

A ECO é a interface do Modo 1. Ela não representa toda a arquitetura do PsycheAI, que também compreende o Modo 2 — o Laboratório —, no qual a ECO não participa — o discurso chega por importação de material já produzido fora da plataforma. Ver [ECO/README.md, "A ECO não é toda a arquitetura do PsycheAI"](ECO/README.md#a-eco-não-é-toda-a-arquitetura-do-psycheai).

### 8.6 Política de Acesso

Adicionado por decisão de arquitetura permanente, exclusivamente documental, que consolida oficialmente a política de acesso aos dois modos — sem alterar código, API, banco de dados ou testes.

**A única diferença estabelecida por esta decisão é a política de acesso.** Os dois modos de operação permanecem exatamente como definidos em §8.0-8.4.

- **Modo 1 — ECO**: interface pública. Qualquer visitante alcança a conversa com a ECO sem necessidade de autorização especial — a identidade por cookie/conta (Sprint 17/20) permanece o único requisito, já em produção.
- **Modo 2 — Laboratório**: nesta primeira fase do projeto, o Laboratório é de **acesso exclusivo do Administrador do PsycheAI**. Nenhum outro usuário — incluindo Analistas já autenticados pelo Portão do Analista ([`PortaoDeAnalista`](../app/Presentation/Web/Security/PortaoDeAnalista.php)) — tem acesso ao Laboratório nesta versão. Esta decisão visa permitir a evolução científica da plataforma antes da abertura para uso profissional externo.

Nenhuma mudança de código decorre desta decisão: o Portão do Analista já protege hoje toda rota de coleta/análise de dados (`/`, CRUDs, histórico, observações, eventos discursivos) para o público interno atual do projeto — o Laboratório, como conceito de Modo 2 com público externo (profissionais/pesquisadores autorizados), continua **especificado, sem implementação**, exatamente como registrado em §8.2. Esta seção apenas formaliza que, quando o Laboratório for implementado, seu acesso nesta primeira fase será restrito ao Administrador.

### 8.7 Evolução Futura

Registrado que, futuramente, o Laboratório poderá disponibilizar acesso controlado para: psicólogos; psicanalistas; pesquisadores; universidades; hospitais; centros de pesquisa; instituições autorizadas. Esse acesso ocorrerá exclusivamente por meio de um sistema de permissões — inexistente no código nesta data. Essa abertura **não faz parte da versão atual da plataforma**; é registrada aqui como direção arquitetônica permanente, não como compromisso de prazo.

### 8.8 Princípio Permanente — o Laboratório não é uma funcionalidade do Sujeito

O Laboratório não é uma funcionalidade do Sujeito. O Laboratório é um ambiente científico da plataforma. A ECO e o Laboratório são interfaces distintas que compartilham exatamente a mesma arquitetura científica — mesma cadeia de rastreabilidade (§1), mesmos princípios permanentes (§2-4), mesma Representação Computacional (§6, §8.3-8.4). Nenhuma leitura desta decisão autoriza duplicar, bifurcar ou criar uma fundamentação científica paralela para o Laboratório.

## Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura.md](Arquitetura.md)
- [Modelo-Observacional.md](Modelo-Observacional.md)
- [Modelo-Observacional/README.md](Modelo-Observacional/README.md)
- [Modelo-Relacional/README.md](Modelo-Relacional/README.md)
- [Representacao-Computacional/README.md](Representacao-Computacional/README.md)
- [ECO/README.md](ECO/README.md)
- [ECO/Etica-da-Psicanalise.md](ECO/Etica-da-Psicanalise.md)
- [Biblioteca-Teorica/README.md](Biblioteca-Teorica/README.md)
- [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md)
- [Base-Cientifica-v1.0.md](Base-Cientifica-v1.0.md)
- [Regras-Dominio.md](Regras-Dominio.md)
- [Roadmap.md](Roadmap.md)
