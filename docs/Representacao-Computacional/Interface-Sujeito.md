# Interface do Sujeito — Representação Computacional

> Sprint 29. Consolidação definitiva, do ponto de vista desta camada, do que o Sujeito pode e nunca pode visualizar. Não substitui [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md) — que já especifica a interface do Sujeito na ECO, com o estado de implementação de cada item auditado contra o código real — mas responde a uma pergunta complementar: de todas as representações catalogadas nesta pasta (Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos, Indicadores), quais alcançam o Sujeito. A resposta, para todas as oito, é a mesma: nenhuma.

## O que o Sujeito pode visualizar

Fechado, sem exceção — mesma lista de [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-visualiza):

- **Conversa** — o diálogo com a ECO. Nunca uma representação desta pasta: a ECO nunca recebe como entrada nenhuma leitura do Motor Freud/Lacan (ver "Garantia técnica" abaixo).
- **Histórico das suas sessões** — o próprio conteúdo do que já disse, sem nenhuma estrutura derivada (recorrência, classificação, rótulo).
- **Consentimentos** — o que autorizou sobre o tratamento de seus dados.
- **Configurações pessoais** — dados da própria conta.

## Por que nenhuma representação desta pasta alcança o Sujeito

Cada uma das oito representações documentadas nesta pasta é, por definição, uma estrutura *produzida pelos motores* sobre o discurso do Sujeito — exatamente a categoria que o [Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos) reserva exclusivamente ao Analista:

| Representação | Por que nunca chega ao Sujeito |
|---|---|
| [Timeline.md](Timeline.md) | Expõe recorrências, interrupções e retornos como estrutura nomeada — vocabulário técnico vedado ao Sujeito |
| [Memoria-Longitudinal.md](Memoria-Longitudinal.md) | Consolida sessões como objeto analítico (`MemoriaLongitudinal`), não como memória vivida |
| [Recorrencias.md](Recorrencias.md) | `Recorrencia` é, por definição, estrutura produzida pelo Motor Freud |
| [Formacoes-Freudianas.md](Formacoes-Freudianas.md) | Classificação estrutural (`TipoFormacaoFreudiana`) — a Regra 11 reserva a fundamentação teórica ao Analista |
| [Representacoes-Lacanianas.md](Representacoes-Lacanianas.md) | Escrita lacaniana — vedada ao Sujeito por princípio permanente ([Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos), "a escrita lacaniana pertence ao analista") |
| [Circuitos.md](Circuitos.md) | Topologia de retorno através de sessões — estrutura, não vivência |
| [Grafos.md](Grafos.md) | Visualização topológica das relações — listada explicitamente como nunca visualizada em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) |
| [Indicadores.md](Indicadores.md) | Contagens e consolidações são leitura analítica sobre o discurso, não o discurso em si |

## O que o Sujeito nunca visualiza

Lista fechada, idêntica à de [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#o-que-o-sujeito-nunca-visualiza) e derivada diretamente da tabela acima:

- motores (Discourse Engine, Motor Freud, Motor Lacan) ou qualquer rótulo de componente interno;
- recorrências, sua frequência ou qualquer contagem apresentada como tal;
- classificações (`TipoFormacaoFreudiana`, rótulo lacaniano);
- grafos, circuitos ou qualquer visualização topológica;
- cadeias significantes ou qualquer estrutura lacaniana;
- indicadores derivados de observação computacional;
- observações produzidas pelo sistema (`Observacao`) ou qualquer texto derivado delas.

## O único ponto de contato indireto

O modo de enunciação socrático (Documento-Mestre.md §6.7, ECO — [ECO/README.md](../ECO/README.md)) usa o Motor Freud e o Motor Lacan apenas para decidir *onde dirigir a atenção flutuante* — nunca para compor conteúdo entregue ao Sujeito. A pergunta devolvida nunca cita recorrência, rótulo ou estrutura; é sempre aberta ("Você voltou a falar em '%s'. O que vem à mente sobre isso?" — [RespostaEcoRecorrenciaService](../../app/Infrastructure/AI/RespostaEcoRecorrenciaService.php)). Esse fluxo pertence à ECO, não a esta camada de Representação Computacional: nenhuma das oito representações documentadas aqui é, ela própria, entregue ao Sujeito — apenas informa, de fora, a pergunta que a ECO formula.

## Garantia técnica já existente

`RespostaSocraticaService`/`GeradorDePerguntaSocraticaLLM` (Sprint 23) constroem o prompt do Sujeito apenas a partir dos turnos recentes da própria conversa — nunca de `Recorrencia`, `Observacao` ou rótulo lacaniano. As rotas que expõem qualquer representação desta pasta (`/sujeitos/{id}/historico`, `/sujeitos/{id}/observacoes`, `/sujeitos/{id}/observacoes/grafo-circuito`) estão protegidas por `PortaoDeAnalista::proteger()` desde a Sprint 18 e nunca são alcançadas pelas rotas `/conversa*` — mesma garantia já registrada em [ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md#garantia-técnica-já-existente).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Principios.md](Principios.md)
- [../ECO/Interface-Sujeito.md](../ECO/Interface-Sujeito.md)
- [../ECO/Interface-Analista.md](../ECO/Interface-Analista.md)
- [../Documento-Mestre.md](../Documento-Mestre.md#5-princípios-éticos)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)
- [../Regras-Dominio.md](../Regras-Dominio.md)
