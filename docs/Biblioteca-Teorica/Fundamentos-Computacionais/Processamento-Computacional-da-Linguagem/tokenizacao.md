# Tokenização

## Metadados

- **Categoria**: Processamento Computacional da Linguagem
- **Tópico**: Tokenização (Tokenization)
- **Definição**: Processo de segmentar uma sequência de texto bruto em unidades discretas menores (tokens — palavras, subpalavras ou caracteres), etapa de pré-processamento necessária para qualquer modelo computacional de linguagem operar sobre o texto.
- **Área científica de origem**: Processamento de Linguagem Natural.
- **Referências principais**: Sennrich, R.; Haddow, B.; Birch, A. (2016). "Neural Machine Translation of Rare Words with Subword Units". *ACL*. arXiv:1508.07909 (introduz Byte-Pair Encoding aplicado a NLP); Kudo, T.; Richardson, J. (2018). "SentencePiece: A Simple and Language Independent Subword Tokenizer". arXiv:1808.06226.
- **Tópicos relacionados**: [Processamento de Linguagem Natural](processamento-de-linguagem-natural.md); [Embeddings](embeddings.md); [Large Language Models](large-language-models.md)
- **Status**: Catalogado
- **Observações**: Distinta da normalização de texto (`trim` + minúsculas) já em produção em `DetectorRecorrencias` desde a Sprint 15 — normalização e tokenização são etapas de pré-processamento relacionadas, mas não idênticas.

## Aplicação no PsycheAI

Fundamentação teórica de fundo para qualquer pré-processamento de texto antes do envio a um LLM — o próprio ato de tokenizar ocorre internamente ao provedor de LLM consumido pela plataforma (`AnthropicLLMService`), sem componente de tokenização próprio implementado pelo PsycheAI.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — a normalização de conteúdo em `Domain/Services/DetectorRecorrencias::normalizar()` (trim + minúsculas) é uma técnica correlata, mas não constitui tokenização no sentido catalogado aqui.

## Relação com a Base Científica

Tokenização qualifica o dado textual bruto em unidades processáveis — etapa puramente técnica, anterior e neutra a qualquer decisão sobre relevância clínica, que permanece exclusiva da Fundamentação Psicanalítica.

## Relação com os Motores

Nenhum diretamente — ocorre internamente ao provedor de LLM consumido pelo Freud Engine e pela ECO, fora do código de domínio do PsycheAI.

## Relação com a Representação Computacional

Não alcança a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [embeddings.md](embeddings.md)
- [large-language-models.md](large-language-models.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
