# Recalque — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/recalque.md](../../Biblioteca-Teorica/Conceitos/recalque.md).

## Fenômeno observado

Uma lacuna, substituição ou autocorreção aparente dentro de um `EventoDiscursivo`, preservada tal como produzida, sem correção editorial.

## Evidências observáveis

- lacunas no discurso registrado;
- substituições dentro do mesmo turno;
- correções relatadas pelo próprio Sujeito.

## Dados necessários

`EventoDiscursivo.conteudo` (preservado tal como produzido).

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Registro de um `EventoDiscursivo` com lacuna, substituição ou autocorreção aparente — apenas preservado, nunca classificado como recalque.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema nunca infere o que estaria sendo recalcado — preserva a lacuna tal como ela aparece no registro, sem tentar completá-la ou nomeá-la ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

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

Nenhuma — nenhum componente classifica conteúdo como "recalcado".

## Componentes envolvidos

- **Motor Freud**: nenhum implementado.
- **Motor Lacan**: nenhum.
- **Memória Discursiva**: `RegistrarEventoDiscursivoHandler` preserva o conteúdo tal como produzido, compatível com o conceito mas não decorrente dele.
- **Interface do Sujeito**: nenhuma — a IA nunca corrige, completa ou "destrava" o que o Sujeito não conseguiu dizer.
- **Interface do Analista**: nenhuma.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/recalque.md](../../Biblioteca-Teorica/Conceitos/recalque.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
