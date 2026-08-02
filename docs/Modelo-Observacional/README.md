# Modelo Observacional — Catálogo por Conceito

> Camada obrigatória da [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) entre a [Biblioteca Teórica](../Biblioteca-Teorica/README.md) e a Representação Computacional (seção obrigatória de todo documento de Conceito em [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/)). Complementa, sem substituir, [Modelo-Observacional.md](../Modelo-Observacional.md) — que trata dos princípios gerais da observação (objetivo, Neutralidade Observacional, Status do Caso) — detalhando, conceito a conceito, o que exatamente pode ser observado, o que nunca poderá ser observado, quais evidências computacionais podem ser produzidas e quais limites da observação computacional devem ser respeitados.

## O que este catálogo é

Uma tradução disciplinada de cada um dos 21 conceitos canônicos da Biblioteca Teórica ([Ontologia-Freud.md §3](../Ontologia-Freud.md#3-conceitos-fundamentais) + [Ontologia-Lacan.md §3](../Ontologia-Lacan.md#3-conceitos-fundamentais)) em fenômeno observável computacionalmente — nunca uma reinterpretação da teoria, e nunca uma antecipação de implementação futura. Nenhum motor novo do PsycheAI pode ser desenvolvido sem que o(s) conceito(s) que ele operacionaliza tenha(m) seu Modelo Observacional documentado aqui primeiro, tal como a Biblioteca Teórica já exige a Aplicação Computacional (decisão de arquitetura desta Sprint, vinculante para sprints futuras).

## Por que esta camada existe separada da Biblioteca Teórica

A Biblioteca Teórica cataloga fundamentação científica e, no nível Conceito, já registra "Aplicação Computacional" e "Representação Computacional" — mas responde a "o que este conceito justifica computacionalmente". O Modelo Observacional responde a uma pergunta anterior e mais estrita: "o que, deste conceito, é *fenômeno observável* no discurso registrado — e o que, por definição teórica, nunca poderá ser". Ele existe para impedir que a distância entre teoria psicanalítica e implementação computacional seja percorrida sem que, em cada passo, fique registrado o que é dado bruto observável e o que é interpretação — que permanece, sempre, do sujeito ou do analista.

## Estrutura

| Pasta | Conteúdo |
|---|---|
| [Conceitos/](Conceitos/) | Os 21 documentos de Modelo Observacional, um por conceito canônico — mesmo escopo 1:1 de [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/) |
| [Freud/](Freud/README.md) | Síntese dos fenômenos observáveis fundamentados pela obra de Freud, por Motor Freud |
| [Lacan/](Lacan/README.md) | Síntese dos fenômenos observáveis fundamentados pela releitura de Lacan, por Motor Lacan |

## Modelo único de documento

Todo documento de `Conceitos/` segue a mesma estrutura fixa, obrigatória e sem campos ad-hoc:

1. **Fenômeno observado** — descreve apenas o fenômeno, nunca interpreta.
2. **Evidências observáveis** — quais dados podem indicar sua ocorrência (repetições, mudanças discursivas, recorrências, interrupções, atos falhos relatados, sonhos relatados, lapsos relatados, mudanças de posição subjetiva), sempre como evidência, nunca como diagnóstico.
3. **Dados necessários**
4. **Dados opcionais**
5. **Eventos relacionados**
6. **Limites da observação** — o sistema nunca pode afirmar significado, intenção, desejo, significante, diagnóstico ou hipótese clínica.
7. **Observação automática** (Sim/Não)
8. **Organização automática** (Sim/Não)
9. **Classificação automática** (Sim/Não)
10. **Confirmação do sujeito** (Sim/Não)
11. **Validação do analista** (Sim/Não)
12. **Evidências produzidas** — o que o sistema efetivamente devolve.
13. **Componentes envolvidos** — Motor Freud, Motor Lacan, Memória Discursiva, Interface do Sujeito, Interface do Analista, Timeline, Circuito Pulsional, demais motores.

## Panorama desta Sprint

Auditado contra o código real desta data, na mesma base já estabelecida pela Biblioteca Teórica:

- **Observado, organizado e classificado automaticamente hoje**: [Repetição](Conceitos/repeticao.md) (o mais implementado — `DetectorRecorrencias`, circuito, grafo D3) e as quatro espécies de Formação de compromisso — [Ato falho](Conceitos/ato-falho.md), [Chiste](Conceitos/chiste.md), [Formação de compromisso](Conceitos/formacao-de-compromisso.md), [Sonhos](Conceitos/sonhos.md) — classificadas via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`.
- **Reclassificado (não observado por conta própria)**: [Metonímia](Conceitos/metonimia.md), sobre a mesma observação de Repetição, e [Metáfora](Conceitos/metafora.md), por ponte com a classificação freudiana (Chiste/Sonho) — os dois únicos rótulos lacanianos efetivamente produzidos hoje (corrigido na Sprint 30: a afirmação anterior de que Metáfora nunca era produzida estava desatualizada em relação a `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()`, em produção desde a revisão pós-Sprint 17). A observação *direta* do fenômeno que fundamentaria uma metáfora (substituição entre dois conteúdos distintos) continua fora do alcance do detector atual, que só reconhece repetição do mesmo conteúdo normalizado.
- **Fundamentação teórica de fundo, sem observação própria**: [Inconsciente](Conceitos/inconsciente.md), [Recalque](Conceitos/recalque.md), [Pulsão](Conceitos/pulsao.md), [Desejo (Freud)](Conceitos/desejo-freud.md), [Transferência](Conceitos/transferencia.md).
- **Sem nenhuma representação computacional definida nesta versão**: os onze conceitos exclusivamente lacanianos restantes — [Significante](Conceitos/significante.md), [Cadeia significante](Conceitos/cadeia-significante.md), [Desejo lacaniano](Conceitos/desejo-lacaniano.md), [Falta](Conceitos/falta.md), [Objeto a](Conceitos/objeto-a.md), [Outro](Conceitos/outro.md) e os três Registros — [Simbólico](Conceitos/registro-simbolico.md), [Imaginário](Conceitos/registro-imaginario.md), [Real](Conceitos/registro-real.md).

Nenhum componente foi inventado para este panorama — cada afirmação decorre diretamente da Aplicação Computacional já auditada em [Biblioteca-Teorica/Conceitos/](../Biblioteca-Teorica/Conceitos/).

## Restrições desta Sprint

Nenhuma interpretação foi escrita. Nenhum motor foi implementado. Nenhum código, API, banco de dados ou teste foi alterado. Esta Sprint é exclusivamente documental — Sprint científica de tradução de conceito em fenômeno observável, não uma sprint de engenharia.

## Referências cruzadas do projeto

- [Modelo-Observacional.md](../Modelo-Observacional.md)
- [Biblioteca-Teorica/README.md](../Biblioteca-Teorica/README.md)
- [Documento-Mestre.md](../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [Ontologia-Freud.md](../Ontologia-Freud.md)
- [Ontologia-Lacan.md](../Ontologia-Lacan.md)
- [Modelo-Computacional-Discurso.md](../Modelo-Computacional-Discurso.md)
- [Regras-Dominio.md](../Regras-Dominio.md)
- [Roadmap.md](../Roadmap.md)
