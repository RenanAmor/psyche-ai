# Sonhos

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Sonhos
- **Obra de origem**: A Interpretação dos Sonhos
- **Ano**: 1900
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Formação de compromisso; Desejo (Freud); Chiste
- **Autores relacionados**: Nenhum registrado nesta versão.
- **Obras relacionadas**: A Interpretação dos Sonhos; Sobre os Sonhos; Complemento Metapsicológico à Teoria dos Sonhos
- **Status**: Catalogado
- **Observações**: 

## Aplicação Computacional

- **Objetivo computacional**: As operações do trabalho do sonho (condensação/deslocamento) são o precedente teórico mais direto para tratar recorrência e deslocamento no discurso registrado como estruturalmente relevantes — sem interpretar o sonho relatado.
- **Fundamentação científica**: A Interpretação dos Sonhos (Freud, 1900) — ver Ontologia-Freud.md §3.8.
- **Dados necessários**: EventoDiscursivo.conteudo (relato do sonho, tratado como qualquer outro material discursivo)
- **Dados opcionais**: Nenhum registrado nesta versão.
- **Eventos que podem originá-lo**: Mensagem do Sujeito classificada pelo Motor Freud
- **Relações com outros conceitos**: Modelo para o Chiste e para toda Formação de compromisso; ligado ao Desejo (Freud) (realização de desejo) e ao Recalque (razão da distorção).
- **Componentes do PsycheAI que utilizam este conceito**: TipoFormacaoFreudiana::Sonho; Infrastructure/AI/ClassificadorFreudianoLLM
- **Pode ser observado automaticamente?**: Sim (reconhecimento de forma, não interpretação de conteúdo onírico).
- **Pode ser organizado automaticamente?**: Não.
- **Pode ser classificado automaticamente?**: Sim.
- **Depende de confirmação do sujeito?**: Não diretamente.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: TipoFormacaoFreudiana::Sonho
- **Limitações computacionais**: O sistema nunca interpreta o sonho relatado — apenas registra o relato como material discursivo (Ontologia-Freud.md §3.8).
- **Trabalhos científicos relacionados**: A Interpretação dos Sonhos; Sobre os Sonhos; Complemento Metapsicológico à Teoria dos Sonhos
- **Motores impactados**: Freud Engine

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere na resposta dada ao Sujeito — a classificação roda em paralelo, nunca compõe a resposta socrática (Regra 11).
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: Quando o Sujeito relata um sonho, a IA responde via Modo Socrático como a qualquer outro conteúdo — sem sinalizar reconhecimento de "relato onírico".
- **Quais perguntas podem ser feitas?**: Perguntas de continuidade socráticas genéricas, que podem convidar o Sujeito a seguir falando sobre o que relatou.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que ofereça interpretação do sonho ou nomeie "trabalho do sonho", "condensação" ou "deslocamento".

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado na tela de Observações do Sujeito como rótulo estrutural ao lado do Evento Discursivo classificado.
- **Quais visualizações são produzidas?**: Rótulo de tipo de formação na tela de Observações.
- **Quais relações podem ser exibidas?**: Rótulo lacaniano correspondente e fundamentação teórica, quando aplicável.
- **Quais evidências sustentam essa representação?**: TipoFormacaoFreudiana::Sonho
- **Quais motores produzem essa informação?**: Freud Engine
- **Quais componentes do sistema participam dessa construção?**: ObservacoesSujeitoController; ClassificadorFreudianoLLM
