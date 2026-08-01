# Ato falho — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/ato-falho.md](../../Biblioteca-Teorica/Conceitos/ato-falho.md).

## Fenômeno observado

Um `EventoDiscursivo` registrado apresenta uma interrupção, substituição de palavra, autocorreção ou desvio em relação ao enunciado que parecia estar em curso — uma diferença formal entre o que o discurso registrado deixa ver como encaminhado e o que efetivamente ficou registrado.

## Evidências observáveis

- interrupções no meio de uma frase;
- autocorreções explícitas ("quis dizer...", "não, na verdade...");
- substituição de uma palavra por outra dentro do mesmo turno;
- lapsos relatados pelo próprio Sujeito.

## Dados necessários

`EventoDiscursivo.conteudo`, preservado tal como produzido, sem correção editorial.

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Registro de uma mensagem do Sujeito, submetida à classificação do Motor Freud.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

Reconhecer a forma de um ato falho não é inferir a intenção inconsciente por trás dele — essa inferência permanece, por definição teórica, fora do alcance do sistema ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

## Observação automática

Sim — classificação estrutural via LLM com guardrail de enum fechado.

## Organização automática

Não — a classificação rotula um `EventoDiscursivo` isolado, não organiza um conjunto.

## Classificação automática

Sim — `TipoFormacaoFreudiana::AtoFalho`.

## Confirmação do sujeito

Não diretamente — o Sujeito nunca vê o rótulo (Regra 11, [Regras-Dominio.md](../../Regras-Dominio.md)).

## Validação do analista

Sim — só as telas do analista exibem o rótulo.

## Evidências produzidas

`TipoFormacaoFreudiana::AtoFalho`, exibido como rótulo estrutural ao lado do `EventoDiscursivo` classificado.

## Componentes envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM`, `TipoFormacaoFreudiana`.
- **Motor Lacan**: rótulo lacaniano correspondente via `ReclassificadorLacaniano`, quando `comLeituraLacaniana=true`.
- **Memória Discursiva**: `EventoDiscursivo`.
- **Interface do Sujeito**: nenhuma — o rótulo nunca é exposto (Regra 11).
- **Interface do Analista**: `ObservacoesSujeitoController`.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/ato-falho.md](../../Biblioteca-Teorica/Conceitos/ato-falho.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
