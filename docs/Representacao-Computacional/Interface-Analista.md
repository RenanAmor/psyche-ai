# Interface do Analista — Representação Computacional

> Sprint 29. Especifica todas as representações disponíveis ao Analista dentro desta camada. Complementa [ECO/Interface-Analista.md](../ECO/Interface-Analista.md) — que já lista as estruturas visíveis ao Analista e seu estado de implementação — detalhando, para cada uma, o documento próprio desta pasta que a especifica em profundidade (fenômeno, evidências, dados, rastreabilidade).

## O Analista está fora da ECO

Mesma fronteira já estabelecida em [ECO/Interface-Analista.md §1](../ECO/Interface-Analista.md#o-analista-está-fora-da-eco): o Analista não conversa com a ECO. Acessa uma interface distinta, protegida por `PortaoDeAnalista` (Sprint 18), que expõe exatamente as oito representações abaixo — nunca como diagnóstico automático (Regra 9, [Regras-Dominio.md](../Regras-Dominio.md)), sempre como apoio à escuta clínica (Regra 10).

## As oito representações disponíveis

| Representação | Documento | Estado |
|---|---|---|
| Timeline | [Timeline.md](Timeline.md) | Implementado (`LinhaDoTempoApplicationService`, Sprint 13) |
| Memória Longitudinal | [Memoria-Longitudinal.md](Memoria-Longitudinal.md) | Implementado (`MemoriaLongitudinal`, Sprint 8/13) |
| Recorrências | [Recorrencias.md](Recorrencias.md) | Parcialmente implementado (`Recorrencia`/`DetectorRecorrencias`, Sprint 14/15 — ver limites de cada campo no documento próprio) |
| Formações Freudianas | [Formacoes-Freudianas.md](Formacoes-Freudianas.md) | Implementado (`TipoFormacaoFreudiana`/`ClassificadorFreudianoLLM`) |
| Representações Lacanianas | [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md) | Parcialmente implementado (`ReclassificadorLacaniano`, Sprint 16 — só metonímia e metáfora reclassificadas; demais estruturas lacanianas sem representação computacional nesta versão) |
| Circuitos | [Circuitos.md](Circuitos.md) | Implementado (`consultarCircuito()`, revisão pós-Sprint 16) |
| Grafos | [Grafos.md](Grafos.md) | Parcialmente implementado (grafo do circuito real, Sprint 19; os cinco grafos científicos do Modelo Relacional são especificação, sem implementação) |
| Indicadores | [Indicadores.md](Indicadores.md) | Implementado (`ConsolidacaoApplicationService`, Sprint 13) |

Nenhuma nona representação existe nesta versão. Qualquer representação futura deve, antes de qualquer código, ser documentada nesta pasta — mesma obrigatoriedade já registrada em [README.md](README.md#por-que-esta-camada-existe-separada-do-modelo-relacional).

## Como cada representação é acessada hoje

Todas as oito são consultadas por rotas protegidas por `PortaoDeAnalista::proteger()` (Sprint 18), nunca alcançadas pelas rotas `/conversa*` da ECO — ver [Interface-Sujeito.md](Interface-Sujeito.md#garantia-técnica-já-existente):

- Timeline e Consolidação (Indicadores): `GET /subjects/{id}/timeline`, `GET /subjects/{id}/consolidation`, tela `/sujeitos/{id}/historico`.
- Recorrências, Formações Freudianas (via classificação), Representações Lacanianas e Circuitos: `GET /subjects/{id}/observations` (parâmetros `vocabulario=lacan`, `minimoDeRecorrencia`), tela `/sujeitos/{id}/observacoes`.
- Grafo do Circuito: `GET /sujeitos/{id}/observacoes/grafo-circuito`.
- Memória Longitudinal: consolidada nas mesmas telas acima, sem tela própria isolada.

## O que o Analista nunca recebe

Mesma lista permanente de [ECO/Interface-Analista.md §"O que o analista nunca recebe do sistema"](../ECO/Interface-Analista.md#o-que-o-analista-nunca-recebe-do-sistema): diagnóstico automático (Regra 9); interpretação do que uma estrutura significa para aquele sujeito específico, além da fundamentação teórica que gerou o rótulo (Regra 11); qualquer decisão automatizada sobre conduta clínica. Toda interpretação permanece responsabilidade exclusiva do Analista (Regra 10).

## Fronteira técnica com a ECO

Nenhum componente da interface do Analista (`PortaoDeAnalista`, `HistoricoSujeitoController`, `ObservacoesSujeitoController`) é acessado pelas rotas `/conversa*`, e vice-versa — mesma garantia já documentada em [ECO/Interface-Analista.md §"Fronteira técnica com a ECO"](../ECO/Interface-Analista.md#fronteira-técnica-com-a-eco) e em [Arquitetura.md §9.2](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Principios.md](Principios.md)
- [Evidencias.md](Evidencias.md)
- [Visualizacoes.md](Visualizacoes.md)
- [../ECO/Interface-Analista.md](../ECO/Interface-Analista.md)
- [../Arquitetura.md](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista)
- [../Regras-Dominio.md](../Regras-Dominio.md)
