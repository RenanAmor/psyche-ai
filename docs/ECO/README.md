# ECO — Estrutura Computacional de Observação

> Versão 1.0 — Sprint 28. Camada da [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) que documenta a identidade da interface conversacional do PsycheAI. Sprint exclusivamente científica e arquitetônica: nenhum código, API, banco de dados ou teste foi alterado para produzir esta documentação.

## O que é a ECO

A ECO é a interface conversacional do PsycheAI. Ela representa exclusivamente a presença da plataforma diante do sujeito. O sujeito nunca conversa diretamente com os motores (Discourse Engine, Motor Freud, Motor Lacan) — ele conversa apenas com a ECO. Ver [Manifesto.md](Manifesto.md).

A ECO não é um componente novo: é o nome oficial, a partir desta Sprint, da camada conversacional já em produção desde a Sprint 12 e refinada nas Sprints 17, 20, 22-24 (`ConversaController`, `RespostaSocraticaService`, `GeradorDePerguntaSocraticaLLM`).

## Estrutura

| Documento | Conteúdo |
|---|---|
| [Manifesto.md](Manifesto.md) | O que é a ECO, por que ela existe, por que esse nome |
| [Principios.md](Principios.md) | Missão permanente e os cinco princípios que nenhuma sprint futura pode contradizer |
| [Metodo-Socratico.md](Metodo-Socratico.md) | A Maiêutica Socrática como método conversacional; perguntas permitidas e proibidas |
| [Posicao-Clinica.md](Posicao-Clinica.md) | O que a ECO não é: não é terapia, não é análise, não substitui o analista |
| [Fluxo-Conversacional.md](Fluxo-Conversacional.md) | As nove etapas do fluxo conversacional, cada uma marcada como implementada ou especificação futura |
| [Interface-Sujeito.md](Interface-Sujeito.md) | O que o sujeito visualiza e o que nunca visualiza |
| [Interface-Analista.md](Interface-Analista.md) | O que o analista pode visualizar, fora da ECO |
| [Limites-da-ECO.md](Limites-da-ECO.md) | Os dez limites permanentes, consolidados em um único documento de referência |
| [Etica.md](Etica.md) | O compromisso ético permanente: a ECO nunca interpreta |

## Missão

Sustentar um espaço de associação livre. Jamais interpretar, jamais aconselhar, jamais diagnosticar, jamais conduzir o sujeito a respostas previamente esperadas, jamais ocupar o lugar do analista. Ver o detalhamento em [Principios.md](Principios.md).

## Posição na cadeia de rastreabilidade

A ECO ocupa, na arquitetura do PsycheAI, o lugar do "modo de enunciação" já registrado em [Documento-Mestre.md §7](../Documento-Mestre.md#7-arquitetura-conceitual): a camada que transforma o que o Discourse Engine, o Motor Freud e o Motor Lacan trazem em pergunta dirigida ao sujeito, nunca em afirmação. Esta Sprint não altera essa posição — apenas a nomeia e documenta por completo, condição para que qualquer evolução futura da conversa com o sujeito tenha identidade fixada por escrito antes de qualquer código, na mesma disciplina já exigida da Biblioteca Teórica, do Modelo Observacional e do Modelo Relacional.

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
                                                                                                                    │
                                                                                                                    ▼
                                                                                                        ECO (modo de enunciação)
```

## Restrições desta Sprint

Nenhum código foi alterado. Nenhuma API foi modificada. Nenhum comportamento conversacional novo foi implementado. Todo componente citado nos nove documentos desta pasta já existia em produção antes desta Sprint — auditado contra o código real, na mesma disciplina já aplicada pela Biblioteca Teórica (Sprint 25), pelo Modelo Observacional (Sprint 26) e pelo Modelo Relacional (Sprint 27). Nenhum componente foi inventado.

## Referências cruzadas do projeto

- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Arquitetura.md](../Arquitetura.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Biblioteca-Teorica/README.md](../Biblioteca-Teorica/README.md)
- [../Modelo-Observacional/README.md](../Modelo-Observacional/README.md)
- [../Modelo-Relacional/README.md](../Modelo-Relacional/README.md)
- [../Roadmap.md](../Roadmap.md)
