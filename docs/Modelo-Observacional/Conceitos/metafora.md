# Metáfora — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md). Corrigido na Sprint 30: produzida por reclassificação, via ponte com o Motor Freud (Chiste/Sonho) — nunca por observação direta de substituição entre dois conteúdos distintos.

## Fenômeno observado

Não observado diretamente nesta versão — o fenômeno que fundamentaria a observação direta de uma metáfora (dois conteúdos distintos em relação de substituição um pelo outro) não é captado por `DetectorRecorrencias`, que só reconhece repetição do mesmo conteúdo normalizado. O rótulo de metáfora É produzido, mas indiretamente: sempre que uma Recorrência sem circuito (confinada a uma única Sessão) tem seu conteúdo classificado como `TipoFormacaoFreudiana::Chiste` ou `::Sonho` pelo Motor Freud, `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` devolve o rótulo de metáfora.

## Evidências observáveis

A classificação freudiana prévia do conteúdo (Chiste ou Sonho) — nunca evidência própria de substituição entre dois significantes distintos.

## Dados necessários

Um `EventoDiscursivo` cujo conteúdo o Motor Freud classifica como Chiste ou Sonho, dentro de uma Recorrência sem circuito.

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Consulta com `vocabulario=lacan` via `ObservacaoApplicationService::consultarCircuito()`, quando a Recorrência subjacente já foi classificada pelo Motor Freud.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

A observação direta (detectar substituição entre dois conteúdos distintos, não apenas repetição do mesmo conteúdo) permanece fora de escopo — mudança de escopo do detector ainda não decidida com o usuário. Mesmo o rótulo indireto hoje produzido nunca afirma estatuto de significante confirmado ([Ontologia-Lacan.md §5](../../Ontologia-Lacan.md#5-limites)) — é sempre "estrutura candidata".

## Observação automática

Não diretamente; Sim indiretamente, por reclassificação de uma classificação freudiana já produzida.

## Organização automática

Não.

## Classificação automática

Sim, indiretamente — via ponte com `TipoFormacaoFreudiana::Chiste`/`::Sonho`, sempre como estrutura candidata.

## Confirmação do sujeito

Sim.

## Validação do analista

Sim.

## Evidências produzidas

O rótulo "Estrutura candidata: metáfora — condensação", com fundamentação teórica, exclusivo da interface do Analista.

## Componentes envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM` — fornece a classificação de origem (Chiste/Sonho).
- **Motor Lacan**: `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — produz o rótulo de metáfora por reclassificação.
- **Memória Discursiva**: nenhum.
- **Interface do Sujeito**: nenhuma.
- **Interface do Analista**: exibe o rótulo e sua fundamentação, quando disparado (`ObservacaoApplicationService::consultarCircuito()`).
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum registrado — o rótulo de metáfora é produzido justamente quando NÃO há circuito (Recorrência confinada a uma única Sessão); ver [Circuitos.md](../../Representacao-Computacional/Circuitos.md).
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Metonímia](metonimia.md)
