# Roadmap — Psyche AI

> Versão 0.4 — Sprint 3 (Ontologia Lacan)

## Sprint 0 — Fundação oficial do projeto (concluída)

- [x] Certificação do ambiente de desenvolvimento.
- [x] Estrutura física do repositório.
- [x] README institucional.
- [x] Documento Mestre (visão, missão, objetivos, escopo, princípios éticos, arquitetura conceitual).
- [x] `composer.json` base (PHP 8.2, sem dependências).
- [x] Publicação e sincronização do repositório remoto.

## Sprint 1 — Modelo Computacional do Discurso (concluída)

- [x] `docs/Modelo-Computacional-Discurso.md`: objetivo do modelo, objeto computacional do sistema (discurso registrado), unidade fundamental (Evento Discursivo), estrutura do discurso, temporalidade, estruturas discursivas, representação conceitual de hipóteses e limites.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

## Sprint 2 — Ontologia Freud (concluída)

- [x] `docs/Ontologia-Freud.md`: objetivo da ontologia, escopo teórico, dez conceitos fundamentais (Inconsciente, Recalque, Pulsão, Desejo, Formação de compromisso, Ato falho, Chiste, Sonhos, Repetição, Transferência), relações conceituais, limites e estrutura de referências bibliográficas.
- [x] Referências cruzadas adicionadas em `Documento-Mestre.md`.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

## Sprint 3 — Ontologia Lacan (atual)

- [x] `docs/Ontologia-Lacan.md`: objetivo da ontologia (complementa, reorganiza e amplia a Ontologia Freud, sem substituí-la), escopo teórico, onze conceitos fundamentais (Significante, Cadeia significante, Metáfora, Metonímia, Registro Simbólico, Registro Imaginário, Registro Real, Outro, Objeto a, Falta, Desejo lacaniano), relações conceituais e articulação explícita com cada conceito correspondente da Ontologia Freud, limites — incluindo a reafirmação explícita de que o significante não é uma palavra, não pode ser identificado automaticamente pelo sistema, e que apenas o sujeito, no processo analítico, confirma seu estatuto — e estrutura de referências bibliográficas.
- [x] Referências cruzadas adicionadas em `Documento-Mestre.md`.
- [x] Atualização do Roadmap.
- [x] Publicação e sincronização do repositório remoto.

Escopo desta sprint é exclusivamente ontológico — sem algoritmos, regras de negócio, banco de dados, classes, código, APIs ou especificação do Lacan Engine / Discourse Engine.

## Sprints futuras (não planejadas em detalhe nesta fase)

- Definição de arquitetura técnica detalhada (camadas de domínio, aplicação e infraestrutura), a partir do Modelo Computacional do Discurso.
- Especificação técnica do Evento Discursivo (formato de registro, granularidade, critérios de segmentação) — ver [Modelo-Computacional-Discurso.md (3.2)](Modelo-Computacional-Discurso.md#32-por-que-uma-unidade-própria).
- Consolidação da bibliografia freudiana estruturada em [Ontologia-Freud.md (6)](Ontologia-Freud.md#6-referências) e da bibliografia lacaniana estruturada em [Ontologia-Lacan.md (6)](Ontologia-Lacan.md#6-referências).
- Investigação da questão de pesquisa central: como representar computacionalmente um significante sem reduzi-lo a uma simples palavra (ver [Documento-Mestre.md](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto) e [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites)).
- Especificação e implementação do **Discourse Engine** (estruturação do discurso, estruturas discursivas recorrentes candidatas a cadeias de significantes, contexto temporal).
- Especificação e implementação do **Freud Engine** (núcleo conceitual: inconsciente, recalque, pulsão, desejo, etc.), a partir da Ontologia Freud.
- Especificação e implementação do **Lacan Engine** (estrutura de leitura: significante, registros RSI, objeto a, etc.), a partir da Ontologia Lacan.
- Definição de regras de negócio.
- Configuração de ambiente de testes automatizados.
- Implementação das primeiras funcionalidades.

> Este roadmap será revisado e expandido ao final de cada sprint.
