# Como os Motores do PsycheAI Usam a Biblioteca Teórica

> Este documento explica a ponte entre a fundamentação científica registrada na Biblioteca Teórica e os motores conceituais do PsycheAI definidos em [Documento-Mestre.md §7](../Documento-Mestre.md#7-arquitetura-conceitual). É o documento de fechamento exigido pelos Critérios de Aceite desta Sprint.

## O princípio

Desde esta Sprint, **nenhum conceito é implementado no código sem fundamentação científica correspondente registrada na Biblioteca Teórica**, e nenhum motor novo pode ser desenvolvido sem que o(s) conceito(s) que ele operacionaliza tenha(m) sua [Aplicação Computacional](Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito) documentada primeiro (ver [Documento-Mestre.md §6.0](../Documento-Mestre.md#60-objetivo-científico-do-psycheai)).

A cadeia de rastreabilidade exigida (ver [Documento-Mestre.md §6.0](../Documento-Mestre.md#60-objetivo-científico-do-psycheai) e [Arquitetura.md §9](../Arquitetura.md#9-base-científica-e-princípios-de-representação-biblioteca-teórica)) é:

```
Biblioteca Teórica → Modelo Observacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

- **Biblioteca Teórica**: autores, obras, conceitos — `Freud/`, `Lacan/`, `Referencias/`, `Psicanalise/`.
- **Modelo Observacional**: o que, do discurso registrado, pode em princípio ser observado — [Modelo-Computacional-Discurso.md](../Modelo-Computacional-Discurso.md).
- **Representação Computacional**: como o conceito pode aparecer para o Sujeito e para o Analista — seção obrigatória de todo documento de Conceito.
- **Ontologia**: vocabulário fixado em [Ontologia-Freud.md](../Ontologia-Freud.md) / [Ontologia-Lacan.md](../Ontologia-Lacan.md).
- **Modelo Computacional**: seção "Aplicação Computacional" de cada documento de Conceito.
- **Implementação**: código real em `app/`.
- **Testes**: suíte automatizada correspondente.

Só o nível **Conceito** (`Conceitos/`) — não Obra, não Autor — está autorizado a descrever uso e representação computacional, porque é o único nível em que a fundamentação já tem o rigor necessário (vocabulário fixado, relações mapeadas, limites explícitos nas duas Ontologias). Ver [Modelo-de-Documento.md](Modelo-de-Documento.md) para a estrutura completa dos campos "Aplicação Computacional" e "Representação Computacional".

## Separação Sujeito/Analista dentro de cada Conceito

Todo documento de `Conceitos/` responde separadamente por dois públicos, nunca de forma unificada (princípio ético registrado em [Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos) e [Arquitetura.md §9.2](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista)):

- **Visão do Sujeito**: nunca inclui vocabulário técnico, rótulo estrutural ou classificação — só o comportamento observável da IA e as perguntas socráticas que o conceito autoriza ou proíbe.
- **Visão do Analista**: onde a escrita lacaniana e as estruturas produzidas pelos motores (recorrência, circuito, rótulo estrutural) efetivamente aparecem, sempre como apoio à escuta clínica, nunca como diagnóstico automático.

## Os quatro motores e os conceitos que os fundamentam

### Discourse Engine

Organiza o discurso e expõe as recorrências detectadas ao longo do tempo, sem hierarquizar importância nem interpretar conteúdo (implementado desde a Sprint 14).

- Conceito fundante: [Repetição](Conceitos/repeticao.md).

### Freud Engine

Aplica "atenção flutuante" sobre o que o Discourse Engine expõe, classificando estruturalmente o que se repete via LLM (`ClassificadorFreudianoLLM`), sem hipótese de causa ou sentido.

- Conceitos fundantes: [Repetição](Conceitos/repeticao.md), [Formação de compromisso](Conceitos/formacao-de-compromisso.md), [Ato falho](Conceitos/ato-falho.md), [Chiste](Conceitos/chiste.md), [Sonhos](Conceitos/sonhos.md).
- Conceitos de fundamentação teórica de fundo, sem operacionalização direta: [Inconsciente](Conceitos/inconsciente.md), [Recalque](Conceitos/recalque.md), [Pulsão](Conceitos/pulsao.md), [Desejo (Freud)](Conceitos/desejo-freud.md), [Transferência](Conceitos/transferencia.md).

### Lacan Engine

Reclassifica as mesmas recorrências trazidas pelo Freud Engine com vocabulário lacaniano (`ReclassificadorLacaniano`), sem acrescentar leitura de sentido nem afirmar estatuto de significante confirmado.

- Conceito efetivamente produzido pela implementação atual: [Metonímia](Conceitos/metonimia.md).
- Conceito mapeado na tabela de reclassificação, mas não efetivamente disparado pelo detector atual: [Metáfora](Conceitos/metafora.md) — ver a limitação registrada no próprio documento do conceito.
- Conceitos sem nenhuma representação computacional nesta versão, incluídos aqui porque compõem o vocabulário estrutural de Ontologia-Lacan.md §3: [Significante](Conceitos/significante.md), [Cadeia significante](Conceitos/cadeia-significante.md), [Registro Simbólico](Conceitos/registro-simbolico.md), [Registro Imaginário](Conceitos/registro-imaginario.md), [Registro Real](Conceitos/registro-real.md), [Outro](Conceitos/outro.md), [Objeto a](Conceitos/objeto-a.md), [Falta](Conceitos/falta.md), [Desejo lacaniano](Conceitos/desejo-lacaniano.md).

### Modo Socrático

Camada de enunciação que transforma o que os motores acima trazem em pergunta dirigida ao sujeito, nunca em afirmação (`RespostaSocraticaService`/`GeradorDePerguntaSocraticaLLM`, desde a Sprint 23).

- Fundamentação nomeada explicitamente em Documento-Mestre.md §6.7 e catalogada nesta Sprint em [Referencias/socrates.md](Referencias/socrates.md) — único autor de Referências Primárias com motor do PsycheAI vinculado por nome.
- Usa as evidências já produzidas pelo Freud Engine/Lacan Engine (recorrência, rótulo estrutural) apenas para saber *onde* dirigir a pergunta — nunca para compor conteúdo interpretativo (Regra 7, [Regras-Dominio.md](../Regras-Dominio.md)).

## Regra de bloqueio para sprints futuras

Antes de qualquer sprint de implementação que amplie um motor existente ou introduza um motor novo:

1. Verificar se o conceito envolvido já tem documento em `Conceitos/`. Se não tiver, esta Sprint (ou uma sprint documental equivalente) deve criá-lo primeiro, com Aplicação Computacional completa.
2. Verificar o campo "Limitações computacionais" do conceito — ele registra, com base nas Ontologias, o que **não pode** ser automatizado. Nenhuma implementação pode contradizer esse campo sem antes revisar o documento do conceito e, se necessário, as próprias Ontologias.
3. Os itens já registrados em [Roadmap.md "Sprints futuras"](../Roadmap.md) (cadeia de significantes como matema formal, Esquema L/R, Grafo do Desejo, quatro discursos lacanianos, Nós Borromeanos) permanecem bloqueados pela mesma razão que já estava documentada ali: ausência de base ontológica ou estrutural — agora também formalizada no campo "Limitações computacionais" dos conceitos correspondentes.

## Cobertura desta versão

Dos 229 documentos desta Sprint (94 obras de Freud, 74 de Lacan, 27 Referências Primárias, 13 autores de Psicanálise, 21 Conceitos), **apenas os 21 documentos de `Conceitos/` têm Aplicação Computacional e Representação Computacional** — é uma decisão de arquitetura desta Sprint, não uma lacuna: documentar uso ou representação computacional a partir de obras ou autores individuais (que não têm o mesmo rigor de definição das Ontologias) recriaria interpretação ad-hoc, exatamente o que esta Sprint proíbe. Ver [Indices/Indice-Motores.md](Indices/Indice-Motores.md) para o inventário completo por motor.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Modelo-de-Documento.md](Modelo-de-Documento.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Ontologia-Freud.md](../Ontologia-Freud.md)
- [../Ontologia-Lacan.md](../Ontologia-Lacan.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Roadmap.md](../Roadmap.md)
