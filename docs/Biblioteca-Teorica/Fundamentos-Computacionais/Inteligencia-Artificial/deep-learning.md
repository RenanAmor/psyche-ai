# Deep Learning

## Metadados

- **Categoria**: Inteligência Artificial
- **Tópico**: Deep Learning (Aprendizado Profundo)
- **Definição**: Subcampo do Aprendizado de Máquina baseado em redes neurais artificiais com múltiplas camadas (profundidade), capazes de aprender representações hierárquicas de dados diretamente a partir de exemplos brutos, sem engenharia manual de características.
- **Área científica de origem**: Ciência da Computação.
- **Referências principais**: Goodfellow, I.; Bengio, Y.; Courville, A. (2016). *Deep Learning*. MIT Press. ISBN 978-0-262-03561-3; LeCun, Y.; Bengio, Y.; Hinton, G. (2015). "Deep Learning". *Nature*, 521(7553), 436–444. DOI: 10.1038/nature14539.
- **Tópicos relacionados**: [Redes Neurais](redes-neurais.md); [Transformers](transformers.md); [Aprendizado de Máquina](aprendizado-de-maquina.md)
- **Status**: Catalogado
- **Observações**: Os LLMs consumidos pelo PsycheAI ([Large Language Models](../Processamento-Computacional-da-Linguagem/large-language-models.md)) são, tecnicamente, modelos de Deep Learning — o PsycheAI consome a saída desses modelos via API, sem operar nenhuma camada de rede neural própria.

## Aplicação no PsycheAI

Fundamentação teórica de base para os LLMs em produção — sem componente de Deep Learning próprio implementado ou treinado pelo PsycheAI nesta versão.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — consumido apenas indiretamente, via API de terceiros (`AnthropicLLMService`, `OpenAIWhisperTranscriptionService`).

## Relação com a Base Científica

Deep Learning é a técnica de extração/qualificação subjacente ao LLM e ao ASR consumidos — a decisão de relevância clínica do dado extraído permanece exclusivamente da Fundamentação Psicanalítica.

## Relação com os Motores

Freud Engine e ECO dependem indiretamente, através do LLM. Discourse Engine depende indiretamente, através do Whisper.

## Relação com a Representação Computacional

Não alcança diretamente a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [redes-neurais.md](redes-neurais.md)
- [transformers.md](transformers.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
