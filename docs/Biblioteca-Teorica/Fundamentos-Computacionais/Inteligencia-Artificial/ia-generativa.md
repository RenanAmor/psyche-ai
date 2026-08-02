# IA Generativa

## Metadados

- **Categoria**: Inteligência Artificial
- **Tópico**: IA Generativa (Generative AI)
- **Definição**: Subcampo da Inteligência Artificial dedicado a modelos capazes de gerar novos dados (texto, imagem, áudio, código) estatisticamente similares aos dados usados em seu treinamento, em vez de apenas classificar ou prever rótulos sobre dados existentes.
- **Área científica de origem**: Aprendizado de Máquina.
- **Referências principais**: Goodfellow, I. et al. (2014). "Generative Adversarial Networks". *NeurIPS*. arXiv:1406.2661; Kingma, D. P.; Welling, M. (2013). "Auto-Encoding Variational Bayes". arXiv:1312.6114; Brown, T. et al. (2020). "Language Models are Few-Shot Learners". arXiv:2005.14165.
- **Tópicos relacionados**: [Large Language Models](../Processamento-Computacional-da-Linguagem/large-language-models.md); [Transformers](transformers.md)
- **Status**: Catalogado
- **Observações**: O LLM consumido pela plataforma é, tecnicamente, um modelo de IA Generativa — a geração de texto de `GeradorDePerguntaSocraticaLLM` é uma aplicação direta dessa categoria.

## Aplicação no PsycheAI

Fundamenta cientificamente a geração das perguntas socráticas devolvidas pela ECO ao Sujeito — texto novo, gerado a partir do contexto da conversa, nunca uma resposta pré-escrita fora dos casos de `RespostaFixaService`.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/GeradorDePerguntaSocraticaLLM.php`; `app/Infrastructure/AI/AnthropicLLMService.php`.

## Relação com a Base Científica

IA Generativa produz a forma linguística da pergunta devolvida ao Sujeito — o conteúdo e os limites dessa pergunta (nunca afirmar, nunca interpretar, nunca aconselhar) são inteiramente definidos pela Fundamentação Psicanalítica e pela Ética da Psicanálise ([../../../ECO/Etica-da-Psicanalise.md](../../../ECO/Etica-da-Psicanalise.md)), nunca pelo modelo generativo em si.

## Relação com os Motores

ECO depende diretamente. Freud Engine depende de geração apenas quando a resposta-eco de recorrência é composta (`RespostaEcoRecorrenciaService`).

## Relação com a Representação Computacional

Não alcança a Representação Computacional — a saída da IA Generativa vai para a Interface do Sujeito ([../../../ECO/Interface-Sujeito.md](../../../ECO/Interface-Sujeito.md)), nunca para a Interface do Analista.

## Referências cruzadas do projeto

- [README.md](README.md)
- [transformers.md](transformers.md)
- [../../../ECO/Etica-da-Psicanalise.md](../../../ECO/Etica-da-Psicanalise.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
