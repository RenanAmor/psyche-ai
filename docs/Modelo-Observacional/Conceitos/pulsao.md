# Pulsão — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/pulsao.md](../../Biblioteca-Teorica/Conceitos/pulsao.md).

## Fenômeno observado

Conteúdo discursivo normalizado que se repete de forma insistente ao longo de múltiplas Sessões do mesmo Sujeito.

## Evidências observáveis

- repetições de conteúdo normalizado entre sessões;
- retorno de um mesmo tema por vias distintas;
- trajeto de um tema entre sessões (circuito).

## Dados necessários

`EventoDiscursivo.conteudo` normalizado através de múltiplas Sessões, para observar insistência ao longo do tempo.

## Dados opcionais

`OcorrenciaRecorrencia` (circuito) — mostra o trajeto, não a pulsão em si.

## Eventos relacionados

Registro de múltiplos `EventoDiscursivo` com conteúdo normalizado equivalente ao longo de Sessões distintas.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O que é observado é recorrência de conteúdo — um efeito possível, entre outros — nunca a pulsão como tal. Como conceito-limite entre o somático e o psíquico, a pulsão não tem correlato observável direto em texto ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

## Observação automática

Não — o que é observado é a recorrência de conteúdo (`DetectorRecorrencias`), nunca nomeado como "pulsão" na saída do sistema.

## Organização automática

Não.

## Classificação automática

Não.

## Confirmação do sujeito

Sim.

## Validação do analista

Sim.

## Evidências produzidas

`Recorrencia` (contagem de ocorrências de conteúdo normalizado) — nunca rotulada como "pulsão" na saída do sistema.

## Componentes envolvidos

- **Motor Freud**: `DetectorRecorrencias` (fundamentação teórica de fundo, sem nomear o conceito na saída).
- **Motor Lacan**: nenhum.
- **Memória Discursiva**: `EventoDiscursivo`, `Recorrencia`.
- **Interface do Sujeito**: nenhuma.
- **Interface do Analista**: nenhuma.
- **Timeline**: nenhum.
- **Circuito Pulsional**: `OcorrenciaRecorrencia`, quando presente em ≥2 Sessões — nome do componente inspirado neste conceito, mas sem operacionalizar a teoria pulsional em si (mesma ressalva de [Modelo-Relacional/Conceitos/pulsao.md](../../Modelo-Relacional/Conceitos/pulsao.md); o que o componente de fato implementa é o circuito/trajeto de uma Recorrência).
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/pulsao.md](../../Biblioteca-Teorica/Conceitos/pulsao.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
