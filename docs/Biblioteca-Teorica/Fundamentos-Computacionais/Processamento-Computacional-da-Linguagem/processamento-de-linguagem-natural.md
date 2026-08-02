# Processamento de Linguagem Natural

## Metadados

- **Categoria**: Processamento Computacional da Linguagem
- **Tópico**: Processamento de Linguagem Natural (Natural Language Processing, NLP)
- **Definição**: Subcampo da Ciência da Computação e da Inteligência Artificial dedicado a permitir que sistemas computacionais processem, compreendam e produzam linguagem humana, escrita ou falada, combinando métodos da Linguística Computacional com aprendizado de máquina.
- **Área científica de origem**: Ciência da Computação / Inteligência Artificial / Linguística Computacional.
- **Referências principais**: Jurafsky, D.; Martin, J. H. (2023). *Speech and Language Processing* (3rd ed. draft). Pearson/online. ISBN da 2ª edição impressa: 978-0-13-187321-6; Manning, C. D.; Schütze, H. (1999). *Foundations of Statistical Natural Language Processing*. MIT Press. ISBN 978-0-262-13360-9.
- **Tópicos relacionados**: [Linguística Computacional](linguistica-computacional.md); [Large Language Models](large-language-models.md); [Tokenização](tokenizacao.md); [Embeddings](embeddings.md); [Recuperação de Informação](recuperacao-de-informacao.md)
- **Status**: Catalogado
- **Observações**: Termo "guarda-chuva" que engloba as demais entradas desta categoria — cada uma delas é uma subtarefa ou técnica específica de NLP.

## Aplicação no PsycheAI

NLP é o campo científico que fundamenta, em conjunto, toda a cadeia de extração e qualificação do discurso textual do Sujeito: da transcrição de áudio (que produz texto) até a classificação estrutural do conteúdo discursivo pelo Motor Freud. O PsycheAI não implementa um "motor de NLP" único e monolítico — utiliza tarefas específicas de NLP (classificação de texto via LLM, geração de linguagem) através dos serviços listados abaixo.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/ClassificadorFreudianoLLM.php` (classificação estrutural de texto); `app/Infrastructure/AI/GeradorDePerguntaSocraticaLLM.php` (geração de linguagem); `app/Infrastructure/AI/AnthropicLLMService.php` (serviço de LLM subjacente a ambos).

## Relação com a Base Científica

NLP extrai e qualifica o texto (normalização, classificação, geração) que a Fundamentação Psicanalítica interpreta cientificamente como fenômeno observável — nunca o contrário: nenhuma técnica de NLP decide sozinha que um trecho de discurso é uma "formação de compromisso" ou um "ato falho"; essa qualificação clínico-estrutural depende da Ontologia e do Modelo Observacional ([../../../Ontologia-Freud.md](../../../Ontologia-Freud.md), [../../../Modelo-Observacional/README.md](../../../Modelo-Observacional/README.md)).

## Relação com os Motores

Discourse Engine (organização do discurso bruto) e Freud Engine (classificação estrutural via `ClassificadorFreudianoLLM`) dependem diretamente de NLP como infraestrutura. A ECO depende de NLP para geração de linguagem (`GeradorDePerguntaSocraticaLLM`). O Lacan Engine reclassifica rótulos já produzidos pelo Freud Engine — depende de NLP apenas indiretamente, através dele.

## Relação com a Representação Computacional

NLP produz o dado de entrada (texto classificado, rótulo estrutural) consumido por representações como Formações Freudianas e Recorrências ([../../../Representacao-Computacional/Formacoes-Freudianas.md](../../../Representacao-Computacional/Formacoes-Freudianas.md)) — não produz, por si, nenhuma representação visual ou estrutural.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
- [../../../Documento-Mestre.md](../../../Documento-Mestre.md)
