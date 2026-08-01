# Pulsão

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Pulsão
- **Obra de origem**: Pulsões e seus Destinos
- **Ano**: 1915
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Desejo; Repetição; Formação de compromisso
- **Autores relacionados**: Nenhum registrado nesta versão.
- **Obras relacionadas**: Pulsões e seus Destinos; Três Ensaios sobre a Teoria da Sexualidade
- **Status**: Catalogado
- **Observações**: 

## Aplicação Computacional

- **Objetivo computacional**: Sustenta teoricamente a hipótese de que elementos discursivos podem se apresentar com insistência — retornando, deslocando-se, buscando novas vias — base teórica do eixo de recorrência já implementado.
- **Fundamentação científica**: Pulsões e seus Destinos (Freud, 1915); Três Ensaios sobre a Teoria da Sexualidade (1905) — ver Ontologia-Freud.md §3.3.
- **Dados necessários**: EventoDiscursivo.conteudo normalizado através de múltiplas Sessões, para observar insistência ao longo do tempo
- **Dados opcionais**: OcorrenciaRecorrencia (circuito) — mostra o trajeto, não a pulsão em si
- **Eventos que podem originá-lo**: Registro de múltiplos EventoDiscursivo com conteúdo normalizado equivalente ao longo de Sessões distintas
- **Relações com outros conceitos**: Força motriz junto com Desejo (Ontologia-Freud.md §4); base teórica da Repetição.
- **Componentes do PsycheAI que utilizam este conceito**: Domain/Services/DetectorRecorrencias — observa insistência de conteúdo, mas nomeia isso "recorrência", nunca "pulsão"
- **Pode ser observado automaticamente?**: Não — o que é observado é recorrência de conteúdo (um efeito possível), nunca a pulsão como tal.
- **Pode ser organizado automaticamente?**: Não.
- **Pode ser classificado automaticamente?**: Não.
- **Depende de confirmação do sujeito?**: Sim.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: Recorrencia (contagem de ocorrências de conteúdo normalizado) — nunca rotulada como "pulsão" na saída do sistema
- **Limitações computacionais**: Ontologia-Freud.md §5 — a pulsão, como conceito-limite entre o somático e o psíquico, não tem correlato observável direto em texto.
- **Trabalhos científicos relacionados**: Pulsões e seus Destinos; Três Ensaios sobre a Teoria da Sexualidade
- **Motores impactados**: Freud Engine (fundamentação teórica de fundo para DetectorRecorrencias, sem nomear o conceito na saída)

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere diretamente — fundamenta, de fundo, por que insistência de conteúdo (Repetição) é tratada como relevante pelo Freud Engine.
- **O sujeito pode perceber sua existência?**: Não — o Sujeito nunca vê o termo "pulsão" nem qualquer rótulo derivado dele.
- **Como a IA deve se comportar diante dele?**: A IA não nomeia pulsão em nenhuma resposta ao Sujeito; no máximo reflete a insistência de um tema via pergunta-eco (RespostaEcoRecorrenciaService), já registrada sob o conceito de Repetição.
- **Quais perguntas podem ser feitas?**: Nenhuma pergunta é derivada diretamente deste conceito — a pergunta-eco pertence ao conceito de Repetição.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie pulsão, fonte, pressão, finalidade ou objeto pulsional.

### Visão do Analista

- **Como o conceito é apresentado?**: Não é apresentado como estrutura própria; a insistência que ele fundamenta aparece ao analista apenas como Recorrencia/circuito, rotulados nesses termos, nunca como "pulsão".
- **Quais visualizações são produzidas?**: Nenhuma.
- **Quais relações podem ser exibidas?**: Nenhuma.
- **Quais evidências sustentam essa representação?**: Nenhum registrado nesta versão.
- **Quais motores produzem essa informação?**: Nenhum registrado nesta versão.
- **Quais componentes do sistema participam dessa construção?**: Nenhum registrado nesta versão.
