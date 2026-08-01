# Interface do Analista — Psyche AI

> Versão 1.0 — Sprint 28. Especificação da interface do Analista, o lado da arquitetura que a ECO nunca alcança. Nenhuma tela nova foi criada para produzir este documento.

## O analista está fora da ECO

A ECO é, por definição, a interface conversacional exclusiva do sujeito ([Manifesto.md](Manifesto.md)). O analista não conversa com a ECO — ele acessa uma interface distinta, protegida por autenticação própria (`PortaoDeAnalista`, `AutenticacaoAnalistaController`, Sprint 18), que expõe exatamente as estruturas que a interface do Sujeito nunca mostra ([Interface-Sujeito.md](Interface-Sujeito.md)).

## O que o analista pode visualizar

Como apoio à escuta clínica — nunca como diagnóstico automático (Regra 9, [Regras-Dominio.md](../Regras-Dominio.md)):

- **Memória longitudinal** — `MemoriaLongitudinal`, a consolidação das Sessões de um Sujeito ao longo do tempo. **Implementado** (Sprint 8/13).
- **Recorrências** — `Recorrencia`, produzidas por `DetectorRecorrencias` e expostas em `/sujeitos/{id}/observacoes`. **Implementado** (Sprint 14/15).
- **Formações freudianas** — `TipoFormacaoFreudiana` (ato falho, chiste, sonho, formação de compromisso), classificadas por `ClassificadorFreudianoLLM`. **Implementado**.
- **Representações lacanianas** — o rótulo de `ReclassificadorLacaniano` ("deslize metonímico"), a única reclassificação lacaniana efetivamente produzida hoje. **Implementado** (Sprint 16); demais rótulos lacanianos mapeados mas não disparados pelo detector atual (ver [Modelo-Observacional/README.md](../Modelo-Observacional/README.md)).
- **Timelines** — `LinhaDoTempoApplicationService`, a linha do tempo discursiva completa de um Sujeito, com filtro por tipo/período/texto. **Implementado** (Sprint 13).
- **Circuitos** — o grafo de circuito de recorrências (`/sujeitos/{id}/observacoes/grafo-circuito`). **Implementado**.
- **Grafos** — visualizações topológicas das relações entre conceitos ou observações. O Grafo Integrado e os demais grafos científicos estão especificados (sem implementação) em [Modelo-Relacional/Grafos/](../Modelo-Relacional/Grafos/README.md); o grafo de circuito de recorrências já é real, acima.
- **Indicadores** — contagens e consolidações (`ConsolidacaoApplicationService`): quantidade de Sessões, Discursos, Eventos Discursivos, Memórias. **Implementado** (Sprint 13).
- **Observações computacionais** — o objeto `Observacao`, produzido por `GeradorObservacoes`/`CicloDeObservacaoService`, descrevendo fatos encontrados sem interpretação clínica (Regra 8, [Regras-Dominio.md](../Regras-Dominio.md)). **Implementado** (Sprint 14).

## O que o analista nunca recebe do sistema

Mesmo tendo acesso a todas as estruturas acima, o analista nunca recebe do PsycheAI:

- diagnóstico automático (Regra 9);
- interpretação do que uma estrutura significa para aquele sujeito específico — apenas a fundamentação teórica da ontologia que gerou o rótulo (Regra 11, [Regras-Dominio.md](../Regras-Dominio.md));
- qualquer decisão automatizada sobre conduta clínica.

Toda interpretação permanece responsabilidade exclusiva do analista (Regra 10).

## Fronteira técnica com a ECO

Nenhum componente da interface do Analista (`PortaoDeAnalista`, `HistoricoSujeitoController`, `ObservacoesSujeitoController`, `EventosDiscursivosController`) é acessado pelas rotas `/conversa*` da ECO, e vice-versa — são dois conjuntos de rotas e controllers disjuntos desde a Sprint 18, sem nenhum ponto de acoplamento no código de Apresentação (ver [Arquitetura.md §9.2](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista)).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Limites-da-ECO.md](Limites-da-ECO.md)
- [../Arquitetura.md](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Modelo-Observacional/README.md](../Modelo-Observacional/README.md)
- [../Modelo-Relacional/Grafos/README.md](../Modelo-Relacional/Grafos/README.md)
