# Chiste

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Chiste
- **Obra de origem**: Os Chistes e sua Relação com o Inconsciente
- **Ano**: 1905
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Formação de compromisso; Sonhos; Metonímia
- **Autores relacionados**: Jacques Lacan
- **Obras relacionadas**: Os Chistes e sua Relação com o Inconsciente; A Instância da Letra no Inconsciente ou a Razão desde Freud
- **Status**: Catalogado
- **Observações**: 

## Aplicação Computacional

- **Objetivo computacional**: A exigência de um interlocutor reforça a importância do contexto de enunciação já previsto para EventoDiscursivo — o chiste é relacional, nunca isolado.
- **Fundamentação científica**: Os Chistes e sua Relação com o Inconsciente (Freud, 1905) — ver Ontologia-Freud.md §3.7; releitura estrutural em Ontologia-Lacan.md.
- **Dados necessários**: EventoDiscursivo.conteudo
- **Dados opcionais**: Turnos anteriores da Sessao (contexto de enunciação)
- **Eventos que podem originá-lo**: Mensagem do Sujeito classificada pelo Motor Freud
- **Relações com outros conceitos**: Compartilha técnicas (condensação/deslocamento) com Sonhos; espécie de Formação de compromisso; releitura lacaniana em termos de Metáfora/Metonímia.
- **Componentes do PsycheAI que utilizam este conceito**: TipoFormacaoFreudiana::Chiste; Infrastructure/AI/ClassificadorFreudianoLLM; Domain/Services/ReclassificadorLacaniano::reclassificarPorTipoFreudiano() (tabela determinística que traduz o rótulo freudiano para vocabulário lacaniano)
- **Pode ser observado automaticamente?**: Sim.
- **Pode ser organizado automaticamente?**: Não.
- **Pode ser classificado automaticamente?**: Sim (Motor Freud, via LLM) e reclassificado em seguida (Motor Lacan, tabela determinística, sem LLM).
- **Depende de confirmação do sujeito?**: Não diretamente.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: TipoFormacaoFreudiana::Chiste + rótulo lacaniano correspondente quando comLeituraLacaniana=true
- **Limitações computacionais**: O sistema reconhece a forma, nunca o efeito de prazer nem o conteúdo recalcado que o chiste expressaria.
- **Trabalhos científicos relacionados**: Os Chistes e sua Relação com o Inconsciente; A Instância da Letra no Inconsciente ou a Razão desde Freud
- **Motores impactados**: Freud Engine; Lacan Engine

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere na resposta dada ao Sujeito — a classificação roda em paralelo, nunca compõe a resposta socrática (Regra 11).
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: A IA pode acompanhar o tom leve de uma mensagem via Modo Socrático, mas nunca nomeia "chiste" nem indica reconhecimento da técnica.
- **Quais perguntas podem ser feitas?**: As perguntas socráticas genéricas já em produção.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie "chiste", "condensação" ou "deslocamento" para o Sujeito.

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado na tela de Observações do Sujeito como rótulo estrutural ao lado do Evento Discursivo classificado.
- **Quais visualizações são produzidas?**: Rótulo de tipo de formação e, lado a lado, a coluna "Leitura Lacaniana".
- **Quais relações podem ser exibidas?**: Rótulo lacaniano correspondente e fundamentação teórica (fundamentacaoPara()).
- **Quais evidências sustentam essa representação?**: TipoFormacaoFreudiana::Chiste
- **Quais motores produzem essa informação?**: Freud Engine; Lacan Engine
- **Quais componentes do sistema participam dessa construção?**: ObservacoesSujeitoController; ClassificadorFreudianoLLM; ReclassificadorLacaniano
