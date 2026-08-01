# Metonímia

## Metadados

- **Autor**: Jacques Lacan
- **Conceito**: Metonímia
- **Obra de origem**: A Instância da Letra no Inconsciente ou a Razão desde Freud
- **Ano**: 1957
- **Idioma**: Francês
- **Área**: Lacan
- **Conceitos relacionados**: Metáfora; Repetição; Significante
- **Autores relacionados**: Roman Jakobson
- **Obras relacionadas**: A Instância da Letra no Inconsciente ou a Razão desde Freud; O Seminário sobre "A Carta Roubada"
- **Status**: Catalogado
- **Observações**: Único conceito lacaniano com reclassificação efetivamente produzida pelo sistema nesta versão.

## Aplicação Computacional

- **Objetivo computacional**: É o rótulo estrutural lacaniano efetivamente produzido hoje pelo sistema, reclassificando com vocabulário lacaniano as mesmas recorrências já trazidas pelo Motor Freud.
- **Fundamentação científica**: A Instância da Letra no Inconsciente ou a Razão desde Freud — ver Ontologia-Lacan.md §4 (tabela Deslocamento→Metonímia).
- **Dados necessários**: Recorrencia[] já produzida pelo DetectorRecorrencias
- **Dados opcionais**: OcorrenciaRecorrencia[] (circuito) — quando presente em ≥2 Sessões distintas, o rótulo passa a "circuito" em vez de "deslize metonímico" (revisão pós-Sprint 16, Peça C)
- **Eventos que podem originá-lo**: Recorrência já detectada pelo Motor Freud (≥2 ocorrências de conteúdo normalizado)
- **Relações com outros conceitos**: Par com Metáfora; releitura estrutural de Deslocamento (Ontologia-Freud.md).
- **Componentes do PsycheAI que utilizam este conceito**: Domain/Services/ReclassificadorLacaniano::reclassificar() / reclassificarComTrajeto()
- **Pode ser observado automaticamente?**: Não (reclassifica dado já observado pelo Motor Freud, não observa por conta própria).
- **Pode ser organizado automaticamente?**: Sim (a reclassificação é, em si, uma forma de organização/rotulagem sobre dado já existente).
- **Pode ser classificado automaticamente?**: Sim — sempre como "Estrutura candidata: deslize metonímico." (ou variante de circuito), nunca afirmando estatuto de significante confirmado.
- **Depende de confirmação do sujeito?**: Não diretamente — só nas telas do analista.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: Rótulo "Estrutura candidata: deslize metonímico." ou "...circuito — o tema retorna ao mesmo ponto através de sessões distintas."
- **Limitações computacionais**: Nunca afirma o estatuto de significante confirmado — permanece "estrutura candidata" (Ontologia-Lacan.md §5).
- **Trabalhos científicos relacionados**: A Instância da Letra no Inconsciente ou a Razão desde Freud; O Seminário sobre "A Carta Roubada"
- **Motores impactados**: Lacan Engine

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere na conversa — é uma reclassificação exclusiva das telas do analista (Regra 11).
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: A IA nunca menciona metonímia, deslize ou estrutura lacaniana ao Sujeito.
- **Quais perguntas podem ser feitas?**: Nenhuma — este conceito não gera pergunta ao Sujeito nesta versão (a pergunta-eco pertence ao conceito de Repetição, não a este rótulo).
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie metonímia, significante ou estrutura lacaniana.

### Visão do Analista

- **Como o conceito é apresentado?**: Apresentado como rótulo "Estrutura candidata: deslize metonímico." (ou variante de circuito) ao lado de cada recorrência, na tela de Observações.
- **Quais visualizações são produzidas?**: Coluna "Leitura Lacaniana" na tela de Observações; rótulo no grafo do circuito quando aplicável.
- **Quais relações podem ser exibidas?**: A recorrência de base (Motor Freud) e, quando em ≥2 Sessões, o circuito correspondente.
- **Quais evidências sustentam essa representação?**: Rótulo textual de ReclassificadorLacaniano
- **Quais motores produzem essa informação?**: Lacan Engine
- **Quais componentes do sistema participam dessa construção?**: ReclassificadorLacaniano; ObservacoesSujeitoController; observacoes/mostrar.php
