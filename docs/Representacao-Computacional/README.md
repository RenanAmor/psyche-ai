# Representação Computacional — Psyche AI

> Sprint 29; seção "Origem dos Dados" adicionada por decisão de arquitetura permanente pós-Sprint 30, ajustada por decisão posterior que consolidou a organização de três modos de operação da plataforma para dois. Modelo oficial de Representação Computacional do PsycheAI: como toda observação produzida pelos motores (Discourse Engine, Motor Freud, Motor Lacan) é apresentada ao Analista e, de forma estritamente distinta, ao Sujeito. Camada da [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) entre o [Modelo Relacional](../Modelo-Relacional/README.md) (como os 21 conceitos canônicos se relacionam entre si) e a Ontologia (vocabulário conceitual fixado em [Ontologia-Freud.md](../Ontologia-Freud.md)/[Ontologia-Lacan.md](../Ontologia-Lacan.md)). Sprint exclusivamente científica: nenhum código, API, banco de dados ou teste foi alterado para produzir esta documentação.

## O que esta camada é

A ponte definitiva entre os Motores do PsycheAI e a interface que efetivamente chega ao Analista ou ao Sujeito. Onde a Biblioteca Teórica responde "de onde vem o conceito", o Modelo Observacional responde "o que, dele, é observável" e o Modelo Relacional responde "como os conceitos se conectam entre si", a Representação Computacional responde a uma quarta pergunta, mais concreta: **como uma observação, já fundamentada e relacionada, chega a ser vista** — por quem, em que formato, sustentada por quais evidências, e com qual rastreabilidade até a literatura de origem.

Nenhuma representação documentada aqui interpreta, diagnostica, conclui ou produz hipótese clínica. Toda representação é evidência observacional, nunca leitura de sentido — mesmo limite já estabelecido pelas Regras 7–11 de [Regras-Dominio.md](../Regras-Dominio.md) e pelo [Documento-Mestre.md §6.5](../Documento-Mestre.md#65-limites-do-sistema).

## Por que esta camada existe separada do Modelo Relacional

O Modelo Relacional documenta como os conceitos se relacionam entre si — independente de qualquer interface. A Representação Computacional documenta a etapa seguinte, específica de cada um dos dois públicos do sistema: como essa rede de conceitos, evidências e relações vira algo que um Analista pode efetivamente ler em tela, ou — no caso do Sujeito — o que dela, se é que algo, pode legitimamente atravessar para a conversa. Nenhum Motor de Representação (visualização, indicador, grafo renderizado) pode ser desenvolvido sem que esta camada esteja documentada primeiro — mesma obrigatoriedade já estabelecida para o Modelo Observacional e o Modelo Relacional em [Arquitetura-Cientifica.md §1](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## Estrutura

| Documento | Conteúdo |
|---|---|
| [Principios.md](Principios.md) | Os cinco atributos obrigatórios de toda representação e as quatro proibições permanentes |
| [Interface-Sujeito.md](Interface-Sujeito.md) | O que o Sujeito pode visualizar (conversa, histórico, consentimentos, configurações) e a lista fechada do que nunca visualiza |
| [Interface-Analista.md](Interface-Analista.md) | Especificação de todas as representações disponíveis ao Analista, com estado de implementação |
| [Timeline.md](Timeline.md) | Sessões, eventos, mudanças, recorrências, interrupções, retornos, intervalos |
| [Memoria-Longitudinal.md](Memoria-Longitudinal.md) | Evolução temporal, histórico completo, continuidade, consolidação |
| [Recorrencias.md](Recorrencias.md) | Frequência, primeira/última ocorrência, intensidade, duração, persistência |
| [Formacoes-Freudianas.md](Formacoes-Freudianas.md) | Ato falho, sonho, chiste, formação de compromisso, repetição — sempre como evidência |
| [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md) | Metonímia, metáfora, cadeia significante, Outro, Falta, Objeto a, RSI, desejo — representação estrutural, nunca interpretação clínica |
| [Circuitos.md](Circuitos.md) | Circuito pulsional, retornos, mudanças, persistências, encerramentos |
| [Grafos.md](Grafos.md) | Grafo discursivo, conceitual, temporal, de recorrências, de relações — documentação, sem implementação (exceto o grafo do circuito, já real) |
| [Indicadores.md](Indicadores.md) | Indicadores observacionais — nunca indicadores clínicos |
| [Evidencias.md](Evidencias.md) | O que sustenta cada representação e sua rastreabilidade até a Biblioteca Teórica |
| [Visualizacoes.md](Visualizacoes.md) | Catálogo de todos os tipos de visualização permitidos, um por um, com as nove perguntas obrigatórias |

## Princípio geral

Toda representação documentada nesta pasta deve ser observacional, rastreável, auditável, reproduzível e fundamentada na Biblioteca Teórica — ver [Principios.md](Principios.md) para a formulação completa. Nenhuma pode interpretar, diagnosticar, concluir ou produzir hipótese clínica.

## Rastreabilidade

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

Cada documento desta pasta declara explicitamente, em sua própria seção de rastreabilidade, a quais conceitos da [Biblioteca Teórica](../Biblioteca-Teorica/README.md), fenômenos do [Modelo Observacional](../Modelo-Observacional/README.md) e relações do [Modelo Relacional](../Modelo-Relacional/README.md) corresponde — nunca uma representação sem essa cadeia completa até a fundamentação bibliográfica original.

## Origem dos Dados

Adicionado por decisão de arquitetura permanente pós-Sprint 30 ("Modos de Operação da Plataforma", [Arquitetura-Cientifica.md §8](../Arquitetura-Cientifica.md#8-modos-de-operação-da-plataforma)), ajustado por decisão posterior que consolidou a organização de três modos para dois (Modo 1, destinado ao Sujeito; Modo 2, destinado a profissionais e pesquisadores): o PsycheAI não é mais definido como plataforma exclusivamente conversacional — o discurso que alimenta os Motores pode ter múltiplas origens, sempre convergindo para a mesma Representação Computacional. Nenhuma das oito representações desta pasta muda de estrutura, campo ou significado conforme a origem do dado: todas operam sobre `EventoDiscursivo` já registrado, independentemente de como ele chegou até ali. A diferença entre origens está inteiramente antes da cadeia científica (na captação/transcrição/importação), nunca depois — este é o princípio de **equivalência computacional**.

| Origem | Modo | Estado | Caminho até o `EventoDiscursivo` |
|---|---|---|---|
| **Conversa** | Modo 1 | Implementado | `ConversaController` → `MensagemApplicationService` → `EventoDiscursivo`, já em produção desde a Sprint 12 |
| **Texto** | Modo 1 (mesmo caminho de Conversa) / Modo 2 (importado) | Implementado (Modo 1) / Não implementado (Modo 2) | Todo `EventoDiscursivo` é, por definição, texto; não há caminho de importação de texto externo (Modo 2) nesta versão |
| **Áudio** | Modo 1 (captura da própria conversa) / Modo 2 (gravação importada) | Parcialmente implementado | Captura de áudio de sessão existe desde a Sprint 22 (`StorageInterface`), usada hoje apenas dentro da conversa da ECO (Modo 1); importação de gravação já existente (Modo 2, ex.: sessão clínica) é especificação nova, sem componente de Aplicação/Apresentação implementado |
| **Transcrição** | Modo 1 ou Modo 2 | Parcialmente implementado | `TranscriptionInterface`/`StatusTranscricao` existem como porta desde a Sprint 7/22; sem implementação concreta de importação ligada ao Modo 2 |
| **Vídeo** | Modo 2 | Não implementado — especificação | Nenhum componente de captura ou processamento de vídeo existe nesta data |

Cada uma das oito representações ([Timeline.md](Timeline.md), [Memoria-Longitudinal.md](Memoria-Longitudinal.md), [Recorrencias.md](Recorrencias.md), [Formacoes-Freudianas.md](Formacoes-Freudianas.md), [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md), [Circuitos.md](Circuitos.md), [Grafos.md](Grafos.md), [Indicadores.md](Indicadores.md)) herda esta tabela por inteiro — nenhuma delas documenta uma origem de dado diferente das cinco acima, e nenhuma exige alteração de sua própria especificação para operar sobre discurso vindo de qualquer uma delas.

## Estado de implementação — nota de leitura obrigatória

Assim como a ECO ([ECO/README.md](../ECO/README.md)) e o Modelo Relacional auditam o código real antes de afirmar qualquer coisa como existente, cada documento desta pasta marca explicitamente, item a item, se a representação já está em produção (**Implementado**, com o componente real citado) ou se é apenas especificação para sprint futura (**Não implementado** / **Especificação**). Nenhuma representação documentada aqui é apresentada como implementada sem correspondência auditável no código em `app/` nesta data.

## Restrições desta Sprint

Não implementação. Não alteração de código, banco de dados, API ou testes. Sprint exclusivamente científica — mesma disciplina já aplicada pela Biblioteca Teórica (Sprint 25), pelo Modelo Observacional (Sprint 26), pelo Modelo Relacional (Sprint 27) e pela ECO (Sprint 28).

## Referências cruzadas do projeto

- [Principios.md](Principios.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Evidencias.md](Evidencias.md)
- [Visualizacoes.md](Visualizacoes.md)
- [../Biblioteca-Teorica/README.md](../Biblioteca-Teorica/README.md)
- [../Modelo-Observacional/README.md](../Modelo-Observacional/README.md)
- [../Modelo-Relacional/README.md](../Modelo-Relacional/README.md)
- [../ECO/README.md](../ECO/README.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Roadmap.md](../Roadmap.md)
