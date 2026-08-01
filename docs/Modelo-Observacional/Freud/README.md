# Modelo Observacional — Freud

> Síntese dos Modelos Observacionais fundamentados na obra de Freud. Cada conceito tem seu documento completo em [../Conceitos/](../Conceitos/); esta página apenas agrupa e contextualiza o que já está lá — não introduz fenômeno, evidência ou limite novo.

## Escopo

Os dez conceitos canônicos de [Ontologia-Freud.md §3](../../Ontologia-Freud.md#3-conceitos-fundamentais): [Inconsciente](../Conceitos/inconsciente.md), [Recalque](../Conceitos/recalque.md), [Pulsão](../Conceitos/pulsao.md), [Desejo (Freud)](../Conceitos/desejo-freud.md), [Formação de compromisso](../Conceitos/formacao-de-compromisso.md), [Ato falho](../Conceitos/ato-falho.md), [Chiste](../Conceitos/chiste.md), [Sonhos](../Conceitos/sonhos.md), [Repetição](../Conceitos/repeticao.md) e [Transferência](../Conceitos/transferencia.md).

## O que o Motor Freud efetivamente observa hoje

O Motor Freud ([Documento-Mestre.md §7](../../Documento-Mestre.md#7-arquitetura-conceitual)) aplica "atenção flutuante" sobre o que o Discourse Engine expõe, trazendo apenas o que se repete, sem hipótese. Nesta data, isso se traduz em dois fenômenos efetivamente observáveis, nunca três — o terceiro nível (interpretação) está fora do escopo por princípio, não por limitação técnica temporária:

1. **Recorrência de conteúdo normalizado** ([Repetição](../Conceitos/repeticao.md)) — dois ou mais `EventoDiscursivo` com o mesmo conteúdo, detectados por `DetectorRecorrencias`. É o fenômeno mais diretamente implementado de toda a Biblioteca Teórica.
2. **Classificação estrutural de formações discursivas** — quatro rótulos fechados via `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana`: [Ato falho](../Conceitos/ato-falho.md), [Chiste](../Conceitos/chiste.md), [Sonho](../Conceitos/sonhos.md) e [Formação de compromisso](../Conceitos/formacao-de-compromisso.md) (categoria geral da qual as três primeiras são espécies).

## O que é fundamentação teórica de fundo, sem observação própria

[Inconsciente](../Conceitos/inconsciente.md), [Recalque](../Conceitos/recalque.md), [Pulsão](../Conceitos/pulsao.md), [Desejo (Freud)](../Conceitos/desejo-freud.md) e [Transferência](../Conceitos/transferencia.md) justificam teoricamente por que o sistema trata recorrência, lacuna e continuidade relacional como estruturalmente relevantes — mas nenhum deles é, ele mesmo, observado, organizado ou classificado pelo sistema. Nenhum aparece nomeado em qualquer saída do sistema, para o Sujeito ou para o Analista.

## Limite comum a todos os dez

Nenhum dos dez conceitos autoriza o sistema a afirmar significado, intenção, desejo, diagnóstico ou hipótese clínica — mesmo os quatro efetivamente classificados (Ato falho, Chiste, Sonho, Formação de compromisso) têm apenas sua *forma* reconhecida, nunca sua causa ou conteúdo recalcado, conforme [Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites) e Regra 7 ([Regras-Dominio.md](../../Regras-Dominio.md)). Toda leitura de causa permanece do analista ou do próprio sujeito (Regra 10).

## Referências cruzadas do projeto

- [../README.md](../README.md)
- [../Lacan/README.md](../Lacan/README.md)
- [Biblioteca-Teorica/Freud/](../../Biblioteca-Teorica/Freud/)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Regras-Dominio.md](../../Regras-Dominio.md)
