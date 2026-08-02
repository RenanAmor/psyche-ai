# Fundamentos Computacionais — Biblioteca Teórica

> Criado na Sprint 33. Segundo grande eixo da Base Científica do PsycheAI, ao lado da Fundamentação Psicanalítica (Freud, Lacan e correntes pós-freudianas/pós-lacanianas, já catalogadas em [../Freud/](../Freud/), [../Lacan/](../Lacan/), [../Referencias/](../Referencias/), [../Psicanalise/](../Psicanalise/) e [../Conceitos/](../Conceitos/)).

## Por que este eixo existe

Até a Sprint 32, a Biblioteca Teórica fundamentava cientificamente a psicanálise que o PsycheAI implementa, mas não fundamentava, com o mesmo rigor bibliográfico e o mesmo padrão de rastreabilidade, a camada computacional que torna essa observação possível na prática.

**A partir desta Sprint, a Base Científica do PsycheAI é oficialmente interdisciplinar, composta por dois eixos de igual importância:**

1. **Fundamentação Psicanalítica** — Freud, Lacan, psicanálise pós-freudiana/pós-lacaniana, filosofia, linguística (semiótica), antropologia, psiquiatria/neurologia.
2. **Fundamentação Computacional** — processamento computacional da linguagem, processamento de áudio, inteligência artificial, arquiteturas cognitivas, engenharia científica, ética computacional.

A divisão de trabalho entre os dois eixos é precisa: **a Biblioteca Computacional ajuda a extrair e qualificar os dados** — captar, transcrever, tokenizar, vetorizar, classificar o discurso bruto com rigor técnico auditável; **a Biblioteca Psicanalítica orienta como esses dados podem ser organizados na representação** — que fenômeno, dentre o que foi extraído e qualificado, tem relevância científica para ser observado, organizado e eventualmente representado ao Analista. Nenhum dos dois eixos subordina o outro, e nenhum substitui o outro: sem extração e qualificação computacional rigorosa não há dado confiável para organizar; sem a orientação psicanalítica não há critério científico para decidir o que, no dado extraído, é observação relevante.

## O que esta área é — e o que não é

Assim como o restante da Biblioteca Teórica ([Modelo-de-Documento.md](../Modelo-de-Documento.md)), esta área é catalogação de metadados científicos e técnicos com rastreabilidade até a literatura e a normativa que os fundamenta — **não** é um tutorial de implementação, não é documentação de API, não é guia de uso de biblioteca de software. O modelo próprio de documento desta área — que estende, sem contradizer, o modelo geral — está em [Modelo-de-Documento.md](Modelo-de-Documento.md).

## Estrutura

| Pasta | Conteúdo | Itens catalogados |
|---|---|---|
| [Processamento-Computacional-da-Linguagem/](Processamento-Computacional-da-Linguagem/README.md) | NLP, Linguística Computacional, LLMs, Embeddings, Tokenização, Recuperação de Informação, Representação Vetorial, Engenharia de Prompts | 8 |
| [Processamento-de-Audio/](Processamento-de-Audio/README.md) | Processamento Digital de Sinais, ASR, Whisper, VAD, Diarização, Prosódia, Entonação, Pausas, Segmentação da Fala | 9 |
| [Inteligencia-Artificial/](Inteligencia-Artificial/README.md) | IA, Aprendizado de Máquina, Deep Learning, Redes Neurais, Transformers, IA Generativa, Neuro-Symbolic AI | 7 |
| [Arquiteturas-Cognitivas/](Arquiteturas-Cognitivas/README.md) | Arquiteturas Cognitivas (visão geral), ACT-R, SOAR, LIDA, CLARION, Sigma | 6 |
| [Engenharia-Cientifica/](Engenharia-Cientifica/README.md) | Rastreabilidade, Reprodutibilidade, Proveniência de Dados, Auditoria Científica, Versionamento Científico, Validação Experimental | 6 |
| [Etica-Computacional/](Etica-Computacional/README.md) | Ética em IA, Pesquisa com Seres Humanos, Consentimento, Anonimização, LGPD, GDPR, Segurança de Dados, Governança de IA | 8 |
| [Indice-Topicos.md](Indice-Topicos.md) | Índice único dos 44 tópicos desta área, por categoria | — |

**Total: 44 documentos de tópico + 6 READMEs de categoria + este README + o Modelo de Documento próprio.**

## Relação com as pastas de Ciências Auxiliares já existentes

Três pastas de primeiro nível da Biblioteca Teórica já existiam, reservadas desde a Sprint 25, para conteúdo próximo ao desta área: [../Inteligencia-Artificial/](../Inteligencia-Artificial/README.md), [../Linguistica/](../Linguistica/README.md) e [../Engenharia-de-Software/](../Engenharia-de-Software/README.md). Esta Sprint não move nem duplica o conteúdo dessas três pastas — cada uma recebeu uma nota cruzada explícita apontando para cá, preservando seu escopo original (ver nota em cada `README.md`):

- **../Inteligencia-Artificial/** permanece reservada especificamente para a fundamentação técnica *dos componentes já implementados* em `app/Infrastructure/AI/` — mais estreita e auditada contra código do que [Inteligencia-Artificial/](Inteligencia-Artificial/README.md) desta área, que cataloga a ciência da Inteligência Artificial em si (Aprendizado de Máquina, Deep Learning, Transformers etc.), com ou sem implementação real na plataforma.
- **../Linguistica/** permanece reservada para a Semiótica como ciência auxiliar (Saussure, Jakobson, Benveniste, já catalogados em [../Referencias/](../Referencias/)) — distinta da Linguística Computacional catalogada em [Processamento-Computacional-da-Linguagem/linguistica-computacional.md](Processamento-Computacional-da-Linguagem/linguistica-computacional.md).
- **../Engenharia-de-Software/** permanece reservada para padrões arquiteturais de software (Clean/Hexagonal, DDD) — distinta da Engenharia Científica catalogada em [Engenharia-Cientifica/](Engenharia-Cientifica/README.md), que trata de rastreabilidade e reprodutibilidade *da pesquisa*, não de arquitetura de código.

## Modelo de documento

Ver [Modelo-de-Documento.md](Modelo-de-Documento.md) para a especificação completa. Em resumo, cada documento de tópico tem um bloco de Metadados (mesmo espírito do modelo geral — [../Modelo-de-Documento.md](../Modelo-de-Documento.md)) seguido de cinco seções obrigatórias específicas desta área: Aplicação no PsycheAI, Componentes da Plataforma relacionados, Relação com a Base Científica, Relação com os Motores e Relação com a Representação Computacional.

## Critério de catalogação

- **Referências priorizadas**: livros, artigos científicos revisados por pares, normas internacionais (ISO, W3C, IEEE), documentação acadêmica e publicações clássicas de cada área — nunca blogs, tutoriais ou material promocional como referência principal.
- **Precisão sobre exaustividade**: quando um componente citado como "Componentes da Plataforma relacionados" não existe no código nesta data, o documento registra explicitamente "Nenhum implementado nesta versão" — nunca uma intenção de implementação futura apresentada como fato consumado, mesmo critério já em vigor no restante da Biblioteca Teórica ([Modelo-de-Documento.md, "O que este modelo não permite"](../Modelo-de-Documento.md#o-que-este-modelo-não-permite)).
- **Arquiteturas Cognitivas (ACT-R, SOAR, LIDA, CLARION, Sigma)** são catalogadas exclusivamente como referência científica de posicionamento do PsycheAI no campo mais amplo da ciência cognitiva computacional — nunca como arquitetura de implementação obrigatória ou já adotada pela plataforma.
- **Ética Computacional (LGPD, GDPR, ética em pesquisa com seres humanos etc.) complementa, mas não substitui**, a Ética da Psicanálise já documentada em [../../ECO/Etica-da-Psicanalise.md](../../ECO/Etica-da-Psicanalise.md) — a primeira trata da conformidade legal/técnica do tratamento de dados e da pesquisa; a segunda, do lugar clínico-ético que a ECO ocupa diante do sujeito. Nenhuma das duas substitui a outra.
- **Nenhum motor novo do PsycheAI pode ser desenvolvido a partir desta área sem que o conceito computacional que o fundamenta esteja aqui catalogado primeiro** — mesma obrigatoriedade já registrada para os 21 conceitos canônicos psicanalíticos em [../Modelo-de-Documento.md](../Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito), agora estendida ao eixo computacional.

## Restrições desta Sprint

Sprint exclusivamente documental. Nenhum código, API, banco de dados, teste ou arquitetura de Motor foi alterado. Nenhuma implementação foi antecipada como fato consumado — toda afirmação de uso real na plataforma foi auditada contra `app/` nesta data.

## Referências cruzadas do projeto

- [../README.md](../README.md)
- [Modelo-de-Documento.md](Modelo-de-Documento.md)
- [Indice-Topicos.md](Indice-Topicos.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
- [../../Documento-Mestre.md](../../Documento-Mestre.md)
- [../../Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../../Base-Cientifica-v1.0.md](../../Base-Cientifica-v1.0.md)
- [../../ECO/Etica-da-Psicanalise.md](../../ECO/Etica-da-Psicanalise.md)
- [../../Roadmap.md](../../Roadmap.md)
