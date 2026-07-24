# Documento Mestre — Psyche AI

> Versão 0.1 — Sprint 0 (Fundação)
> Este documento estabelece apenas os fundamentos institucionais do projeto. Teoria psicanalítica aplicada e regras de negócio serão desenvolvidas em sprints futuras.

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

## 6. Modelo teórico fundamental

O Psyche AI adota **exclusivamente** o referencial psicanalítico **Freud–Lacan**. Todo o desenvolvimento do sistema é fundamentado na obra de Sigmund Freud e na releitura estrutural proposta por Jacques Lacan.

A arquitetura do sistema respeita a distinção entre:

- os **conceitos fundamentais** desenvolvidos por Freud — organizados em [Ontologia-Freud.md](Ontologia-Freud.md);
- a **estrutura de leitura** proposta por Lacan — organizada em [Ontologia-Lacan.md](Ontologia-Lacan.md).

Este referencial teórico será progressivamente detalhado em sprints futuras. Nesta fase, apenas o posicionamento teórico e seus limites são estabelecidos.

### 6.1 Princípio fundador

O Psyche AI nasce da hipótese de que é possível construir um sistema computacional para organizar o discurso humano segundo um modelo inspirado no referencial Freud–Lacan.

O objetivo do projeto **não é** reproduzir o trabalho do psicanalista, nem automatizar a interpretação clínica. Seu propósito é organizar estruturas discursivas, identificar padrões linguísticos, acompanhar estruturas discursivas recorrentes ao longo do tempo e produzir hipóteses analíticas transparentes que auxiliem o trabalho do analista. **Toda interpretação clínica permanece sob responsabilidade do psicanalista.**

### 6.2 Objeto de pesquisa

O objeto de pesquisa do Psyche AI **não é o inconsciente em si** — é a **organização computacional do discurso**.

Partindo da hipótese lacaniana de que o inconsciente é estruturado como uma linguagem, o projeto investiga de que maneira estruturas linguísticas observáveis podem ser representadas computacionalmente e utilizadas para construir hipóteses analíticas.

### 6.3 Hipótese central

> Se o inconsciente se manifesta na linguagem, então padrões linguísticos, cadeias de significantes e recorrências discursivas podem ser organizados computacionalmente para auxiliar o processo analítico.

Essa hipótese orienta toda a arquitetura do sistema.

### 6.4 Objetivo científico

Desenvolver um modelo computacional capaz de:

- organizar o discurso em estruturas analisáveis;
- representar estruturas discursivas candidatas a cadeias de significantes;
- identificar recorrências e deslocamentos;
- preservar o contexto temporal das sessões;
- produzir hipóteses fundamentadas no modelo Freud–Lacan;
- apresentar essas hipóteses de forma transparente e rastreável ao analista.

### 6.5 Limites do sistema

O Psyche AI **não**:

- realiza diagnósticos;
- substitui o psicanalista;
- interpreta o sujeito de forma autônoma;
- determina interpretações verdadeiras;
- identifica estados mentais ocultos;
- identifica significantes;
- marca automaticamente uma palavra como sendo um significante;
- afirma ter acesso direto ao inconsciente.

O Psyche AI não identifica significantes. O Psyche AI identifica **estruturas discursivas** cuja relevância poderá, ou não, ser confirmada no processo analítico. O sistema organiza informações e produz hipóteses cuja validade depende da avaliação clínica do analista.

O significante não é uma palavra; é uma representação que só o sujeito que diz pode confirmar. Por isso, o Psyche AI não marca automaticamente uma palavra como sendo um significante — trata palavras e recorrências apenas como pistas de uma estrutura discursiva possível, cujo estatuto de significante só pode ser confirmado pelo sujeito, no processo analítico.

### 6.6 Questão de pesquisa em aberto

Uma pergunta ainda mais fundamental orienta o horizonte científico do projeto:

> Como representar computacionalmente um significante sem reduzi-lo a uma simples palavra?

Em Lacan, um significante não se confunde com um vocábulo isolado — seu papel depende das relações que estabelece na cadeia discursiva. O significante não é uma palavra; é uma representação que só o sujeito que diz pode confirmar. Traduzir essa ideia para uma estrutura computacional é um dos maiores desafios científicos do Psyche AI: uma resposta consistente a essa questão pode configurar não apenas uma aplicação de IA para psicanálise, mas um novo modelo de representação computacional do discurso inspirado em Freud e Lacan. Esta questão **não é respondida nesta fase** — orienta a pesquisa em sprints futuras.

## 7. Arquitetura conceitual inicial

Nesta fase, a arquitetura é apenas conceitual e servirá de base para o detalhamento técnico em [Arquitetura.md](Arquitetura.md).

- Projeto construído em **PHP 8.2+**, seguindo os padrões do ecossistema L369.
- Estrutura de diretórios convencional: `app/` (código da aplicação), `config/` (configurações), `storage/` (armazenamento local), `tests/` (testes automatizados).
- Gestão de dependências via **Composer**, com autoload PSR-4.
- Configuração sensível isolada via variáveis de ambiente (`.env`), nunca versionada.
- Visão de longo prazo organizada em três motores conceituais (Freud Engine, Lacan Engine, Discourse Engine) — ver [Arquitetura.md](Arquitetura.md) para o desenho preliminar. Nenhum desses motores é implementado nesta fase.

## 8. Referências

- [Arquitetura.md](Arquitetura.md)
- [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)
- [Ontologia-Freud.md](Ontologia-Freud.md)
- [Ontologia-Lacan.md](Ontologia-Lacan.md)
- [Roadmap.md](Roadmap.md)
