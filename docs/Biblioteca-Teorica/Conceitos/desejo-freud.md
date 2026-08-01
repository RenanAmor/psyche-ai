# Desejo (Freud)

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Desejo (Freud)
- **Obra de origem**: A Interpretação dos Sonhos
- **Ano**: 1900
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Pulsão; Sonhos; Repetição
- **Autores relacionados**: Nenhum registrado nesta versão.
- **Obras relacionadas**: A Interpretação dos Sonhos
- **Status**: Catalogado
- **Observações**: Distinto do Desejo lacaniano (Conceitos/desejo-lacaniano.md) — nota de escopo em Ontologia-Freud.md §3.4, não fundidos nesta Biblioteca.

## Aplicação Computacional

- **Objetivo computacional**: Fundamenta por que associações e repetições ao longo do tempo — não apenas o conteúdo isolado — podem ser candidatas a estrutura discursiva relevante, reforçando a importância da temporalidade.
- **Fundamentação científica**: A Interpretação dos Sonhos (Freud, 1900) — ver Ontologia-Freud.md §3.4.
- **Dados necessários**: EventoDiscursivo.criadoEm; Sessao.data (temporalidade)
- **Dados opcionais**: Relato de sonho registrado como EventoDiscursivo, sem classificação automática de "desejo realizado"
- **Eventos que podem originá-lo**: Nenhum registrado nesta versão.
- **Relações com outros conceitos**: Força motriz junto com Pulsão (Ontologia-Freud.md §4); distinto do Desejo lacaniano.
- **Componentes do PsycheAI que utilizam este conceito**: Nenhum nomeia "desejo" diretamente — a temporalidade que ele fundamenta é usada por LinhaDoTempoApplicationService e por DetectorRecorrencias::detectarCircuito()
- **Pode ser observado automaticamente?**: Não.
- **Pode ser organizado automaticamente?**: Não (a organização cronológica em si é função técnica de LinhaDoTempoApplicationService, não "organização do desejo").
- **Pode ser classificado automaticamente?**: Não.
- **Depende de confirmação do sujeito?**: Sim.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: Nenhum registrado nesta versão.
- **Limitações computacionais**: Ontologia-Freud.md §5; ver também nota de escopo distinguindo do Desejo lacaniano.
- **Trabalhos científicos relacionados**: A Interpretação dos Sonhos
- **Motores impactados**: Nenhum (fundamentação teórica de fundo para a temporalidade usada pelo Freud Engine)

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere diretamente — fundamenta a importância da temporalidade usada pela Linha do Tempo e pelo circuito de recorrências.
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: A IA nunca nomeia "desejo" na conversa; no máximo dá continuidade à fala do Sujeito via Motor de Enunciação Socrática, sem atribuir causa ou objeto de desejo.
- **Quais perguntas podem ser feitas?**: Perguntas de continuidade genéricas do Motor de Enunciação Socrática (GeradorDePerguntaSocraticaLLM), que dão sequência ao assunto trazido pelo Sujeito sem nomear desejo.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que afirme ou sugira o que o Sujeito "realmente deseja" ou "realmente quer dizer".

### Visão do Analista

- **Como o conceito é apresentado?**: Não é apresentado como estrutura própria; a Linha do Tempo (LinhaDoTempoApplicationService) e o circuito de recorrências são as únicas representações temporais disponíveis ao analista, sem nomear "desejo".
- **Quais visualizações são produzidas?**: Nenhuma.
- **Quais relações podem ser exibidas?**: Nenhuma.
- **Quais evidências sustentam essa representação?**: Nenhum registrado nesta versão.
- **Quais motores produzem essa informação?**: Nenhum registrado nesta versão.
- **Quais componentes do sistema participam dessa construção?**: LinhaDoTempoApplicationService
