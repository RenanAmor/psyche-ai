# Modelo de Documento — Fundamentos Computacionais

> Estende, sem contradizer, o [Modelo-de-Documento.md](../Modelo-de-Documento.md) geral da Biblioteca Teórica. Aplica-se aos 44 documentos de tópico catalogados em [Processamento-Computacional-da-Linguagem/](Processamento-Computacional-da-Linguagem/README.md), [Processamento-de-Audio/](Processamento-de-Audio/README.md), [Inteligencia-Artificial/](Inteligencia-Artificial/README.md), [Arquiteturas-Cognitivas/](Arquiteturas-Cognitivas/README.md), [Engenharia-Cientifica/](Engenharia-Cientifica/README.md) e [Etica-Computacional/](Etica-Computacional/README.md).

## Por que este eixo precisa de modelo próprio

Os documentos de Obra/Autor/Conceito do modelo geral pressupõem uma obra ou um autor específico como unidade de catalogação. Os tópicos desta área — Processamento de Linguagem Natural, Transformers, LGPD, ACT-R etc. — são campos de conhecimento ou normas técnicas, não obras nem autores isoladamente. O modelo abaixo preserva o mesmo espírito (metadado, não resumo opinativo; rastreabilidade até a fonte; nenhuma interpretação) adaptado a essa unidade.

## Campos obrigatórios — Bloco 1 (Metadados)

- **Categoria** — uma das seis categorias de [README.md](README.md#estrutura).
- **Tópico** — nome do campo/tecnologia/norma catalogado.
- **Definição** — descrição factual e objetiva, nunca interpretativa, do que é o tópico (o que ele é, não o que "significa" para o projeto).
- **Área científica de origem** — disciplina donde o tópico se origina (ex.: Ciência da Computação, Linguística Computacional, Processamento de Sinais Digitais, Direito Digital).
- **Referências principais** — autores, ano, título, editora/periódico/instituição e, quando disponível, DOI ou ISBN. Livros, artigos revisados por pares, normas internacionais (ISO/IEC, W3C, IEEE) e documentação acadêmica têm prioridade sobre blogs, tutoriais ou material promocional.
- **Tópicos relacionados** — outros documentos desta área ou de [../Conceitos/](../Conceitos/) diretamente relacionados.
- **Status** — `Catalogado` (metadados registrados e verificados) ou `A verificar` (quando a fonte consultada diverge ou não há consenso terminológico consolidado).
- **Observações** — notas estritamente bibliográficas ou terminológicas, nunca uma opinião sobre o valor do tópico.

## Campos obrigatórios — Bloco 2 (específico desta área)

Estas cinco seções são exigidas em todo documento desta área, além do Bloco 1:

### Aplicação no PsycheAI

O que, computacionalmente, este tópico permite fazer dentro da plataforma — sempre no âmbito de **extrair e qualificar dados do discurso** (transcrever, tokenizar, vetorizar, classificar, medir), nunca no âmbito de decidir o que, desse dado, é clinicamente relevante — essa decisão pertence exclusivamente à Fundamentação Psicanalítica ([../Conceitos/](../Conceitos/), [../../Modelo-Observacional/](../../Modelo-Observacional/README.md)).

### Componentes da Plataforma relacionados

Classes/serviços reais do código (`app/`) que já implementam este tópico nesta data, citados por nome de arquivo/classe — ou, quando não há implementação real, a frase fixa **"Nenhum implementado nesta versão"**. Campo auditável contra o repositório, nunca uma lista de intenção futura (mesma regra do modelo geral, [Modelo-de-Documento.md, "O que este modelo não permite"](../Modelo-de-Documento.md#o-que-este-modelo-não-permite)).

### Relação com a Base Científica

Declara explicitamente como este tópico se posiciona frente à Fundamentação Psicanalítica: ele extrai/qualifica dado (papel da Biblioteca Computacional) e nunca decide sozinho o que esse dado significa ou como deve ser organizado clinicamente (papel exclusivo da Biblioteca Psicanalítica) — ver [README.md, "Por que este eixo existe"](README.md#por-que-este-eixo-existe).

### Relação com os Motores

Quais dos motores conceituais do PsycheAI ([Documento-Mestre.md §7](../../Documento-Mestre.md#7-arquitetura-conceitual): Discourse Engine, Freud Engine, Lacan Engine, ECO) dependem, hoje ou potencialmente, deste tópico como infraestrutura técnica — nunca como fundamentação teórica de conceito psicanalítico, papel que pertence exclusivamente a [../Conceitos/](../Conceitos/). "Nenhum diretamente" é valor válido quando o tópico é fundamentação de fundo sem uso corrente por nenhum motor.

### Relação com a Representação Computacional

Como este tópico, quando aplicável, participa da cadeia até a camada de [../../Representacao-Computacional/](../../Representacao-Computacional/README.md) — por exemplo, fornecendo o dado de entrada (texto transcrito, vetor de embedding, marcação prosódica) que uma representação (Timeline, Recorrências etc.) eventualmente consome. Quando o tópico não alcança essa camada nesta versão, o documento registra "Não alcança a Representação Computacional nesta versão" em vez de omitir o campo.

## O que este modelo não permite

- Resumo de conteúdo técnico além da Definição objetiva do Bloco 1.
- Juízo de valor sobre a tecnologia, a norma ou o campo científico catalogado.
- Afirmar que um Motor "usa" um tópico quando esse uso ainda não existe no código.
- Apresentar Arquiteturas Cognitivas (ACT-R, SOAR, LIDA, CLARION, Sigma) como arquitetura de implementação do PsycheAI — são exclusivamente referência de posicionamento científico (ver [README.md, "Critério de catalogação"](README.md#critério-de-catalogação)).
- Apresentar a Ética Computacional (LGPD, GDPR etc.) como substituta da Ética da Psicanálise já registrada em [../../ECO/Etica-da-Psicanalise.md](../../ECO/Etica-da-Psicanalise.md) — as duas são complementares.
- Incluir qualquer trecho de código-fonte no corpo do documento — apenas nomes de classe/arquivo já existentes, como referência.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
- [../README.md](../README.md)
- [Indice-Topicos.md](Indice-Topicos.md)
- [../../Documento-Mestre.md](../../Documento-Mestre.md)
- [../../Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../../Roadmap.md](../../Roadmap.md)
