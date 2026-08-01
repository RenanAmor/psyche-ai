# Matriz Conceito × Evidência

> Consolida o campo "Evidências produzidas" de cada documento de [Modelo-Observacional/Conceitos/](../../Modelo-Observacional/Conceitos/) — fonte primária desta matriz, já auditada contra o código real na Sprint 26. Nenhuma evidência nova foi atribuída a nenhum conceito nesta Sprint.

| Conceito | Evidências efetivamente produzidas pelo sistema |
|---|---|
| [Inconsciente](../Conceitos/inconsciente.md) | Nenhuma. |
| [Recalque](../Conceitos/recalque.md) | Nenhuma — nenhum componente classifica conteúdo como "recalcado". |
| [Pulsão](../Conceitos/pulsao.md) | `Recorrencia` (contagem de ocorrências de conteúdo normalizado) — nunca rotulada como "pulsão" na saída do sistema. |
| [Desejo (Freud)](../Conceitos/desejo-freud.md) | Nenhuma nomeada como "desejo" — apenas a Linha do Tempo e o circuito de recorrências, em vocabulário técnico, nunca psicanalítico. |
| [Formação de compromisso](../Conceitos/formacao-de-compromisso.md) | `TipoFormacaoFreudiana::FormacaoDeCompromisso` (ou `NaoClassificado` quando o guardrail rejeita a resposta do LLM). |
| [Ato falho](../Conceitos/ato-falho.md) | `TipoFormacaoFreudiana::AtoFalho`, exibido como rótulo estrutural ao lado do `EventoDiscursivo` classificado. |
| [Chiste](../Conceitos/chiste.md) | `TipoFormacaoFreudiana::Chiste` + rótulo lacaniano correspondente quando `comLeituraLacaniana=true`. |
| [Sonhos](../Conceitos/sonhos.md) | `TipoFormacaoFreudiana::Sonho`. |
| [Repetição](../Conceitos/repeticao.md) | `Recorrencia` (conteúdo normalizado + contagem); `Observacao`; `CircuitoRecorrenciaDTO` (trajeto cronológico entre Sessões). |
| [Transferência](../Conceitos/transferencia.md) | Nenhuma nomeada como "transferência". |
| [Significante](../Conceitos/significante.md) | Nenhuma. |
| [Cadeia significante](../Conceitos/cadeia-significante.md) | Nenhuma. |
| [Metáfora](../Conceitos/metafora.md) | Nenhuma. |
| [Metonímia](../Conceitos/metonimia.md) | Rótulo "Estrutura candidata: deslize metonímico." ou variante de circuito ("o tema retorna ao mesmo ponto através de sessões distintas"). |
| [Registro Simbólico](../Conceitos/registro-simbolico.md) | Nenhuma. |
| [Registro Imaginário](../Conceitos/registro-imaginario.md) | Nenhuma. |
| [Registro Real](../Conceitos/registro-real.md) | Nenhuma. |
| [Outro](../Conceitos/outro.md) | Nenhuma. |
| [Objeto a](../Conceitos/objeto-a.md) | Nenhuma. |
| [Falta](../Conceitos/falta.md) | Nenhuma. |
| [Desejo lacaniano](../Conceitos/desejo-lacaniano.md) | Nenhuma. |

## Leitura da matriz

- **6 dos 21 conceitos** produzem evidência computacional efetiva hoje: Pulsão (indireta, sem nomear), Formação de compromisso, Ato falho, Chiste, Sonhos e Repetição — todos do polo freudiano, mais a reclassificação de Metonímia sobre a evidência de Repetição.
- **15 dos 21 conceitos** não produzem nenhuma evidência computacional nomeada — consistente com a coluna "Motores envolvidos" vazia desses mesmos conceitos em [Motor-x-Conceito.md](Motor-x-Conceito.md).
- Em nenhum caso o vocabulário psicanalítico ("desejo", "recalcado", "pulsão") aparece na saída do sistema — apenas nomes técnicos (`Recorrencia`, `Observacao`, `TipoFormacaoFreudiana`) ou rótulos de "estrutura candidata", nunca afirmação de estatuto teórico confirmado.

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Conceitos/](../Conceitos/)
- [Modelo-Observacional/Conceitos/](../../Modelo-Observacional/Conceitos/)
- [Motor-x-Conceito.md](Motor-x-Conceito.md)
