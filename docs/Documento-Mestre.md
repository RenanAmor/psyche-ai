# Documento Mestre — Psyche AI

> Versão 1.3 — Sprint 0 (Fundação); §6-7 reescritos na Sprint 14 para substituir as instruções editoriais pendentes por conteúdo final; §6.7 e modo socrático em §7 adicionados após a Sprint 17; §6.0 (Objetivo Científico do PsycheAI, cadeia de rastreabilidade obrigatória, Princípio da Neutralidade Observacional) e dois novos princípios em §5 (separação Sujeito/Analista; escrita lacaniana exclusiva do analista) adicionados na Sprint 25 (Biblioteca Teórica); §6.0 atualizado na Sprint 26 (Modelo Observacional) para referenciar o catálogo por conceito; §7 atualizado na Sprint 28 (ECO) para nomear oficialmente o modo socrático como ECO — Estrutura Computacional de Observação; §6.0 atualizado na Sprint 29 (Representação Computacional) para referenciar o modelo oficial de representação ao Analista e ao Sujeito. **Sprint 30 — Consolidação Científica v1.0**: este documento foi auditado integralmente, corrigido em pontos de nomenclatura e certificado como parte da Base Científica v1.0 — ver [Base-Cientifica-v1.0.md](Base-Cientifica-v1.0.md). A partir desta Sprint, a Fase 1 (Fundação Científica) está encerrada e a Fase 2 (Desenvolvimento Experimental) tem início. **Decisão de Arquitetura Permanente pós-Sprint 30**: §8 adicionado, registrando o PsycheAI como Plataforma de Observação Computacional do Discurso — não mais definida como plataforma exclusivamente conversacional. **Nova decisão de arquitetura permanente**: §6.8 adicionado, registrando a Ética da Psicanálise (Seminário 7 de Lacan) como pilar permanente, complementar ao método socrático de §6.7. **Ajuste de arquitetura permanente**: §8 consolidado de três para dois modos de operação (destinado ao Sujeito; destinado a profissionais e pesquisadores) — princípio de fundo preservado, apenas a organização dos modos foi simplificada. **Sprint 33 — Expansão da Biblioteca Científica (Fundamentos Computacionais)**: §6.0 atualizado para registrar que a Base Científica passa a ser interdisciplinar, com dois eixos de igual importância (Fundamentação Psicanalítica e Fundamentação Computacional) — ver [Biblioteca-Teorica/Fundamentos-Computacionais/README.md](Biblioteca-Teorica/Fundamentos-Computacionais/README.md).
> Este documento estabelece os fundamentos institucionais do projeto e o modelo teórico fundamental adotado (§6-7).

## 1. Visão

Ser uma referência técnica e ética na aplicação de inteligência artificial ao campo da psicologia e da psicanálise, construindo ferramentas que ampliem a capacidade humana de reflexão e autoconhecimento, sempre em complementaridade — nunca em substituição — ao julgamento clínico profissional.

## 2. Missão

Explorar, com rigor técnico e responsabilidade ética, a aplicação de inteligência artificial à compreensão do funcionamento psíquico humano.

## 3. Objetivos

- Estabelecer uma fundação técnica sólida, extensível e auditável para o projeto.
- Construir, de forma incremental e documentada, a ponte entre conceitos psicológicos/psicanalíticos e modelos de inteligência artificial.
- Garantir que toda evolução do projeto seja rastreável, testável e revisável.
- Priorizar segurança, privacidade e ética em cada decisão técnica.

## 4. Escopo

### Incluído nesta fase (Sprint 0)

- Estrutura física do repositório.
- Documentação fundacional (este documento, Arquitetura, Roadmap).
- Configuração base do projeto PHP (Composer, variáveis de ambiente).

### Fora de escopo nesta fase

- Modelagem de teoria psicanalítica aplicada.
- Definição de regras de negócio.
- Implementação de funcionalidades, integrações ou modelos de IA.

Essas frentes serão desenvolvidas em sprints subsequentes, com documentação própria.

## 5. Princípios éticos

- **Não substituição do profissional**: o projeto não tem como finalidade substituir psicólogos, psicanalistas ou qualquer profissional de saúde mental.
- **Privacidade e confidencialidade**: dados sensíveis relacionados à vida psíquica de indivíduos devem ser tratados com o mais alto padrão de proteção.
- **Transparência**: limitações e natureza assistiva do sistema devem ser sempre comunicadas de forma clara.
- **Responsabilidade**: decisões técnicas devem considerar o impacto potencial sobre pessoas em situação de vulnerabilidade.
- **Rigor técnico e teórico**: qualquer aplicação de conceitos psicológicos/psicanalíticos deve ser feita com cuidado e fundamentação, evitando simplificações que distorçam o campo teórico de origem.
- **Separação de interface entre Sujeito e Analista**: a interface do Sujeito e a interface do Analista são sistemas distintos. O Sujeito nunca visualiza significantes, recorrências, circuito pulsional, hipóteses, classificações, escrita lacaniana ou qualquer estrutura produzida pelos motores — apenas o Analista pode visualizar essas estruturas, como apoio à escuta clínica (já em prática desde `PortaoDeAnalista`, Sprint 18, e a Regra 11 de [Regras-Dominio.md](Regras-Dominio.md); formalizado como princípio permanente da arquitetura na Sprint da Biblioteca Teórica).
- **A escrita lacaniana pertence ao analista, não ao sujeito**: a capacidade do sistema de representar estruturalmente o discurso segundo a teoria lacaniana existe exclusivamente para a interface do Analista; essa representação nunca é utilizada para dialogar com o Sujeito.

## 6. Modelo Teórico Fundamental

### 6.0 Objetivo Científico do PsycheAI

O PsycheAI nasce inspirado no projeto freudiano de construir uma Psicologia Científica. A hipótese de trabalho da plataforma é que tecnologias computacionais atuais permitem registrar, organizar e analisar longitudinalmente regularidades do discurso humano descritas pela teoria psicanalítica, preservando rigorosamente a distinção entre observação computacional e interpretação clínica. O sistema não pretende substituir o analista nem produzir interpretações automáticas. Seu propósito é construir uma base observacional digital, rastreável e auditável, capaz de apoiar a investigação científica do discurso e oferecer novas ferramentas para o trabalho clínico.

A fundamentação científica dessa hipótese de trabalho — autores, obras, conceitos e sua rastreabilidade até cada motor do sistema — é mantida na [Biblioteca Teórica](Biblioteca-Teorica/README.md), parte da arquitetura do PsycheAI desde a Sprint que a instituiu. Nenhum conceito é implementado no código sem fundamentação científica correspondente registrada ali (ver [Biblioteca-Teorica/Modelo-de-Documento.md](Biblioteca-Teorica/Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito)).

**A partir da Sprint 33**, a Biblioteca Teórica passa a ser oficialmente interdisciplinar, composta por dois eixos de igual importância: a **Fundamentação Psicanalítica** (Freud, Lacan e correntes correlatas, já descrita acima) e a **Fundamentação Computacional** ([Biblioteca-Teorica/Fundamentos-Computacionais/](Biblioteca-Teorica/Fundamentos-Computacionais/README.md) — processamento computacional da linguagem, processamento de áudio, inteligência artificial, arquiteturas cognitivas, engenharia científica e ética computacional). A Biblioteca Computacional ajuda a extrair e qualificar os dados do discurso; a Biblioteca Psicanalítica orienta como esses dados podem ser organizados na representação — nenhum dos dois eixos subordina ou substitui o outro.

Toda implementação futura deve seguir obrigatoriamente a cadeia de rastreabilidade abaixo, sem pular nenhuma camada:

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

- **Biblioteca Teórica**: autores, obras e conceitos catalogados em [Biblioteca-Teorica/](Biblioteca-Teorica/README.md).
- **Modelo Observacional**: o que, do discurso registrado, pode em princípio ser observado — princípios gerais em [Modelo-Observacional.md](Modelo-Observacional.md) (estrutura de dados correspondente em [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)); detalhamento conceito a conceito — fenômeno observado, evidências, dados, limites — em [Modelo-Observacional/](Modelo-Observacional/README.md) (Sprint 26).
- **Modelo Relacional**: como os 21 conceitos canônicos se relacionam entre si — conceito a conceito, com fundamentação bibliográfica, intensidade, natureza e observabilidade de cada relação — em [Modelo-Relacional/](Modelo-Relacional/README.md) (Sprint 27), com matrizes (Conceito×Conceito, Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência, Conceito×Observabilidade) e especificação de grafos científicos, sem implementação.
- **Representação Computacional**: como um conceito pode aparecer para o Sujeito e para o Analista, com seus limites de cada lado — seção obrigatória de todo documento de Conceito (ver [Biblioteca-Teorica/Modelo-de-Documento.md](Biblioteca-Teorica/Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito)), e modelo oficial consolidado, por tipo de representação (Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos, Indicadores), em [Representacao-Computacional/](Representacao-Computacional/README.md) (Sprint 29).
- **Ontologia**: vocabulário conceitual fixado em [Ontologia-Freud.md](Ontologia-Freud.md) e [Ontologia-Lacan.md](Ontologia-Lacan.md).
- **Modelo Computacional**: a seção "Aplicação Computacional" de cada documento de Conceito.
- **Implementação**: código real em `app/`.
- **Testes**: suíte automatizada correspondente.

Esta cadeia é um princípio permanente da arquitetura do PsycheAI, não uma orientação de uma única Sprint. Ver [Arquitetura-Cientifica.md](Arquitetura-Cientifica.md) para a versão consolidada de todos os princípios científicos permanentes.

### Princípio da Neutralidade Observacional

O PsycheAI não mede o sucesso de sua operação pelo desfecho clínico. Sua finalidade é produzir observações computacionais rigorosas do discurso humano. Casos concluídos, interrompidos, abandonados, inconclusivos ou considerados fracassos clínicos possuem igualmente valor científico para a plataforma — a qualidade científica do PsycheAI é medida pela qualidade dos dados observados, organizados, representados e preservados, nunca pelo resultado clínico. Nenhum dado é descartado por representar um caso interrompido.

Este princípio é inspirado diretamente na própria história da psicanálise: vários dos casos clínicos que Freud publicou e que fundamentaram a teoria — catalogados em [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md) — não foram casos de sucesso terapêutico, mas situações de impasse, interrupção ou resultado parcial cujo valor esteve na qualidade da observação, não no desfecho. Ver a formalização computacional (Status do Caso) em [Modelo-Observacional.md §3](Modelo-Observacional.md#3-status-do-caso) e o detalhamento completo em [Arquitetura-Cientifica.md §4](Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional).

### 6.1 Princípio fundador

O objeto do PsycheAI é preservar, organizar e acompanhar longitudinalmente o discurso humano.

### 6.2 Objeto de pesquisa

O objeto de pesquisa é a organização computacional do discurso, com foco na observação longitudinal das recorrências discursivas.

### 6.3 Hipótese central

É possível identificar recorrências estruturais no discurso de um sujeito ao longo do tempo, sem que isso implique a representação ou a identificação automática de significantes — tarefa que permanece exclusiva do analista ou do próprio sujeito.

### 6.4 Objetivo científico

Investigar como observar, organizar e tornar rastreável a produção discursiva de um sujeito ao longo do tempo, de modo auditável e tecnicamente rigoroso.

### 6.5 Limites do sistema

O sistema não interpreta o discurso do sujeito.
O sistema não atribui significado.
O sistema não substitui a escuta clínica.
Toda leitura permanece de responsabilidade do analista ou do próprio sujeito.

### 6.6 Questão de pesquisa em aberto

Como representar computacionalmente um significante sem reduzi-lo a uma simples palavra — questão que permanece central e ainda sem resposta definitiva (ver também [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites)).

### 6.7 Modo de enunciação: o método socrático

O PsycheAI não fala como quem sabe. Fala como quem pergunta.

A referência adotada para o modo de enunciação da inteligência do sistema é Sócrates — não como figura histórica, mas como método: a maiêutica, a provocação da associação livre no próprio sujeito, sem jamais entregar conteúdo, causa ou sentido no lugar dele.

O Freud Engine e o Lacan Engine (§7) fornecem a base conceitual — respectivamente, a obra de Freud e sua releitura estrutural por Lacan — que orienta *onde* dirigir a atenção flutuante (o que se repete, que forma de formação de compromisso, que estrutura de linguagem). Essa base é usada exclusivamente para saber o que observar, nunca para compor uma resposta interpretativa. O que o sistema devolve ao sujeito é sempre pergunta, nunca afirmação — o mesmo limite já estabelecido na Regra 7 ([Regras-Dominio.md](Regras-Dominio.md#regra-7): "o sistema registra recorrências, não interpreta recorrências").

Esse modo de enunciação já está em prática desde a Sprint 17 — ver [RespostaEcoRecorrenciaService](../app/Infrastructure/AI/RespostaEcoRecorrenciaService.php), que, ao detectar uma repetição, devolve "Você voltou a falar em '%s'. O que vem à mente sobre isso?" em vez de qualquer afirmação sobre a repetição.

### 6.8 Fundamentação ética: a Ética da Psicanálise

Adicionado por decisão de arquitetura permanente, fora de sprint numerada — pilar permanente da arquitetura científica do PsycheAI, complementar ao método socrático de §6.7. Enquanto o método socrático (maiêutica) responde a *como* a ECO pergunta, a **Ética da Psicanálise**, desenvolvida por Jacques Lacan no Seminário 7 (catalogado em [Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md](Biblioteca-Teorica/Lacan/Seminarios/a-etica-da-psicanalise-seminario-vii.md)), responde a *que lugar* a ECO recusa ocupar diante do sujeito: nunca o lugar do mestre, do especialista ou de quem sabe; nunca conduzindo o sujeito a um ideal ou à adaptação social; nunca oferecendo conselho ou interpretação. A ECO sustenta, em vez disso, a possibilidade de emergência do próprio discurso do sujeito.

Os dois elementos — método socrático e Ética da Psicanálise — são complementares, nunca um substituto do outro. Detalhamento completo em [ECO/Etica-da-Psicanalise.md](ECO/Etica-da-Psicanalise.md).

## 7. Arquitetura Conceitual

A arquitetura técnica detalhada (camadas, componentes, dependências) é definida em [Arquitetura.md](Arquitetura.md) e [Arquitetura-Camadas.md](Arquitetura-Camadas.md), atualizados a cada sprint.

Do ponto de vista conceitual, o sistema é composto por três motores e um modo de enunciação:

- **Discourse Engine**: organiza o discurso e expõe as recorrências detectadas ao longo do tempo, sem hierarquizar importância nem interpretar conteúdo — implementado desde a Sprint 14 do [Roadmap.md](Roadmap.md).
- **Freud Engine**: aplica "atenção flutuante" sobre o que o Discourse Engine expõe, trazendo apenas o que se repete, sem hipótese — planejado para a Sprint 15.
- **Lacan Engine**: reclassifica as mesmas recorrências trazidas pelo Freud Engine com vocabulário lacaniano, sem acrescentar leitura de sentido nem afirmar estatuto de significante confirmado — planejado para a Sprint 16.
- **Modo socrático**: camada de enunciação que transforma o que os motores acima trazem em pergunta dirigida ao sujeito, nunca em afirmação — ver §6.7. A partir da Sprint 28, essa camada tem identidade oficial: **ECO — Estrutura Computacional de Observação**, a única interface conversacional com a qual o sujeito interage — nunca com os motores diretamente. Identidade completa (manifesto, princípios permanentes, método socrático, posição clínica, fluxo conversacional, separação Sujeito/Analista e ética) documentada em [ECO/README.md](ECO/README.md).

Nenhum dos três motores produz hipótese, diagnóstico ou identifica significante automaticamente — apenas o analista ou o próprio sujeito confirma qualquer leitura (ver [Regras-Dominio.md](Regras-Dominio.md)).

## 8. Modos de Operação da Plataforma

Adicionado como decisão de arquitetura permanente, imediatamente após a certificação da Base Científica v1.0 (Sprint 30) — não vinculada a uma sprint numerada, registrada como evolução permanente da arquitetura da plataforma, não como nova sprint.

**O PsycheAI deixa de ser definido como uma plataforma conversacional.** A partir desta decisão, o PsycheAI passa a ser definido como uma **Plataforma de Observação Computacional do Discurso**. A conversa com a ECO deixa de ser a única forma de entrada de dados: os Motores do PsycheAI (Discourse Engine, Freud Engine, Lacan Engine) tornam-se independentes da origem do material analisado.

O objeto científico do PsycheAI é o discurso — não importa como esse discurso chega à plataforma ([Documento-Mestre.md §6.2](#62-objeto-de-pesquisa)). A plataforma deve ser capaz de produzir a mesma Representação Computacional independentemente da origem dos dados.

**O PsycheAI não é um chatbot.** A ECO Conversacional (§6.7, §7 acima) é apenas uma das interfaces possíveis de entrada de discurso na plataforma — a única que interage diretamente com o Sujeito, mas não a única forma da plataforma operar. A Plataforma PsycheAI é independente da interface utilizada para a coleta do discurso.

Os dois modos de operação oficiais da plataforma — **Modo 1 — ECO** (interface pública, destinada ao Sujeito; o discurso nasce dentro da própria plataforma, na conversa com a ECO) e **Modo 2 — Laboratório** (ambiente interno do PsycheAI, destinado a profissionais e pesquisadores; o discurso é produzido fora da plataforma e importado como material já existente) — estão documentados por completo, com público, fluxo e objetivo de cada um, em [Arquitetura-Cientifica.md §8](Arquitetura-Cientifica.md#8-modos-de-operação-da-plataforma). Ajustado a partir da organização original em três modos: a diferença entre os dois está exclusivamente na origem do discurso e no público que utiliza a plataforma — ambos utilizam exatamente a mesma cadeia científica (§6.0) até a camada de Motores, sem nenhum componente científico duplicado.

**Política de acesso** (decisão de arquitetura permanente, exclusivamente documental): o Modo 1 — ECO é a superfície pública, sem restrição além da identidade do próprio Sujeito. O Modo 2 — Laboratório, nesta primeira fase do projeto, é de **acesso exclusivo do Administrador do PsycheAI** — nenhum outro usuário tem acesso, inclusive quando implementado. Futuramente poderá se abrir, por sistema de permissões ainda inexistente, a psicólogos, psicanalistas, pesquisadores, universidades, hospitais, centros de pesquisa e instituições autorizadas — fora do escopo da versão atual. **O Laboratório não é uma funcionalidade do Sujeito; é um ambiente científico da plataforma**, que compartilha com a ECO exatamente a mesma arquitetura científica. Detalhamento completo em [Arquitetura-Cientifica.md §8.6-8.8](Arquitetura-Cientifica.md#86-política-de-acesso).