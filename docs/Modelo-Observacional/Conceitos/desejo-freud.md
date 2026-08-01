# Desejo (Freud) — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/desejo-freud.md](../../Biblioteca-Teorica/Conceitos/desejo-freud.md). Distinto do [Desejo lacaniano](desejo-lacaniano.md) — não fundidos neste Modelo Observacional.

## Fenômeno observado

Nenhum diretamente. O que é observável não é o desejo, mas a temporalidade e as associações entre conteúdos discursivos registrados ao longo de múltiplos `EventoDiscursivo`/Sessões de um mesmo Sujeito.

## Evidências observáveis

- recorrência de temas ao longo de sessões;
- relatos de sonho registrados;
- sequência temporal de associações que retornam a um mesmo ponto.

## Dados necessários

`EventoDiscursivo.criadoEm`; `Sessao.data` (temporalidade).

## Dados opcionais

Relato de sonho registrado como `EventoDiscursivo`, sem classificação automática de "desejo realizado".

## Eventos relacionados

Nenhum evento dedicado — a temporalidade que este conceito fundamenta é usada por `LinhaDoTempoApplicationService` e por `DetectorRecorrencias::detectarCircuito()`.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema nunca afirma o que o Sujeito deseja, nem que uma recorrência "realiza" um desejo — apenas organiza a temporalidade em que ela ocorre ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

## Observação automática

Não — o desejo em si não é observável; apenas a temporalidade/recorrência que ele fundamenta teoricamente.

## Organização automática

Não — a organização cronológica é função técnica de `LinhaDoTempoApplicationService`, não "organização do desejo".

## Classificação automática

Não.

## Confirmação do sujeito

Sim.

## Validação do analista

Sim.

## Evidências produzidas

Nenhuma nomeada como "desejo" — apenas a Linha do Tempo e o circuito de recorrências, nomeados nesses termos técnicos, nunca em vocabulário psicanalítico.

## Componentes envolvidos

- **Motor Freud**: fundamentação teórica de fundo para a temporalidade usada por `DetectorRecorrencias`.
- **Motor Lacan**: nenhum.
- **Memória Discursiva**: `EventoDiscursivo`, `Sessao`.
- **Interface do Sujeito**: nenhuma.
- **Interface do Analista**: nenhuma.
- **Timeline**: `LinhaDoTempoApplicationService`.
- **Circuito Pulsional**: `DetectorRecorrencias::detectarCircuito()`.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/desejo-freud.md](../../Biblioteca-Teorica/Conceitos/desejo-freud.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
