# Visualizações — Representação Computacional

> Sprint 29. Catálogo de todos os tipos de visualização permitidos ao Analista. Para cada um, respondidas as nove perguntas obrigatórias do briefing desta Sprint: Objetivo, Evidências utilizadas, Componentes envolvidos, Motores envolvidos, Dados necessários, Dados opcionais, Pode ser produzida automaticamente?, Depende do analista?, O sujeito pode visualizar?

## Nota de leitura

A última pergunta tem sempre a mesma resposta nesta versão — "Não" — para as oito visualizações abaixo, por princípio permanente ([Interface-Sujeito.md](Interface-Sujeito.md)). Ela é repetida em cada seção, não omitida, porque o objetivo do catálogo é ser auditável linha a linha, sem depender de memória do princípio geral.

## 1. Timeline

- **Objetivo**: exibir a sequência cronológica de Sessões, Discursos, Eventos Discursivos e Memórias de um Sujeito.
- **Evidências utilizadas**: registros primários persistidos (ver [Evidencias.md](Evidencias.md)).
- **Componentes envolvidos**: `LinhaDoTempoApplicationService`, `LinhaDoTempoItemDTO`, `HistoricoSujeitoController`.
- **Motores envolvidos**: nenhum (Discourse Engine expõe, mas não classifica) — infraestrutura de observação temporal.
- **Dados necessários**: `Sujeito` com ≥1 `Sessao`.
- **Dados opcionais**: filtro por tipo/período/texto (`de`, `ate`, `q`).
- **Pode ser produzida automaticamente?**: Sim.
- **Depende do analista?**: Não para ser produzida; depende do Analista para ser interpretada.
- **O sujeito pode visualizar?**: Não.

## 2. Memória Longitudinal

- **Objetivo**: consolidar a evolução temporal completa das Sessões de um Sujeito.
- **Evidências utilizadas**: sequência de `Sessao` (ver [Memoria-Longitudinal.md](Memoria-Longitudinal.md)).
- **Componentes envolvidos**: `MemoriaLongitudinal`, `ConstruirMemoriaLongitudinalHandler`, `ConsolidacaoApplicationService`.
- **Motores envolvidos**: Discourse Engine (organiza o discurso sem interpretar).
- **Dados necessários**: `Sujeito` com ≥1 `Sessao`.
- **Dados opcionais**: nenhum.
- **Pode ser produzida automaticamente?**: Sim.
- **Depende do analista?**: Não.
- **O sujeito pode visualizar?**: Não.

## 3. Recorrências

- **Objetivo**: exibir conteúdos discursivos que se repetiram, com frequência e ocorrências.
- **Evidências utilizadas**: comparação normalizada de conteúdo entre Eventos Discursivos (ver [Recorrencias.md](Recorrencias.md)).
- **Componentes envolvidos**: `DetectorRecorrencias`, `Recorrencia`, `RecorrenciaMinimaSpecification`.
- **Motores envolvidos**: Freud Engine.
- **Dados necessários**: ≥2 `EventoDiscursivo` com o mesmo conteúdo normalizado.
- **Dados opcionais**: `vocabulario=lacan`, `minimoDeRecorrencia`.
- **Pode ser produzida automaticamente?**: Sim.
- **Depende do analista?**: Não para ser produzida; toda leitura permanece do Analista (Regra 10).
- **O sujeito pode visualizar?**: Não.

## 4. Formações Freudianas

- **Objetivo**: classificar a forma estrutural de um conteúdo discursivo (ato falho, chiste, sonho, formação de compromisso, repetição).
- **Evidências utilizadas**: classificação fechada via LLM, validada contra enum de 6 valores (ver [Formacoes-Freudianas.md](Formacoes-Freudianas.md)).
- **Componentes envolvidos**: `TipoFormacaoFreudiana`, `ClassificarFormacaoFreudianaHandler`, `ClassificadorFreudianoLLM`.
- **Motores envolvidos**: Freud Engine.
- **Dados necessários**: um `EventoDiscursivo` com conteúdo textual.
- **Dados opcionais**: nenhum.
- **Pode ser produzida automaticamente?**: Sim — sujeita a `NaoClassificado` quando o classificador não decide com clareza ou falha tecnicamente.
- **Depende do analista?**: Não para ser produzida; sim para qualquer leitura clínica sobre o resultado.
- **O sujeito pode visualizar?**: Não.

## 5. Representações Lacanianas

- **Objetivo**: reclassificar, com vocabulário lacaniano, um fato estrutural já observado pelo Motor Freud (metonímia, metáfora e as demais estruturas mapeadas, sem observação própria nesta versão).
- **Evidências utilizadas**: tabela de lookup determinística sobre Recorrência ou classificação freudiana (ver [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)).
- **Componentes envolvidos**: `ReclassificadorLacaniano`.
- **Motores envolvidos**: Lacan Engine.
- **Dados necessários**: uma `Recorrencia` ou um `TipoFormacaoFreudiana` já classificado.
- **Dados opcionais**: `OcorrenciaRecorrencia[]` (circuito).
- **Pode ser produzida automaticamente?**: Sim, para metonímia e metáfora (via ponte freudiana); Não para as seis estruturas restantes (cadeia significante, Outro, Falta, Objeto a, RSI, desejo lacaniano) — sem representação computacional nesta versão.
- **Depende do analista?**: Não para ser produzida; a fundamentação teórica é exclusiva do Analista (Regra 11).
- **O sujeito pode visualizar?**: Não.

## 6. Circuitos

- **Objetivo**: identificar quando uma Recorrência atravessa ≥2 Sessões distintas — o retorno de um mesmo conteúdo ao longo do tempo.
- **Evidências utilizadas**: sequência de `OcorrenciaRecorrencia` em Sessões distintas (ver [Circuitos.md](Circuitos.md)).
- **Componentes envolvidos**: `DetectorRecorrencias::detectarCircuito()`, `ReclassificadorLacaniano::reclassificarComTrajeto()`, `CircuitoRecorrenciaDTO`.
- **Motores envolvidos**: Freud Engine (base) e Lacan Engine (rótulo de circuito).
- **Dados necessários**: uma `Recorrencia` com ocorrências em ≥2 Sessões.
- **Dados opcionais**: `vocabulario=lacan`.
- **Pode ser produzida automaticamente?**: Sim.
- **Depende do analista?**: Não para ser produzida.
- **O sujeito pode visualizar?**: Não.

## 7. Grafos

- **Objetivo**: representar visualmente o circuito/trajeto de recorrências (implementado) ou as relações entre os 21 conceitos canônicos (especificado, sem implementação).
- **Evidências utilizadas**: idêntica a Circuitos (grafo real); matriz Conceito×Conceito (grafos conceituais especificados) — ver [Grafos.md](Grafos.md).
- **Componentes envolvidos**: `GrafoCircuitoViewModel`, `ObservacoesSujeitoController::grafoCircuito()` (real); nenhum para os grafos conceituais especificados.
- **Motores envolvidos**: Freud Engine e Lacan Engine (grafo do circuito, real); nenhum motor implementado para os grafos conceituais.
- **Dados necessários**: variam por tipo — ver [Grafos.md §tabela-resumo](Grafos.md#tabela-resumo).
- **Dados opcionais**: `vocabulario=lacan` (grafo do circuito).
- **Pode ser produzida automaticamente?**: Sim para o grafo do circuito; Não para os quatro grafos restantes (sem implementação nesta versão).
- **Depende do analista?**: Não para ser produzida.
- **O sujeito pode visualizar?**: Não — listado explicitamente em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza).

## 8. Indicadores

- **Objetivo**: apresentar contagens e consolidações factuais sobre o que foi registrado — nunca uma métrica de desfecho clínico.
- **Evidências utilizadas**: contagem direta sobre registros persistidos (ver [Indicadores.md](Indicadores.md)).
- **Componentes envolvidos**: `ConsolidacaoApplicationService`, `ConsolidacaoMemoriaDTO`.
- **Motores envolvidos**: nenhum — infraestrutura de consolidação, não um motor conceitual.
- **Dados necessários**: `Sujeito` com ≥1 `Sessao`.
- **Dados opcionais**: nenhum.
- **Pode ser produzida automaticamente?**: Sim.
- **Depende do analista?**: Não.
- **O sujeito pode visualizar?**: Não.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Evidencias.md](Evidencias.md)
- [Timeline.md](Timeline.md)
- [Memoria-Longitudinal.md](Memoria-Longitudinal.md)
- [Recorrencias.md](Recorrencias.md)
- [Formacoes-Freudianas.md](Formacoes-Freudianas.md)
- [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md)
- [Circuitos.md](Circuitos.md)
- [Grafos.md](Grafos.md)
- [Indicadores.md](Indicadores.md)
