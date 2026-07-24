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

- os **conceitos fundamentais** desenvolvidos por Freud;
- a **estrutura de leitura** proposta por Lacan.

Este referencial teórico será progressivamente detalhado em sprints futuras. Nesta fase, apenas o posicionamento teórico e seus limites são estabelecidos.

### Princípio fundamental

- O Psyche AI **não realiza diagnósticos**.
- O Psyche AI **não substitui o analista**.
- O Psyche AI **não interpreta o sujeito de forma autônoma**.

Sua função é organizar o discurso, identificar recorrências, estruturar cadeias de significantes, registrar padrões ao longo do tempo e produzir hipóteses fundamentadas no modelo teórico Freud–Lacan. **A interpretação clínica permanece responsabilidade exclusiva do psicanalista.**

## 7. Arquitetura conceitual inicial

Nesta fase, a arquitetura é apenas conceitual e servirá de base para o detalhamento técnico em [Arquitetura.md](Arquitetura.md).

- Projeto construído em **PHP 8.2+**, seguindo os padrões do ecossistema L369.
- Estrutura de diretórios convencional: `app/` (código da aplicação), `config/` (configurações), `storage/` (armazenamento local), `tests/` (testes automatizados).
- Gestão de dependências via **Composer**, com autoload PSR-4.
- Configuração sensível isolada via variáveis de ambiente (`.env`), nunca versionada.
- Visão de longo prazo organizada em três motores conceituais (Freud Engine, Lacan Engine, Discourse Engine) — ver [Arquitetura.md](Arquitetura.md) para o desenho preliminar. Nenhum desses motores é implementado nesta fase.

## 8. Referências

- [Arquitetura.md](Arquitetura.md)
- [Roadmap.md](Roadmap.md)
