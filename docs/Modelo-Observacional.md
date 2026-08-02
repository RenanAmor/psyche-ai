# Modelo Observacional — Psyche AI

> Versão 0.4 — criado na Sprint 25 (Biblioteca Teórica), estendido na Sprint 26 (Modelo Observacional), na Sprint 27 (§6, apontando para o Modelo Relacional) e na Sprint 29 (§7, apontando para a Representação Computacional). Define o que, do discurso registrado, pode em princípio ser observado pelo PsycheAI, e o que conta como sucesso científico dessa observação — distinto de, e independente de, sucesso terapêutico. Complementa [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md), que define a estrutura de dados do discurso registrado (Evento Discursivo, Sessão, temporalidade); este documento trata do objetivo e do critério de qualidade da observação, não da sua estrutura de dados.
>
> Este documento registra os **princípios gerais** da observação. Para o detalhamento conceito a conceito — fenômeno observado, evidências observáveis, dados necessários/opcionais, limites e automação, um documento por cada um dos 21 conceitos canônicos da Biblioteca Teórica — ver o catálogo em [Modelo-Observacional/](Modelo-Observacional/README.md), novo nesta Sprint 26.

## 1. Objetivo da observação

O objetivo do PsycheAI é produzir observações confiáveis do discurso — nunca produzir sucesso terapêutico.

Isso é consistente com os limites já estabelecidos em [Documento-Mestre.md §6.5](Documento-Mestre.md#65-limites-do-sistema) (o sistema não interpreta, não atribui significado, não substitui a escuta clínica) e com o [Objetivo Científico do PsycheAI](Documento-Mestre.md#60-objetivo-científico-do-psycheai): construir uma base observacional digital, rastreável e auditável.

## 2. Princípio da Neutralidade Observacional

> Ver o princípio completo, com fundamentação histórica, em [Arquitetura-Cientifica.md §4](Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional) e [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md).

O PsycheAI não mede o sucesso de sua operação pelo desfecho clínico. Casos concluídos, interrompidos, abandonados, inconclusivos ou considerados fracassos clínicos possuem igualmente valor científico para a plataforma. A qualidade científica do PsycheAI é medida pela qualidade dos dados observados, organizados, representados e preservados — nunca pelo resultado clínico.

## 3. Status do Caso

Dimensão observacional nova, registrada nesta versão — um atributo puramente descritivo do estado de uma Sessão/acompanhamento, nunca um juízo de valor sobre seu resultado:

- Em andamento
- Encerrado
- Interrompido pelo sujeito
- Interrompido pelo analista
- Abandono
- Encaminhamento
- Outro

O status do caso jamais altera o valor científico dos dados coletados — todo o histórico, recorrências, formações discursivas, eventos, memória longitudinal e observações produzidas pelos motores permanecem preservados e igualmente válidos, independentemente de qual destes sete valores se aplica.

**Nota de escopo**: este documento registra o "Status do Caso" como conceito observacional — parte do Modelo Observacional. A decisão de onde e como representá-lo no Domínio (nova propriedade de `Sujeito`? de `Sessao`? nova Entidade?) e sua exposição via API/Web é, como todo o resto desta cadeia, uma decisão de implementação futura, sujeita à mesma cadeia de rastreabilidade de [Arquitetura-Cientifica.md §1](Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) — nenhuma alteração de código, banco de dados ou API foi feita nesta Sprint, que é exclusivamente documental.

## 4. O que este documento não faz

- Não define estrutura de dados (isso é [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)).
- Não define vocabulário conceitual psicanalítico (isso são as Ontologias e a [Biblioteca Teórica](Biblioteca-Teorica/README.md)).
- Não interpreta nem avalia o resultado clínico de nenhum caso — pelo contrário, existe para impedir que o sistema faça isso.
- Não detalha, conceito a conceito, o que pode ou não ser observado — isso é o catálogo em [Modelo-Observacional/](Modelo-Observacional/README.md).
- Não descreve como os conceitos observáveis se relacionam entre si — isso é o [Modelo Relacional](Modelo-Relacional/README.md) (Sprint 27), camada seguinte na cadeia de rastreabilidade.

## 5. Catálogo por conceito

Adicionado na Sprint 26 — [Modelo-Observacional/](Modelo-Observacional/README.md) traduz cada um dos 21 conceitos canônicos da Biblioteca Teórica ([Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais) + [Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)) em fenômeno observável computacionalmente, com evidências, dados necessários/opcionais, limites explícitos e as cinco perguntas Sim/Não de automação e confirmação/validação já em uso na Aplicação Computacional da Biblioteca Teórica. Nenhum motor novo pode ser desenvolvido sem que o conceito que ele operacionaliza tenha seu Modelo Observacional documentado ali primeiro — mesma obrigatoriedade já registrada em [Arquitetura-Cientifica.md §1](Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## 6. Modelo Relacional (camada seguinte)

Adicionado na Sprint 27 — [Modelo-Relacional/](Modelo-Relacional/README.md) documenta como os mesmos 21 conceitos, já traduzidos em fenômeno observável por este catálogo, se relacionam entre si: conceitos antecedentes, consequentes, relacionados, relações estruturais/temporais/observacionais/de dependência/bidirecionais/não observáveis computacionalmente — cada uma com fundamentação bibliográfica, intensidade e natureza explícitas — além de seis matrizes e a especificação (sem implementação) de cinco grafos científicos. Nenhum Motor de Representação pode ser desenvolvido sem que essa camada relacional esteja documentada primeiro, mesma obrigatoriedade já registrada em [Arquitetura-Cientifica.md §1](Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## 7. Representação Computacional (camada seguinte)

Adicionado na Sprint 29 — [Representacao-Computacional/](Representacao-Computacional/README.md) documenta como cada fenômeno observável catalogado por este documento e por [Modelo-Observacional/](Modelo-Observacional/README.md) chega a ser efetivamente apresentado ao Analista (oito representações: Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos, Indicadores) e, de forma estritamente distinta, ao Sujeito. Nenhum Motor de Representação pode ser desenvolvido sem que essa camada esteja documentada primeiro, mesma obrigatoriedade já registrada em [Arquitetura-Cientifica.md §1](Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## Referências cruzadas do projeto

- [Modelo-Observacional/README.md](Modelo-Observacional/README.md)
- [Modelo-Relacional/README.md](Modelo-Relacional/README.md)
- [Representacao-Computacional/README.md](Representacao-Computacional/README.md)
- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura-Cientifica.md](Arquitetura-Cientifica.md)
- [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)
- [Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md](Biblioteca-Teorica/Valor-Cientifico-dos-Casos.md)
- [Roadmap.md](Roadmap.md)
