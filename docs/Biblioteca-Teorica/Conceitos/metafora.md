# Metáfora

## Metadados

- **Autor**: Jacques Lacan
- **Conceito**: Metáfora
- **Obra de origem**: A Instância da Letra no Inconsciente ou a Razão desde Freud
- **Ano**: 1957
- **Idioma**: Francês
- **Área**: Lacan
- **Conceitos relacionados**: Metonímia; Formação de compromisso; Significante
- **Autores relacionados**: Roman Jakobson
- **Obras relacionadas**: A Instância da Letra no Inconsciente ou a Razão desde Freud; As Formações do Inconsciente (Seminário V)
- **Status**: Catalogado
- **Observações**: 

## Aplicação Computacional

- **Objetivo computacional**: Fundamenta a releitura estrutural da condensação freudiana como substituição de um significante por outro — reclassificação disparada por ponte com o Motor Freud (Chiste/Sonho), nunca por observação direta de substituição entre dois conteúdos distintos.
- **Fundamentação científica**: A Instância da Letra no Inconsciente ou a Razão desde Freud — ver Ontologia-Lacan.md §4 (tabela Condensação→Metáfora).
- **Dados necessários**: Um `EventoDiscursivo` classificado como `TipoFormacaoFreudiana::Chiste` ou `::Sonho` por `ClassificadorFreudianoLLM`, dentro de uma Recorrência sem circuito (< 2 sessões distintas) — `DetectorRecorrencias` continua sem captar diretamente a substituição entre dois conteúdos distintos que fundamentaria uma metáfora observada em primeira mão.
- **Dados opcionais**: Nenhum registrado nesta versão.
- **Eventos que podem originá-lo**: Um Evento Discursivo cujo conteúdo o Motor Freud classifica como Chiste ou Sonho, consultado via `ObservacaoApplicationService::consultarCircuito()`.
- **Relações com outros conceitos**: Par com Metonímia; releitura estrutural de Condensação (Ontologia-Freud.md).
- **Componentes do PsycheAI que utilizam este conceito**: `Domain/Services/ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — retorna o rótulo de metáfora quando o Motor Freud classifica o conteúdo como Chiste ou Sonho; alcançado em produção por `ObservacaoApplicationService::consultarCircuito()` → `rotularComFundamentacao()` (auditado nesta Sprint 30 contra `app/Application/Services/ObservacaoApplicationService.php`, correção de uma imprecisão presente desde a Sprint 25).
- **Pode ser observado automaticamente?**: Não diretamente (nenhum detector reconhece substituição entre dois conteúdos distintos); Sim indiretamente, por reclassificação de uma classificação freudiana já produzida.
- **Pode ser organizado automaticamente?**: Não.
- **Pode ser classificado automaticamente?**: Sim, indiretamente — via ponte com `TipoFormacaoFreudiana::Chiste`/`::Sonho`, sempre como estrutura candidata.
- **Depende de confirmação do sujeito?**: Sim.
- **Depende de validação do analista?**: Sim.
- **Gera hipótese clínica?**: Nunca automaticamente.
- **Evidências produzidas pelo sistema**: O rótulo "Estrutura candidata: metáfora — condensação", exclusivo da interface do Analista.
- **Limitações computacionais**: A observação direta exigiria detectar substituição entre dois conteúdos distintos, não apenas repetição do mesmo conteúdo — mudança de escopo do detector ainda não decidida com o usuário; o caminho hoje disponível depende inteiramente da classificação freudiana prévia, nunca de evidência própria de metáfora.
- **Trabalhos científicos relacionados**: A Instância da Letra no Inconsciente ou a Razão desde Freud; As Formações do Inconsciente (Seminário V)
- **Motores impactados**: Motor Freud (fornece a classificação de origem); Lacan Engine (produz o rótulo de metáfora por reclassificação)

## Representação Computacional

### Visão do Sujeito

- **Como este conceito interfere na conversa?**: Não interfere — a reclassificação lacaniana nunca compõe a resposta ao Sujeito (Documento-Mestre.md §5).
- **O sujeito pode perceber sua existência?**: Não.
- **Como a IA deve se comportar diante dele?**: Nenhum comportamento voltado ao Sujeito é derivado deste conceito.
- **Quais perguntas podem ser feitas?**: Nenhuma pergunta é derivada diretamente deste conceito para o Sujeito.
- **Quais perguntas nunca podem ser feitas?**: Qualquer pergunta que nomeie "metáfora" ou estrutura de substituição significante para o Sujeito.

### Visão do Analista

- **Como o conceito é apresentado?**: Como rótulo estrutural ("Estrutura candidata: metáfora — condensação") com fundamentação teórica, quando uma Recorrência sem circuito tem seu conteúdo classificado como Chiste ou Sonho pelo Motor Freud.
- **Quais visualizações são produzidas?**: O rótulo lacaniano na tela de Observações (`/sujeitos/{id}/observacoes?vocabulario=lacan`), quando disparado.
- **Quais relações podem ser exibidas?**: A fundamentação teórica de `ReclassificadorLacaniano::fundamentacaoPara()` (Regra 11).
- **Quais evidências sustentam essa representação?**: A classificação freudiana prévia (`TipoFormacaoFreudiana::Chiste`/`::Sonho`) — nunca evidência própria de substituição entre dois significantes.
- **Quais motores produzem essa informação?**: Motor Freud (classificação de origem) e Motor Lacan (reclassificação).
- **Quais componentes do sistema participam dessa construção?**: `ClassificadorFreudianoLLM`, `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()`, `ObservacaoApplicationService::consultarCircuito()`.
