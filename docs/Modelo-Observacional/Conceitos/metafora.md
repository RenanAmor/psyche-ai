# Metáfora — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md). Mapeada na mesma tabela de reclassificação que [Metonímia](metonimia.md), mas nunca efetivamente disparada pela implementação atual.

## Fenômeno observado

Nenhum efetivamente observado nesta versão. O fenômeno que fundamentaria este rótulo — dois conteúdos distintos em relação de substituição um pelo outro — está mapeado teoricamente na tabela de reclassificação, mas o detector atual só reconhece repetição do mesmo conteúdo normalizado, nunca substituição entre dois conteúdos distintos.

## Evidências observáveis

Nenhuma produzida na prática. A evidência que fundamentaria este rótulo exigiria reconhecer dois conteúdos distintos em relação de substituição — dado que `DetectorRecorrencias` não captura.

## Dados necessários

Dois conteúdos distintos em relação de substituição um pelo outro — dado que `DetectorRecorrencias` não captura hoje.

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Nenhum evento dispara este rótulo na implementação atual.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

Exigiria detectar substituição entre dois conteúdos distintos, não apenas repetição do mesmo conteúdo — mudança de escopo do detector ainda não decidida com o usuário. Mesmo se implementado, o sistema nunca afirmaria estatuto de significante confirmado ([Ontologia-Lacan.md §5](../../Ontologia-Lacan.md#5-limites)).

## Observação automática

Não — mapeado teoricamente, mas nunca efetivamente produzido pela implementação atual.

## Organização automática

Não.

## Classificação automática

Não, na prática atual (previsto na tabela de reclassificação, sem caminho de código que o produza).

## Confirmação do sujeito

Sim.

## Validação do analista

Sim.

## Evidências produzidas

Nenhuma.

## Componentes envolvidos

- **Motor Freud**: nenhum.
- **Motor Lacan**: `ReclassificadorLacaniano` — a tabela de lookup prevê o rótulo, mas nunca é retornada na prática.
- **Memória Discursiva**: nenhum.
- **Interface do Sujeito**: nenhuma.
- **Interface do Analista**: nenhuma — o rótulo existe na tabela, mas nenhuma tela chega a exibi-lo porque o detector atual nunca o produz.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/metafora.md](../../Biblioteca-Teorica/Conceitos/metafora.md)
- [Ontologia-Lacan.md](../../Ontologia-Lacan.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Metonímia](metonimia.md)
