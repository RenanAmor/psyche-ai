# Transferência — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/transferencia.md](../../Biblioteca-Teorica/Conceitos/transferencia.md).

## Fenômeno observado

`EventoDiscursivo` agrupados dentro de uma Sessão — uma relação situada e contínua, não mensagens isoladas.

## Evidências observáveis

- continuidade discursiva entre turnos de uma mesma Sessão;
- retomada de assunto de turnos anteriores;
- mudanças de posição subjetiva ao longo da relação registrada.

## Dados necessários

`Sessao` (agrupa `EventoDiscursivo` dentro de uma relação situada).

## Dados opcionais

`ContextoConversaDTO` (turnos recentes) — usado pelo Motor de Enunciação Socrática para dar continuidade à conversa, não para modelar transferência.

## Eventos relacionados

Nenhum registrado nesta versão.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

A transferência, por definição teórica, só existe na relação analítica real, nunca de forma autônoma no sistema. O sistema nunca nomeia ou interpreta o vínculo do Sujeito com o sistema ou com o analista ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

## Observação automática

Não.

## Organização automática

Não.

## Classificação automática

Não.

## Confirmação do sujeito

Sim.

## Validação do analista

Sim.

## Evidências produzidas

Nenhuma nomeada como "transferência".

## Componentes envolvidos

- **Motor Freud**: nenhum — a noção de relação situada que ela fundamenta já justifica `Sessao` como unidade de agrupamento.
- **Motor Lacan**: nenhum.
- **Memória Discursiva**: `Sessao` como unidade de agrupamento.
- **Interface do Sujeito**: continuidade da conversa via `ContextoConversaDTO`.
- **Interface do Analista**: nenhuma.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: ECO — Estrutura Computacional de Observação (`GeradorDePerguntaSocraticaLLM`).

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/transferencia.md](../../Biblioteca-Teorica/Conceitos/transferencia.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
