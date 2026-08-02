# Embeddings

## Metadados

- **Categoria**: Processamento Computacional da Linguagem
- **Tópico**: Embeddings (representações vetoriais distribuídas)
- **Definição**: Representações numéricas densas de unidades linguísticas (caracteres, subpalavras, palavras, sentenças ou documentos) em um espaço vetorial contínuo, nas quais proximidade geométrica aproxima similaridade semântica ou distribucional.
- **Área científica de origem**: Aprendizado de Máquina / Linguística Computacional.
- **Referências principais**: Mikolov, T. et al. (2013). "Efficient Estimation of Word Representations in Vector Space". arXiv:1301.3781; Harris, Z. S. (1954). "Distributional Structure". *Word*, 10(2–3), 146–162; Firth, J. R. (1957). "A Synopsis of Linguistic Theory 1930–1955". In *Studies in Linguistic Analysis*, Blackwell.
- **Tópicos relacionados**: [Representação Vetorial](representacao-vetorial.md); [Tokenização](tokenizacao.md); [Large Language Models](large-language-models.md); [Recuperação de Informação](recuperacao-de-informacao.md)
- **Status**: Catalogado
- **Observações**: A hipótese distribucional de Harris/Firth ("uma palavra é conhecida pela companhia que mantém") é o fundamento teórico comum a todas as técnicas modernas de embedding.

## Aplicação no PsycheAI

Fundamentação teórica de fundo para qualquer futura comparação de similaridade semântica entre trechos de discurso (ex.: aproximar duas ocorrências que usam palavras diferentes para o mesmo tema) — capacidade que, nesta versão, não é operacionalizada: a detecção de recorrência atual (`DetectorRecorrencias`) compara conteúdo normalizado por igualdade literal, não por similaridade vetorial.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão.

## Relação com a Base Científica

Embeddings, quando implementados, extrairiam e qualificariam similaridade semântica do discurso como dado bruto — a decisão de que grau de similaridade configura uma "recorrência" clinicamente relevante continuaria sendo da Fundamentação Psicanalítica (Repetição, [../../Conceitos/repeticao.md](../../Conceitos/repeticao.md)), nunca do próprio vetor.

## Relação com os Motores

Nenhum diretamente nesta versão. Potencial futuro para o Discourse Engine, caso a detecção de recorrência evolua de comparação literal para comparação semântica — decisão de arquitetura não tomada nesta Sprint.

## Relação com a Representação Computacional

Não alcança a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [representacao-vetorial.md](representacao-vetorial.md)
- [../../Conceitos/repeticao.md](../../Conceitos/repeticao.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
