# Representações Lacanianas — Representação Computacional

> Sprint 29. Documenta como as oito estruturas lacanianas do briefing — metonímia, metáfora, cadeia significante, Outro, Falta, Objeto a, RSI, desejo — poderão ser apresentadas ao Analista. Representação estrutural, nunca interpretação clínica (Regra 11, [Regras-Dominio.md](../Regras-Dominio.md)).

## Objetivo

Apresentar ao Analista a reclassificação, com vocabulário lacaniano, de fatos estruturais já observados pelo Motor Freud — nunca uma leitura de sentido, nunca a afirmação do estatuto de significante confirmado, que permanece exclusiva do processo analítico ([Ontologia-Lacan.md §5](../Ontologia-Lacan.md#5-limites)).

## Rastreabilidade

```
Biblioteca Teórica: Metonímia, Metáfora, Cadeia significante, Outro, Falta, Objeto a, os três Registros (RSI), Desejo lacaniano (Ontologia-Lacan.md §3)
Modelo Observacional: Modelo-Observacional/Conceitos/{metonimia,metafora,...}.md
Modelo Relacional: Modelo-Relacional/Conceitos/{mesmos oito}
Representação Computacional: este documento
```

## As oito estruturas

| Estrutura | Estado | Fundamentação |
|---|---|---|
| **Metonímia** | Implementado | `ReclassificadorLacaniano::reclassificar()`/`reclassificarComTrajeto()` — rótulo "Estrutura candidata: deslize metonímico", produzido sempre que uma `Recorrencia` não atravessa múltiplas sessões; único rótulo lacaniano efetivamente produzido por observação própria (não reclassificação de outra coisa), sobre a mesma base de Repetição |
| **Metáfora** | Mapeado, disparado apenas via classificação freudiana | `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` devolve "Estrutura candidata: metáfora — condensação" quando `TipoFormacaoFreudiana::Chiste` ou `::Sonho` — mas o detector determinístico de repetição por si só não captura o fenômeno (substituição entre dois conteúdos distintos) que fundamentaria uma metáfora observada diretamente |
| **Cadeia significante** | Não implementado | Nenhuma representação computacional definida nesta versão ([Modelo-Observacional/README.md](../Modelo-Observacional/README.md#panorama-desta-sprint)) — permanece a questão de pesquisa em aberto de [Documento-Mestre.md §6.6](../Documento-Mestre.md#66-questão-de-pesquisa-em-aberto): como representar um significante sem reduzi-lo a uma palavra |
| **Outro** | Não implementado | Sem representação computacional nesta versão |
| **Falta** | Não implementado | Sem representação computacional nesta versão |
| **Objeto a** | Não implementado | Sem representação computacional nesta versão |
| **RSI (Registros Simbólico, Imaginário, Real)** | Não implementado, com uma exceção parcial | Os três Registros não têm representação computacional própria; o rótulo de circuito ("Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas") é fundamentado, na tabela de `ReclassificadorLacaniano::FUNDAMENTACAO`, como leitura de Repetição enquanto Real — "o que insiste e retorna ao mesmo lugar" — mas não existe um rótulo "Real" isolado, apenas essa fundamentação textual anexada ao rótulo de circuito (ver [Circuitos.md](Circuitos.md)) |
| **Desejo (lacaniano)** | Não implementado | Sem representação computacional nesta versión — distinto do Desejo (Freud), também sem observação própria (ver [Modelo-Observacional/Conceitos/desejo-freud.md](../Modelo-Observacional/Conceitos/desejo-freud.md)) |

## Formação de compromisso — caso de indeterminação documentada

`reclassificarPorTipoFreudiano()` devolve um quarto rótulo, "Estrutura candidata: formação de compromisso — a determinar entre metáfora e metonímia", quando o Motor Freud classifica o conteúdo como `FormacaoDeCompromisso` — a categoria geral (Ontologia-Freud.md §3.5) não permite decidir entre metáfora e metonímia sem mais informação, e o sistema nunca resolve essa indeterminação por conta própria.

## Dados necessários

Uma ou mais `Recorrencia` (para metonímia/circuito) ou um `EventoDiscursivo` já classificado por `ClassificadorFreudianoLLM` (para metáfora/formação de compromisso via ponte freudiana) — ver [Formacoes-Freudianas.md](Formacoes-Freudianas.md).

## Dados opcionais

`OcorrenciaRecorrencia[]` (circuito), para distinguir metonímia simples de circuito.

## Componentes envolvidos

`ReclassificadorLacaniano` (Domain), `ObservacaoApplicationService::consultar()`/`consultarCircuito()`, `ClassificarFormacaoFreudianaHandler`.

## Evidências que sustentam esta representação

Tabela de lookup determinística (`ReclassificadorLacaniano::FUNDAMENTACAO`), sem LLM nesta etapa — a reclassificação lacaniana em si nunca chama um modelo de linguagem; apenas traduz, por regra fixa, um fato estrutural já determinado (repetição normalizada, circuito através de sessões, ou tipo freudiano já classificado). Ver [Evidencias.md](Evidencias.md).

## Visão do Analista / Visão do Sujeito

Exclusiva do Analista — princípio permanente ("a escrita lacaniana pertence ao analista, não ao sujeito", [Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos)). A fundamentação teórica (`fundamentacaoPara()`) também é exclusiva do Analista, nunca da conversa com o Sujeito (Regra 11). Ver [Interface-Sujeito.md](Interface-Sujeito.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Formacoes-Freudianas.md](Formacoes-Freudianas.md)
- [Circuitos.md](Circuitos.md)
- [Evidencias.md](Evidencias.md)
- [../Ontologia-Lacan.md](../Ontologia-Lacan.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Documento-Mestre.md](../Documento-Mestre.md#5-princípios-éticos)
