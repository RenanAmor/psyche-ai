# Documento Mestre — Psyche AI

> Versão 0.7 — Sprint 0 (Fundação); §6-7 reescritos na Sprint 14 para substituir as instruções editoriais pendentes por conteúdo final; §6.7 e modo socrático em §7 adicionados após a Sprint 17; §6.0 (Objetivo Científico do PsycheAI, cadeia de rastreabilidade obrigatória, Princípio da Neutralidade Observacional) e dois novos princípios em §5 (separação Sujeito/Analista; escrita lacaniana exclusiva do analista) adicionados na Sprint 25 (Biblioteca Teórica); §6.0 atualizado na Sprint 26 (Modelo Observacional) para referenciar o catálogo por conceito.
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

Toda implementação futura deve seguir obrigatoriamente a cadeia de rastreabilidade abaixo, sem pular nenhuma camada:

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

- **Biblioteca Teórica**: autores, obras e conceitos catalogados em [Biblioteca-Teorica/](Biblioteca-Teorica/README.md).
- **Modelo Observacional**: o que, do discurso registrado, pode em princípio ser observado — princípios gerais em [Modelo-Observacional.md](Modelo-Observacional.md) (estrutura de dados correspondente em [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)); detalhamento conceito a conceito — fenômeno observado, evidências, dados, limites — em [Modelo-Observacional/](Modelo-Observacional/README.md) (Sprint 26).
- **Modelo Relacional**: como os 21 conceitos canônicos se relacionam entre si — conceito a conceito, com fundamentação bibliográfica, intensidade, natureza e observabilidade de cada relação — em [Modelo-Relacional/](Modelo-Relacional/README.md) (Sprint 27), com matrizes (Conceito×Conceito, Motor×Conceito, Conceito×Obra, Conceito×Autor, Conceito×Evidência, Conceito×Observabilidade) e especificação de grafos científicos, sem implementação.
- **Representação Computacional**: como um conceito pode aparecer para o Sujeito e para o Analista, com seus limites de cada lado — seção obrigatória de todo documento de Conceito (ver [Biblioteca-Teorica/Modelo-de-Documento.md](Biblioteca-Teorica/Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito)).
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

## 7. Arquitetura Conceitual

A arquitetura técnica detalhada (camadas, componentes, dependências) é definida em [Arquitetura.md](Arquitetura.md) e [Arquitetura-Camadas.md](Arquitetura-Camadas.md), atualizados a cada sprint.

Do ponto de vista conceitual, o sistema é composto por três motores e um modo de enunciação:

- **Discourse Engine**: organiza o discurso e expõe as recorrências detectadas ao longo do tempo, sem hierarquizar importância nem interpretar conteúdo — implementado desde a Sprint 14 do [Roadmap.md](Roadmap.md).
- **Freud Engine**: aplica "atenção flutuante" sobre o que o Discourse Engine expõe, trazendo apenas o que se repete, sem hipótese — planejado para a Sprint 15.
- **Lacan Engine**: reclassifica as mesmas recorrências trazidas pelo Freud Engine com vocabulário lacaniano, sem acrescentar leitura de sentido nem afirmar estatuto de significante confirmado — planejado para a Sprint 16.
- **Modo socrático**: camada de enunciação que transforma o que os motores acima trazem em pergunta dirigida ao sujeito, nunca em afirmação — ver §6.7.

Nenhum dos três motores produz hipótese, diagnóstico ou identifica significante automaticamente — apenas o analista ou o próprio sujeito confirma qualquer leitura (ver [Regras-Dominio.md](Regras-Dominio.md)).