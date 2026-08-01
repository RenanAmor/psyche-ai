# Repetição

## Metadados

- **Autor**: Sigmund Freud
- **Conceito**: Repetição
- **Obra de origem**: Além do Princípio do Prazer
- **Ano**: 1920
- **Idioma**: Alemão
- **Área**: Freud
- **Conceitos relacionados**: Transferência; Pulsão; Metonímia
- **Autores relacionados**: Jacques Lacan
- **Obras relacionadas**: Além do Princípio do Prazer; A Dinâmica da Transferência; Recordar, Repetir e Elaborar
- **Status**: Catalogado
- **Observações**: Conceito com o maior grau de implementação computacional real de toda a Biblioteca Teórica nesta versão.

## Aplicação Computacional

- **Objetivo computacional**: É o conceito mais diretamente implementado do sistema — fundamenta diretamente a detecção de recorrência de conteúdo ao longo do tempo como objeto teórico relevante (atenção flutuante, Documento-Mestre.md §6.7).
- **Fundamentação científica**: Além do Princípio do Prazer (Freud, 1920) — ver Ontologia-Freud.md §3.9.
- **Dados necessários**: EventoDiscursivo.conteudo (normalizado — trim + minúsculas, desde a Sprint 15); Sessao.data / EventoDiscursivo.criadoEm (para o circuito/trajeto)
- **Dados opcionais**: Nenhum registrado nesta versão.
- **Eventos que podem originá-lo**: Dois ou mais EventoDiscursivo com o mesmo conteúdo normalizado, em qualquer Sessao do mesmo Sujeito
- **Relações com outros conceitos**: Intersecta a Transferência (repetição encenada na relação em vez de lembrada) e a Pulsão (um de seus destinos possíveis) — Ontologia-Freud.md §4.
- **Componentes do PsycheAI que utilizam este conceito**: Domain/Services/DetectorRecorrencias (detectar(), normalizar(), detectarCircuito()); Domain/Entities/Recorrencia; Domain/Specifications/RecorrenciaMinimaSpecification (limiar mínimo=2); Domain/ValueObjects/OcorrenciaRecorrencia; Application/Services/ObservacaoApplicationService
- **Pode ser observado automaticamente?**: Sim.
- **Pode ser organizado automaticamente?**: Sim — GeradorObservacoes/CicloDeObservacaoService organizam recorrências em Observacao; o circuito organiza a ordem cronológica das ocorrências entre Sessões.
- **Pode ser classificado automaticamente?**: Não — a recorrência é contada e organizada, nunca classificada quanto a causa ou sentido.
- **Depende de confirmação do sujeito?**: Não diretamente na tela do Sujeito (Regra 11: /conversa* nunca expõe isso); RespostaEcoRecorrenciaService devolve pergunta-eco ao Sujeito, nunca afirmação.
- **Depende de validação do analista?**: Sim — toda leitura de causa da repetição é do analista (Regra 10).
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: Recorrencia (conteúdo normalizado + contagem); Observacao; CircuitoRecorrenciaDTO (trajeto cronológico entre Sessões)
- **Limitações computacionais**: O sistema nunca afirma por que algo se repete — apenas que se repete (Regra 7, Regras-Dominio.md).
- **Trabalhos científicos relacionados**: Além do Princípio do Prazer; A Dinâmica da Transferência; Recordar, Repetir e Elaborar
- **Motores impactados**: Discourse Engine; Freud Engine; Lacan Engine; Modo Socrático

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Sim, indiretamente — quando uma recorrência é detectada, RespostaEcoRecorrenciaService/RespostaSocraticaService devolvem uma pergunta-eco ao Sujeito nomeando a repetição, sem interpretar a causa.
- **O sujeito pode perceber sua existência?**: Sim, de forma limitada — o Sujeito pode notar que o sistema "percebeu" que voltou a falar em algo, mas nunca vê a palavra "recorrência", contagem ou circuito.
- **Como a IA deve se comportar diante dele?**: Devolve pergunta-eco ("Você voltou a falar em '%s'. O que vem à mente sobre isso?") — nunca afirma que há repetição como fato objetivo, apenas pergunta a partir dela.
- **Quais perguntas podem ser feitas?**: A pergunta-eco de RespostaEcoRecorrenciaService/RespostaSocraticaService, já em produção desde a Sprint 17.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que cite contagem, data das ocorrências anteriores, ou nomeie "recorrência"/"circuito" para o Sujeito.

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado nas telas de Observações (lista de recorrências e frequência) e no grafo do circuito (D3), rota `/sujeitos/{id}/observacoes/grafo-circuito`.
- **Quais visualizações são produzidas?**: Tabela de recorrências com frequência; grafo de circuito (nós = Sessões em ordem cronológica, arestas = ocorrências consecutivas), via GrafoCircuitoViewModel.
- **Quais relações podem ser exibidas?**: Trajeto cronológico entre Sessões (CircuitoTrajetoComponent); rótulo lacaniano lado a lado, quando `comLeituraLacaniana=true`.
- **Quais evidências sustentam essa representação?**: Recorrencia; Observacao; CircuitoRecorrenciaDTO
- **Quais motores produzem essa informação?**: Discourse Engine; Freud Engine; Lacan Engine
- **Quais componentes do sistema participam dessa construção?**: ObservacoesSujeitoController; GrafoCircuitoViewModel; CircuitoTrajetoComponent; RespostaEcoRecorrenciaService (produz a peça do lado do Sujeito)
