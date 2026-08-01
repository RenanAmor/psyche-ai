# Formação de compromisso

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Formação de compromisso
- **Obra de origem**: Psicopatologia da Vida Cotidiana
- **Ano**: 1901
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Ato falho; Chiste; Sonhos
- **Autores relacionados**: Nenhum registrado nesta versão.
- **Obras relacionadas**: Psicopatologia da Vida Cotidiana; A Interpretação dos Sonhos; Os Chistes e sua Relação com o Inconsciente
- **Status**: Catalogado
- **Observações**: Categoria geral da qual Ato falho, Chiste e Sonhos são espécies (Ontologia-Freud.md §4).

## Aplicação Computacional

- **Objetivo computacional**: Fornece base teórica para que EventoDiscursivo associados a atos falhos, chistes e relatos de sonho recebam atenção estrutural particular — sem que o sistema afirme que são, de fato, formações de compromisso.
- **Fundamentação científica**: Psicopatologia da Vida Cotidiana (Freud, 1901) — ver Ontologia-Freud.md §3.5.
- **Dados necessários**: EventoDiscursivo.conteudo
- **Dados opcionais**: ContextoConversaDTO (turnos recentes da Sessao) — usado pelo Motor de Enunciação Socrática, não pela classificação em si
- **Eventos que podem originá-lo**: Mensagem enviada pelo Sujeito, classificada pelo Motor Freud (LLM)
- **Relações com outros conceitos**: Categoria geral da qual Ato falho, Chiste e Sonhos são espécies (Ontologia-Freud.md §4).
- **Componentes do PsycheAI que utilizam este conceito**: Domain enum TipoFormacaoFreudiana (valor FormacaoDeCompromisso, um dos 6 do enum fechado); Infrastructure/AI/ClassificadorFreudianoLLM; Application/UseCases/ClassificarFormacaoFreudiana
- **Pode ser observado automaticamente?**: Sim — via classificação estrutural por LLM com guardrail de enum fechado (Revisão do Motor Freud, Roadmap.md).
- **Pode ser organizado automaticamente?**: Não — a classificação rotula um EventoDiscursivo isolado, não organiza um conjunto.
- **Pode ser classificado automaticamente?**: Sim — TipoFormacaoFreudiana::FormacaoDeCompromisso é um dos 6 valores possíveis (os demais: AtoFalho, Chiste, Sonho, Repeticao, NaoClassificado).
- **Depende de confirmação do sujeito?**: Não diretamente — o Sujeito nunca vê o rótulo (Regra 11, Regras-Dominio.md).
- **Depende de validação do analista?**: Sim — só as telas do analista exibem o rótulo (revisão pós-Sprint 16, Peça A).
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: TipoFormacaoFreudiana::FormacaoDeCompromisso (ou NaoClassificado quando o guardrail rejeita a resposta do LLM)
- **Limitações computacionais**: Reconhecer a forma de uma formação de compromisso não é interpretá-la — o sistema nunca atribui causa ou conteúdo recalcado (Regra 7).
- **Trabalhos científicos relacionados**: Psicopatologia da Vida Cotidiana; A Interpretação dos Sonhos; Os Chistes e sua Relação com o Inconsciente
- **Motores impactados**: Freud Engine

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere na resposta dada ao Sujeito — a classificação (TipoFormacaoFreudiana) roda em paralelo à conversa, mas nunca é usada para compor a resposta socrática (Regra 11).
- **O sujeito pode perceber sua existência?**: Não — o rótulo nunca aparece em nenhuma tela ou resposta acessível ao Sujeito.
- **Como a IA deve se comportar diante dele?**: A IA responde ao Sujeito via Modo Socrático normalmente, sem sinalizar que uma classificação estrutural ocorreu em paralelo.
- **Quais perguntas podem ser feitas?**: As perguntas socráticas genéricas já em produção (GeradorDePerguntaSocraticaLLM) — nunca fazem referência à classificação freudiana.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie "formação de compromisso" ou pressuponha que o sistema identificou uma.

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado na tela de Observações do Sujeito (rota `/sujeitos/{id}/observacoes`) como rótulo estrutural ao lado do Evento Discursivo classificado.
- **Quais visualizações são produzidas?**: Rótulo de tipo de formação na tela de Observações (observacoes/mostrar.php).
- **Quais relações podem ser exibidas?**: Rótulo lacaniano correspondente, quando `comLeituraLacaniana=true` (ReclassificadorLacaniano::reclassificarPorTipoFreudiano()) e fundamentação teórica via fundamentacaoPara().
- **Quais evidências sustentam essa representação?**: TipoFormacaoFreudiana::FormacaoDeCompromisso
- **Quais motores produzem essa informação?**: Freud Engine
- **Quais componentes do sistema participam dessa construção?**: ObservacoesSujeitoController; ClassificadorFreudianoLLM; ObservacaoApplicationService
