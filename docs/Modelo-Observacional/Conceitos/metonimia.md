# Metonímia — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/metonimia.md](../../Biblioteca-Teorica/Conceitos/metonimia.md). Único conceito lacaniano com reclassificação efetivamente produzida pelo sistema nesta versão — reclassifica a mesma observação de [Repetição](repeticao.md), nunca observa por conta própria.

## Fenômeno observado

Uma `Recorrencia` já detectada pelo Motor Freud (mesmo conteúdo normalizado, duas ou mais ocorrências) é reapresentada com vocabulário lacaniano.

## Evidências observáveis

- recorrência de conteúdo normalizado (a mesma evidência observável de Repetição), reapresentada em vocabulário lacaniano;
- presença de um mesmo conteúdo normalizado em duas ou mais Sessões distintas (circuito).

## Dados necessários

`Recorrencia[]` já produzida pelo `DetectorRecorrencias`.

## Dados opcionais

`OcorrenciaRecorrencia[]` (circuito) — quando presente em ≥2 Sessões distintas, o rótulo passa a "circuito" em vez de "deslize metonímico".

## Eventos relacionados

Recorrência já detectada pelo Motor Freud (≥2 ocorrências de conteúdo normalizado).

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema nunca afirma o estatuto de significante confirmado — permanece sempre "estrutura candidata" ([Ontologia-Lacan.md §5](../../Ontologia-Lacan.md#5-limites)).

## Observação automática

Não — reclassifica dado já observado pelo Motor Freud, não observa por conta própria.

## Organização automática

Sim — a reclassificação é, em si, uma forma de organização/rotulagem sobre dado já existente.

## Classificação automática

Sim — sempre como "Estrutura candidata: deslize metonímico" (ou variante de circuito), nunca afirmando estatuto de significante confirmado.

## Confirmação do sujeito

Não diretamente — só nas telas do analista.

## Validação do analista

Sim.

## Evidências produzidas

Rótulo "Estrutura candidata: deslize metonímico." ou "...circuito — o tema retorna ao mesmo ponto através de sessões distintas."

## Componentes envolvidos

- **Motor Freud**: `DetectorRecorrencias` (fornece o dado de base).
- **Motor Lacan**: `ReclassificadorLacaniano::reclassificar()` / `reclassificarComTrajeto()`.
- **Memória Discursiva**: `Recorrencia`, `OcorrenciaRecorrencia`.
- **Interface do Sujeito**: nenhuma — reclassificação exclusiva das telas do analista (Regra 11).
- **Interface do Analista**: `ObservacoesSujeitoController`, coluna "Leitura Lacaniana".
- **Timeline**: nenhum.
- **Circuito Pulsional**: grafo do circuito, quando aplicável.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/metonimia.md](../../Biblioteca-Teorica/Conceitos/metonimia.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Repetição](repeticao.md)
