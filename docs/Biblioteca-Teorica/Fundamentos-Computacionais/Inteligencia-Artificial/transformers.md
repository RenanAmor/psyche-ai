# Transformers

## Metadados

- **Categoria**: Inteligência Artificial
- **Tópico**: Transformers (arquitetura de rede neural)
- **Definição**: Arquitetura de rede neural baseada inteiramente em mecanismos de atenção (attention), dispensando recorrência e convolução, capaz de processar sequências inteiras em paralelo — arquitetura fundadora dos Large Language Models modernos.
- **Área científica de origem**: Ciência da Computação / Aprendizado de Máquina.
- **Referências principais**: Vaswani, A.; Shazeer, N.; Parmar, N.; Uszkoreit, J.; Jones, L.; Gomez, A. N.; Kaiser, Ł.; Polosukhin, I. (2017). "Attention Is All You Need". *NeurIPS*. arXiv:1706.03762.
- **Tópicos relacionados**: [Redes Neurais](redes-neurais.md); [Deep Learning](deep-learning.md); [Large Language Models](../Processamento-Computacional-da-Linguagem/large-language-models.md)
- **Status**: Catalogado
- **Observações**: Nenhuma.

## Aplicação no PsycheAI

Fundamenta tecnicamente a arquitetura interna do LLM consumido via `AnthropicLLMService` — o PsycheAI não implementa nem treina Transformers próprios; consome um modelo já treinado por terceiros via API.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — consumido apenas indiretamente, via API de terceiros.

## Relação com a Base Científica

A arquitetura Transformer é infraestrutura de extração/qualificação de dado (classificação, geração de linguagem) — não decide, por si, nenhum critério de relevância clínica.

## Relação com os Motores

Freud Engine e ECO dependem indiretamente, através do LLM consumido.

## Relação com a Representação Computacional

Não alcança diretamente a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [redes-neurais.md](redes-neurais.md)
- [../Processamento-Computacional-da-Linguagem/large-language-models.md](../Processamento-Computacional-da-Linguagem/large-language-models.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
