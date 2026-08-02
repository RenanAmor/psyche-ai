# Timeline — Representação Computacional

> Sprint 29. Especifica a representação da Linha do Tempo Discursiva de um Sujeito ao Analista: sessões, eventos, mudanças, recorrências, interrupções, retornos e intervalos, cada um com seu estado de implementação auditado contra o código real.

## Objetivo

Apresentar ao Analista a sequência cronológica completa do que já foi registrado de um Sujeito — sem interpretar, sem hierarquizar por importância, sem agrupar por recorrência a menos que explicitamente solicitado. Implementa, na interface, o [Princípio da Neutralidade Observacional](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional): todo item permanece visível independente do desfecho do caso.

## Rastreabilidade

```
Biblioteca Teórica: nenhum conceito psicanalítico específico — a Timeline é infraestrutura de observação temporal, base para todas as demais representações
Modelo Observacional: Modelo-Computacional-Discurso.md (Evento Discursivo, temporalidade)
Modelo Relacional: relações "temporais" classificadas em Modelo-Relacional/Conceitos/ (Natureza = Temporal)
Representação Computacional: este documento
```

## As sete dimensões

| Dimensão | Estado | Fundamentação |
|---|---|---|
| **Sessões** | Implementado — `LinhaDoTempoItemDTO::TIPO_SESSAO` | Cada Sessão vira um item com `id`, `data`, `quantidadeDeDiscursos` (`LinhaDoTempoApplicationService::itemDeSessao()`) |
| **Eventos** | Implementado — `LinhaDoTempoItemDTO::TIPO_EVENTO` | Cada Evento Discursivo vira um item com `conteudo`, `posicao`, `criadoEm`, vinculado a Discurso e Sessão (`itemDeEvento()`) |
| **Recorrências** | Não incorporado à Timeline — implementado como representação própria | `Recorrencia` é calculada por `DetectorRecorrencias` e consultada separadamente via `GET /subjects/{id}/observations` — ver [Recorrencias.md](Recorrencias.md). A Timeline não funde os dois hoje: quem quer ver sessões/eventos cronológicos usa `/sujeitos/{id}/historico`; quem quer ver recorrências usa `/sujeitos/{id}/observacoes` |
| **Interrupções** | Não implementado — especificação para sprint futura | Corresponde ao "Status do Caso" já registrado como conceito observacional em [Modelo-Observacional.md §3](../Modelo-Observacional.md#3-status-do-caso) ("Interrompido pelo sujeito", "Interrompido pelo analista", "Abandono"), mas ainda sem representação no Domínio, API ou Web nesta versão |
| **Retornos** | Não incorporado à Timeline — implementado como parte do Circuito | Um "retorno" é, computacionalmente, uma `Recorrencia` cujas ocorrências atravessam ≥2 sessões distintas — já produzido por `detectarCircuito()`, mas exposto como representação própria (ver [Circuitos.md](Circuitos.md)), não como item da Timeline |
| **Mudanças** | Não implementado — especificação para sprint futura | Exigiria comparação de conteúdo entre sessões distintas além da detecção de repetição exata/normalizada já feita por `DetectorRecorrencias` — nenhum componente do sistema hoje compara "o que mudou" entre dois momentos |
| **Intervalos** | Parcialmente implementado — derivável, não persistido como campo | `LinhaDoTempoApplicationService::consultar()` aceita filtro `de`/`ate` (intervalo de consulta) e cada item carrega `timestamp`; a duração *entre* itens específicos (ex.: tempo entre duas ocorrências de uma mesma Recorrência) é calculável a partir de `OcorrenciaRecorrencia::momento()`, mas não é um campo exposto diretamente — ver "Duração" em [Recorrencias.md](Recorrencias.md) |

## Dados necessários

`Sujeito` com pelo menos uma `Sessao` registrada. Nenhum dado opcional altera a existência do item — apenas seu conteúdo (`Discurso`/`EventoDiscursivo`/`MemoriaLongitudinal`, quando existentes).

## Componentes envolvidos

`LinhaDoTempoApplicationService`, `LinhaDoTempoItemDTO`, `LinhaDoTempoResultadoDTO`, `LinhaDoTempoController`, `HistoricoSujeitoController`, tela `sujeitos/{id}/historico` (Sprint 13).

## Evidências que sustentam esta representação

Os próprios registros já persistidos — `Sessao`, `Discurso`, `EventoDiscursivo`, `MemoriaLongitudinal` — sem nenhuma inferência adicional. A Timeline nunca produz um item que não corresponda a um registro real; ela apenas ordena, filtra e projeta (ver [Evidencias.md](Evidencias.md)).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista, atrás de `PortaoDeAnalista::proteger()`. O Sujeito nunca visualiza a Timeline estruturada — ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Memoria-Longitudinal.md](Memoria-Longitudinal.md)
- [Recorrencias.md](Recorrencias.md)
- [Circuitos.md](Circuitos.md)
- [Evidencias.md](Evidencias.md)
- [../Modelo-Computacional-Discurso.md](../Modelo-Computacional-Discurso.md)
- [../Modelo-Observacional.md](../Modelo-Observacional.md#3-status-do-caso)
