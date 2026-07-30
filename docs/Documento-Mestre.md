# Documento Mestre — Psyche AI

> Versão 0.2 — Sprint 0 (Fundação); §6-7 reescritos na Sprint 14 para substituir as instruções editoriais pendentes por conteúdo final.
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

## 6. Modelo Teórico Fundamental

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

## 7. Arquitetura Conceitual

A arquitetura técnica detalhada (camadas, componentes, dependências) é definida em [Arquitetura.md](Arquitetura.md) e [Arquitetura-Camadas.md](Arquitetura-Camadas.md), atualizados a cada sprint.

Do ponto de vista conceitual, o sistema é composto por três motores:

- **Discourse Engine**: organiza o discurso e expõe as recorrências detectadas ao longo do tempo, sem hierarquizar importância nem interpretar conteúdo — implementado desde a Sprint 14 do [Roadmap.md](Roadmap.md).
- **Freud Engine**: aplica "atenção flutuante" sobre o que o Discourse Engine expõe, trazendo apenas o que se repete, sem hipótese — planejado para a Sprint 15.
- **Lacan Engine**: reclassifica as mesmas recorrências trazidas pelo Freud Engine com vocabulário lacaniano, sem acrescentar leitura de sentido nem afirmar estatuto de significante confirmado — planejado para a Sprint 16.

Nenhum dos três motores produz hipótese, diagnóstico ou identifica significante automaticamente — apenas o analista ou o próprio sujeito confirma qualquer leitura (ver [Regras-Dominio.md](Regras-Dominio.md)).