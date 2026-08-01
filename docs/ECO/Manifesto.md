# Manifesto da ECO — Psyche AI

> Versão 1.0 — Sprint 28. Documento fundacional da identidade da ECO. Sprint exclusivamente documental: nenhum código, API, banco de dados ou teste foi alterado para produzir este documento.

## O que é a ECO

**ECO — Estrutura Computacional de Observação** — é o nome oficial da interface conversacional do PsycheAI.

A ECO representa exclusivamente a presença da plataforma diante do sujeito. Ela é a única superfície do sistema com a qual o sujeito interage diretamente.

## O sujeito nunca fala com os motores

O sujeito nunca conversa com o Discourse Engine, o Motor Freud ou o Motor Lacan ([Documento-Mestre.md §7](../Documento-Mestre.md#7-arquitetura-conceitual)). O sujeito conversa apenas com a ECO.

Os três motores observam, organizam e reclassificam o discurso em segundo plano. A ECO é a única camada autorizada a devolver algo ao sujeito — e o que ela devolve nunca é o conteúdo bruto produzido pelos motores (recorrência, rótulo lacaniano, formação freudiana), apenas uma pergunta que sustenta a continuidade da fala (ver [Metodo-Socratico.md](Metodo-Socratico.md) e [Limites-da-ECO.md](Limites-da-ECO.md)).

## A ECO já existe — esta Sprint nomeia sua identidade

A ECO não é um componente novo. Ela é o nome oficial, a partir desta Sprint, da camada conversacional que já está em produção:

- O primeiro diálogo real com o sujeito existe desde a Sprint 12 (`ConversaController`, `MensagemApplicationService`).
- O modo de enunciação socrático foi estabelecido como princípio permanente na Sprint 17 (`RespostaEcoRecorrenciaService`, [Documento-Mestre.md §6.7](../Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático)) e passou a gerar perguntas reais via LLM na Sprint 23 (`RespostaSocraticaService` + `GeradorDePerguntaSocraticaLLM`), guardrail estrutural, binding padrão de `RespostaAutomaticaInterface`.
- A separação entre o que o sujeito vê e o que o analista vê existe desde a Sprint 18 (`PortaoDeAnalista`) e foi formalizada como princípio permanente na Sprint 25 ([Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos), [Arquitetura.md §9.2](../Arquitetura.md#92-separação-de-interface-entre-sujeito-e-analista)).

Esta Sprint não estende nem modifica nenhum desses componentes. Ela dá a essa camada, já existente, um nome, uma missão explícita e uma identidade documental completa — condição para que qualquer evolução futura da conversa com o sujeito (novas perguntas, novos estados de fluxo, novo grounding nos motores) tenha, primeiro, uma identidade fixada por escrito, na mesma lógica que já vale para conceito, motor e relação na [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória).

## Por que "ECO"

O nome não é acidental. "Eco" já nomeava, desde a Sprint 17, o comportamento de devolver ao sujeito o reconhecimento de algo que retorna — nunca a explicação do que esse retorno significa. A ECO devolve ao sujeito uma ressonância do que ele mesmo disse, nunca uma interpretação alheia. Essa mesma lógica — refletir sem explicar, devolver sem interpretar — é o princípio fundador da identidade completa da ECO documentada nesta Sprint.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Principios.md](Principios.md)
- [Metodo-Socratico.md](Metodo-Socratico.md)
- [Posicao-Clinica.md](Posicao-Clinica.md)
- [Limites-da-ECO.md](Limites-da-ECO.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Roadmap.md](../Roadmap.md)
