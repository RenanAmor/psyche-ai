# Sonhos — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/sonhos.md](../../Biblioteca-Teorica/Conceitos/sonhos.md).

## Fenômeno observado

Um `EventoDiscursivo` cujo conteúdo é um relato de sonho, registrado como qualquer outro material discursivo.

## Evidências observáveis

- sonhos relatados pelo próprio Sujeito;
- marcadores textuais de relato onírico ("sonhei que...");
- narrativa apresentada como sonho pelo Sujeito.

## Dados necessários

`EventoDiscursivo.conteudo` (relato do sonho, tratado como qualquer outro material discursivo).

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Mensagem do Sujeito classificada pelo Motor Freud.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema nunca interpreta o sonho relatado — apenas registra o relato como material discursivo, sem trabalho de decifração ([Ontologia-Freud.md §3.8](../../Ontologia-Freud.md)).

## Observação automática

Sim (reconhecimento de forma, não interpretação de conteúdo onírico).

## Organização automática

Não.

## Classificação automática

Sim.

## Confirmação do sujeito

Não diretamente.

## Validação do analista

Sim.

## Evidências produzidas

`TipoFormacaoFreudiana::Sonho`.

## Componentes envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM`, `TipoFormacaoFreudiana`.
- **Motor Lacan**: rótulo correspondente via `ReclassificadorLacaniano`, quando aplicável.
- **Memória Discursiva**: `EventoDiscursivo`.
- **Interface do Sujeito**: nenhuma — a classificação roda em paralelo, nunca compõe a resposta socrática (Regra 11).
- **Interface do Analista**: `ObservacoesSujeitoController`.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/sonhos.md](../../Biblioteca-Teorica/Conceitos/sonhos.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
