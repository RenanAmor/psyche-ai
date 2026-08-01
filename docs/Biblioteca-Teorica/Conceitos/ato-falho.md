# Ato falho

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Ato falho
- **Obra de origem**: Psicopatologia da Vida Cotidiana
- **Ano**: 1901
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Formação de compromisso; Recalque; Desejo (Freud)
- **Autores relacionados**: Nenhum registrado nesta versão.
- **Obras relacionadas**: Psicopatologia da Vida Cotidiana
- **Status**: Catalogado
- **Observações**: 

## Aplicação Computacional

- **Objetivo computacional**: Justifica que interrupções, autocorreções e desvios no discurso registrado sejam preservados como classe própria de EventoDiscursivo — sem inferir a intenção inconsciente subjacente.
- **Fundamentação científica**: Psicopatologia da Vida Cotidiana (Freud, 1901) — ver Ontologia-Freud.md §3.6.
- **Dados necessários**: EventoDiscursivo.conteudo
- **Dados opcionais**: Nenhum registrado nesta versão.
- **Eventos que podem originá-lo**: Mensagem do Sujeito classificada pelo Motor Freud
- **Relações com outros conceitos**: Espécie de Formação de compromisso; ligado ao Recalque (o que retorna) e ao Desejo (o que busca expressão) — Ontologia-Freud.md §4.
- **Componentes do PsycheAI que utilizam este conceito**: TipoFormacaoFreudiana::AtoFalho; Infrastructure/AI/ClassificadorFreudianoLLM
- **Pode ser observado automaticamente?**: Sim.
- **Pode ser organizado automaticamente?**: Não.
- **Pode ser classificado automaticamente?**: Sim.
- **Depende de confirmação do sujeito?**: Não diretamente.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: TipoFormacaoFreudiana::AtoFalho
- **Limitações computacionais**: Reconhecer a forma não é inferir a intenção inconsciente — permanece fora do alcance do sistema (Ontologia-Freud.md §5).
- **Trabalhos científicos relacionados**: Psicopatologia da Vida Cotidiana
- **Motores impactados**: Freud Engine

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere na resposta dada ao Sujeito — a classificação roda em paralelo, nunca compõe a resposta socrática (Regra 11).
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: A IA nunca sinaliza ao Sujeito que um ato falho foi reconhecido — responde normalmente via Modo Socrático.
- **Quais perguntas podem ser feitas?**: As perguntas socráticas genéricas já em produção — nunca fazem referência à classificação.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie "ato falho" ou pressuponha intenção inconsciente por trás de um lapso.

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado na tela de Observações do Sujeito como rótulo estrutural ao lado do Evento Discursivo classificado.
- **Quais visualizações são produzidas?**: Rótulo de tipo de formação na tela de Observações.
- **Quais relações podem ser exibidas?**: Rótulo lacaniano correspondente e fundamentação teórica, quando `comLeituraLacaniana=true`.
- **Quais evidências sustentam essa representação?**: TipoFormacaoFreudiana::AtoFalho
- **Quais motores produzem essa informação?**: Freud Engine
- **Quais componentes do sistema participam dessa construção?**: ObservacoesSujeitoController; ClassificadorFreudianoLLM
