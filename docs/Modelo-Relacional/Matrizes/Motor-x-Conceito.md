# Matriz Motor × Conceito

> Consolida a seção "Motores envolvidos" dos 21 documentos de [../Conceitos/](../Conceitos/), na mesma base de auditoria contra o código real já usada em [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md) e em cada campo "Motores impactados" de [Biblioteca-Teorica/Conceitos/](../../Biblioteca-Teorica/Conceitos/). "—" significa nenhum envolvimento registrado nesta versão; texto entre parênteses qualifica o tipo de envolvimento quando não é observação direta.

| Conceito | Motor Freud | Motor Lacan | Memória Discursiva | Timeline | Circuito Pulsional | Interface do Analista | Interface do Sujeito |
|---|---|---|---|---|---|---|---|
| [Inconsciente](../Conceitos/inconsciente.md) | — | — | — | — | — | — | — |
| [Recalque](../Conceitos/recalque.md) | — | — | — | — | — | — | — |
| [Pulsão](../Conceitos/pulsao.md) | (fundo p/ `DetectorRecorrencias`) | — | — | — | (nome do componente, sem operacionalização) | — | — |
| [Desejo (Freud)](../Conceitos/desejo-freud.md) | (fundo p/ temporalidade) | — | — | — | — | — | — |
| [Formação de compromisso](../Conceitos/formacao-de-compromisso.md) | `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana` | reclassifica espécies | registra evento classificado | exibe ocorrências | — | exibe classificação | — |
| [Ato falho](../Conceitos/ato-falho.md) | `ClassificadorFreudianoLLM` | — | registra evento classificado | exibe ocorrências | — | exibe classificação | — |
| [Chiste](../Conceitos/chiste.md) | `ClassificadorFreudianoLLM` | reclassifica (Metonímia) | registra evento classificado | exibe ocorrências | — | exibe classificação e reclassificação | — |
| [Sonhos](../Conceitos/sonhos.md) | `ClassificadorFreudianoLLM` | (fundamenta Metáfora/Metonímia, não produz) | registra evento classificado | exibe ocorrências | — | exibe classificação | — |
| [Repetição](../Conceitos/repeticao.md) | `DetectorRecorrencias` | reclassifica (Metonímia) | registra recorrências | exibe recorrências | grafo D3 do circuito/trajeto | exibe recorrências, circuito e reclassificação | — |
| [Transferência](../Conceitos/transferencia.md) | — | — | — | — | — | — | — |
| [Significante](../Conceitos/significante.md) | — | (questão de pesquisa aberta) | — | — | — | — | — |
| [Cadeia significante](../Conceitos/cadeia-significante.md) | — | — | organiza sequências de eventos (sem nomear o conceito) | — | — | — | — |
| [Metáfora](../Conceitos/metafora.md) | fornece classificação de origem (Chiste/Sonho) | reclassifica, por ponte com o Motor Freud | — | — | — | exibe reclassificação, quando disparada | — |
| [Metonímia](../Conceitos/metonimia.md) | fornece observação-base (Repetição) | reclassifica — um dos dois rótulos lacanianos efetivamente produzidos | registra observação-base | exibe reclassificação | — | exibe reclassificação | — |
| [Registro Simbólico](../Conceitos/registro-simbolico.md) | — | — | opera, por definição, sobre material deste registro (sem nomeá-lo) | — | — | — | — |
| [Registro Imaginário](../Conceitos/registro-imaginario.md) | — | — | — | — | — | — | — |
| [Registro Real](../Conceitos/registro-real.md) | — | — | — | — | — | — | — |
| [Outro](../Conceitos/outro.md) | — | — | preserva contexto de enunciação (sem nomear o conceito) | — | — | — | — |
| [Objeto a](../Conceitos/objeto-a.md) | — | — | — | — | — | — | — |
| [Falta](../Conceitos/falta.md) | — | — | — | — | — | — | — |
| [Desejo lacaniano](../Conceitos/desejo-lacaniano.md) | — | — | — | — | — | — | — |

## Coluna adicional: ECO — Estrutura Computacional de Observação

Apenas [Repetição](../Conceitos/repeticao.md) alimenta a ECO — via `RespostaEcoRecorrenciaService`, que devolve pergunta-eco ao Sujeito a partir de recorrências detectadas, nunca como afirmação (ver [Modelo-Observacional/Conceitos/repeticao.md](../../Modelo-Observacional/Conceitos/repeticao.md)). Chamada de "Modo Socrático" até a Sprint 28, quando recebeu identidade oficial como ECO — ver [ECO/README.md](../../ECO/README.md).

## Leitura da matriz

- **Motor Freud** implementado e nomeando conceitos: apenas nas 5 espécies de Formação de compromisso e em Repetição.
- **Motor Lacan** implementado e produzindo rótulo: Metonímia (observação direta reclassificada) e Metáfora (por ponte com a classificação freudiana) — corrigido na Sprint 30, ver [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md).
- **Interface do Sujeito**: nenhum conceito é exposto diretamente — consistente com [Arquitetura-Cientifica.md §2](../../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista) e a Regra 11 de [Regras-Dominio.md](../../Regras-Dominio.md).
- Os 14 conceitos sem qualquer célula preenchida são fundamentação teórica de fundo ou limites absolutos — nenhum componente foi inventado para preencher esta matriz.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Conceitos/](../Conceitos/)
- [../README.md](../README.md)
- [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)
- [Arquitetura.md](../../Arquitetura.md)
