# Reprodutibilidade

## Metadados

- **Categoria**: Engenharia Científica
- **Tópico**: Reprodutibilidade (Reproducibility)
- **Definição**: Capacidade de um terceiro, usando os mesmos dados e o mesmo método documentado, chegar aos mesmos resultados — condição básica de validação do conhecimento científico, distinta de replicabilidade (obter resultados equivalentes com dados novos, coletados independentemente).
- **Área científica de origem**: Filosofia da Ciência / Metodologia de Pesquisa.
- **Referências principais**: National Academies of Sciences, Engineering, and Medicine (2019). *Reproducibility and Replicability in Science*. National Academies Press. DOI: 10.17226/25303; Peng, R. D. (2011). "Reproducible Research in Computational Science". *Science*, 334(6060), 1226–1227. DOI: 10.1126/science.1213847.
- **Tópicos relacionados**: [Versionamento Científico](versionamento-cientifico.md); [Validação Experimental](validacao-experimental.md)
- **Status**: Catalogado
- **Observações**: Já é o Princípio Permanente 7 da Base Científica (Regra do Domínio de reprodutibilidade, [Regras-Dominio.md](../../../Regras-Dominio.md)) — este documento cataloga a fundamentação científica geral, sem alterar a regra já em vigor.

## Aplicação no PsycheAI

Fundamenta cientificamente a exigência de que qualquer observação computacional produzida pelos Motores (recorrência, classificação estrutural) seja determinística e auditável a partir dos mesmos dados de entrada — mesma entrada, mesma saída, sempre.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — é um princípio de processo/qualidade de dado, verificável por auditoria manual da documentação e do código, não um componente isolado.

## Relação com a Base Científica

Reprodutibilidade garante que a extração/qualificação do dado pela camada computacional seja confiável o suficiente para que a Fundamentação Psicanalítica possa se apoiar nela sem re-verificar cada execução individualmente.

## Relação com os Motores

Discourse Engine e Freud Engine dependem indiretamente — a determinística de `DetectorRecorrencias` é uma instância prática deste princípio; a natureza probabilística de um LLM (`ClassificadorFreudianoLLM`) exige, em vez de determinismo estrito, disciplina de versionamento de prompt e modelo para preservar reprodutibilidade na medida do possível.

## Relação com a Representação Computacional

Fundamenta o atributo "reproduzível", um dos cinco atributos obrigatórios de toda representação ([../../../Representacao-Computacional/Principios.md](../../../Representacao-Computacional/Principios.md)).

## Referências cruzadas do projeto

- [README.md](README.md)
- [versionamento-cientifico.md](versionamento-cientifico.md)
- [../../../Representacao-Computacional/Principios.md](../../../Representacao-Computacional/Principios.md)
- [../../../Regras-Dominio.md](../../../Regras-Dominio.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
