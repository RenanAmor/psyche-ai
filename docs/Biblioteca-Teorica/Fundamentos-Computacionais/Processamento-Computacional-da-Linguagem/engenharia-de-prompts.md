# Engenharia de Prompts

## Metadados

- **Categoria**: Processamento Computacional da Linguagem
- **Tópico**: Engenharia de Prompts (Prompt Engineering)
- **Definição**: Prática de estruturar deliberadamente a entrada textual (instruções, exemplos, restrições de formato) fornecida a um Large Language Model para condicionar e tornar mais previsível sua saída, sem alterar os parâmetros internos do modelo.
- **Área científica de origem**: Aprendizado de Máquina / Interação Humano-Computador.
- **Referências principais**: Liu, P. et al. (2023). "Pre-train, Prompt, and Predict: A Systematic Survey of Prompting Methods in Natural Language Processing". *ACM Computing Surveys*, 55(9), 1–35. DOI: 10.1145/3560815; Wei, J. et al. (2022). "Chain-of-Thought Prompting Elicits Reasoning in Large Language Models". *NeurIPS*. arXiv:2201.11903.
- **Tópicos relacionados**: [Large Language Models](large-language-models.md)
- **Status**: A verificar
- **Observações**: Catalogado com a ressalva explícita do briefing desta Sprint — fundamentação científica ainda emergente, área em rápida mudança, sem consenso teórico tão consolidado quanto os demais tópicos desta categoria; a literatura citada é a mais estável disponível nesta data, mas o campo carece ainda de teoria unificadora comparável à de NLP/Linguística Computacional.

## Aplicação no PsycheAI

Fundamenta, de forma emergente, a prática já em uso de estruturar a instrução enviada ao LLM para produzir saída em vocabulário fechado (rótulos de `TipoFormacaoFreudiana`) em vez de texto livre — técnica de restrição de formato, não de conteúdo interpretativo.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/ClassificadorFreudianoLLM.php` e `app/Infrastructure/AI/GeradorDePerguntaSocraticaLLM.php` aplicam, na prática, estruturação de prompt para restringir saída — sem um componente de "engenharia de prompts" nomeado ou isolado na plataforma.

## Relação com a Base Científica

Estruturar o prompt é uma técnica de qualificação da extração (garantir que o dado retornado pelo LLM já venha no formato esperado) — nunca decide, por si, qual rótulo é clinicamente correto; essa decisão de vocabulário vem da Ontologia ([../../../Ontologia-Freud.md](../../../Ontologia-Freud.md)).

## Relação com os Motores

Freud Engine e ECO dependem indiretamente, através dos dois componentes citados acima.

## Relação com a Representação Computacional

Não alcança a Representação Computacional nesta versão — a estruturação do prompt ocorre antes da produção do dado que a Representação Computacional consome.

## Referências cruzadas do projeto

- [README.md](README.md)
- [large-language-models.md](large-language-models.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
