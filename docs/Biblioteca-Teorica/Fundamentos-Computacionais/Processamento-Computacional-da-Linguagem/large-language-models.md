# Large Language Models

## Metadados

- **Categoria**: Processamento Computacional da Linguagem
- **Tópico**: Large Language Models (LLMs)
- **Definição**: Modelos de linguagem de grande escala, tipicamente baseados na arquitetura Transformer, treinados por pré-treinamento auto-supervisionado sobre grandes volumes de texto para prever/gerar sequências linguísticas, e posteriormente ajustados para seguir instruções.
- **Área científica de origem**: Aprendizado de Máquina / Processamento de Linguagem Natural.
- **Referências principais**: Vaswani, A. et al. (2017). "Attention Is All You Need". *NeurIPS*. arXiv:1706.03762; Brown, T. et al. (2020). "Language Models are Few-Shot Learners". *NeurIPS*. arXiv:2005.14165; Bommasani, R. et al. (2021). "On the Opportunities and Risks of Foundation Models". Stanford CRFM. arXiv:2108.07258.
- **Tópicos relacionados**: [Transformers](../Inteligencia-Artificial/transformers.md); [Embeddings](embeddings.md); [Tokenização](tokenizacao.md); [Engenharia de Prompts](engenharia-de-prompts.md); [IA Generativa](../Inteligencia-Artificial/ia-generativa.md)
- **Status**: Catalogado
- **Observações**: O provedor específico de LLM usado em produção (`AnthropicLLMService`) é decisão de infraestrutura, fora do escopo desta catalogação científica.

## Aplicação no PsycheAI

Fundamenta cientificamente dois papéis distintos já em produção: (1) classificação estrutural do discurso registrado, atribuindo rótulos fechados de um vocabulário fixo (nunca texto livre interpretativo); (2) geração de linguagem natural para as perguntas socráticas da ECO. Em ambos os casos, o LLM extrai/qualifica ou gera dado — nunca decide, por conta própria, que interpretação clínica dar a ele.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/AnthropicLLMService.php`; `app/Infrastructure/AI/ClassificadorFreudianoLLM.php`; `app/Infrastructure/AI/GeradorDePerguntaSocraticaLLM.php`.

## Relação com a Base Científica

O LLM é o motor técnico de extração/qualificação (classifica, gera) — a decisão de que vocabulário fechado usar na classificação (os rótulos de `TipoFormacaoFreudiana`) vem inteiramente da Fundamentação Psicanalítica, nunca do modelo em si. Nenhuma saída do LLM é aceita como afirmação clínica; é sempre tratada como dado a ser organizado pela camada psicanalítica.

## Relação com os Motores

Freud Engine (classificação via `ClassificadorFreudianoLLM`) e ECO (geração via `GeradorDePerguntaSocraticaLLM`) dependem diretamente. Discourse Engine e Lacan Engine não chamam o LLM diretamente nesta versão.

## Relação com a Representação Computacional

Alimenta diretamente a Representação de Formações Freudianas ([../../../Representacao-Computacional/Formacoes-Freudianas.md](../../../Representacao-Computacional/Formacoes-Freudianas.md)), cujo dado de origem é o rótulo produzido pelo LLM.

## Referências cruzadas do projeto

- [README.md](README.md)
- [processamento-de-linguagem-natural.md](processamento-de-linguagem-natural.md)
- [../Inteligencia-Artificial/transformers.md](../Inteligencia-Artificial/transformers.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
