# Fluxo Conversacional da ECO — Psyche AI

> Versão 1.0 — Sprint 28. Especificação do fluxo conversacional da ECO — nenhum comportamento novo foi implementado para produzir este documento. Cada etapa abaixo indica explicitamente se já está em prática hoje (com o componente real que a implementa) ou se é especificação para sprint futura, sujeita à mesma [cadeia de rastreabilidade](../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória) de qualquer outro motor.

## As nove etapas

### 1. Início da sessão

O sujeito chega a `/conversa`. Se não houver Sessao ativa em `$_SESSION`, uma nova Sessao é criada automaticamente sob o Sujeito identificado pelo cookie de longa duração (`psyche_pessoa_id`) — sem exigir cadastro ou login.

**Estado**: implementado desde a Sprint 12, identidade por cookie desde a Sprint 17 (`ConversaController::sessaoAtivaOuNova()`).

### 2. Abertura

A primeira devolução da ECO em uma Sessao nova não tem histórico prévio para ecoar — não há repetição a refletir. A ECO responde à primeira mensagem do sujeito com uma pergunta socrática construída sobre o conteúdo trazido, sem qualquer referência a sessões anteriores nesta etapa.

**Estado**: implementado — mesmo caminho de `RespostaSocraticaService` de qualquer outro turno, apenas sem sinal de repetição na primeira mensagem.

### 3. Associação livre

O corpo da conversa: o sujeito fala livremente, turno a turno, e a ECO devolve uma pergunta socrática a cada mensagem — nunca uma afirmação, nunca uma lista de opções, nunca uma conclusão.

**Estado**: implementado — `GeradorDePerguntaSocraticaLLM` roda em toda mensagem do sujeito (Sprint 23), não apenas quando há repetição.

### 4. Perguntas

Ver o detalhamento completo de perguntas permitidas e proibidas em [Metodo-Socratico.md](Metodo-Socratico.md). Cada pergunta devolvida se apoia estritamente no que o próprio sujeito trouxe nos turnos recentes da sessão atual.

**Estado**: implementado, com o guardrail estrutural descrito em [Metodo-Socratico.md](Metodo-Socratico.md).

### 5. Silêncio

Quando o sujeito não responde, envia uma mensagem vazia ou uma mensagem mínima (ex.: "..."), a ECO não deve preencher o silêncio com uma pergunta insistente nem interpretá-lo como recusa, resistência ou impasse — o silêncio é, ele mesmo, uma forma legítima de associação livre. A resposta esperada da ECO diante do silêncio é sustentar o espaço sem pressionar, nunca decidir por ele o que fazer.

**Estado**: especificação para sprint futura. Hoje, mensagem vazia é apenas validada e rejeitada antes de qualquer chamada à API ("A mensagem não pode ser vazia.", `ConversaController::processarEnvio()`) — não há tratamento distinto para silêncio prolongado ou mensagens mínimas como evento discursivo com sentido próprio.

### 6. Mudança de tema

Quando o sujeito muda de assunto no meio da conversa, a ECO não deve trazer o sujeito de volta ao tema anterior nem apontar a mudança como fato notável — mudar de tema é parte legítima da associação livre, não um desvio a corrigir.

**Estado**: já respeitado por omissão (a ECO nunca comenta a coerência temática da fala), mas não há tratamento explícito que reconheça uma mudança de tema como evento distinto. `GeradorDePerguntaSocraticaLLM` apenas responde ao turno mais recente.

### 7. Retorno de temas

Quando um tema já discutido em sessões anteriores reaparece, a ECO pode devolver uma pergunta-eco reconhecendo o retorno — sem nunca afirmar por que o tema retornou.

**Estado**: parcialmente implementado dentro de uma única sessão (`RespostaEcoRecorrenciaService`/`RespostaSocraticaService` detectam repetição literal de conteúdo dentro do histórico persistido do Sujeito). A continuidade explícita entre sessões distintas — reconhecer que um tema volta depois de dias ou semanas — está listada como sprint futura no [Roadmap.md](../Roadmap.md) ("Motor de Enunciação Socrática: continuidade cross-sessão").

### 8. Encerramento

O sujeito pode interromper a conversa a qualquer momento, sem qualquer exigência de fechamento formal — nenhuma pergunta de despedida é imposta, nenhuma síntese da sessão é oferecida ao sujeito. Preservar tudo o que foi dito, mesmo sem encerramento formal, é o mesmo Princípio da Neutralidade Observacional que já vale para o sistema inteiro ([Arquitetura-Cientifica.md §4](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional)).

**Estado**: implementado por ausência de exigência — não há fluxo de "finalizar sessão" na interface do sujeito; a Sessao permanece registrada com tudo o que já foi dito.

### 9. Nova sessão

Uma nova Sessao começa automaticamente quando `$_SESSION` expira (nova visita, mesmo cookie de pessoa) ou quando o sujeito sai e retorna sem cookie de identidade — nesse último caso, um novo Sujeito anônimo é criado, começando do zero. O sujeito também pode encerrar a identidade atual explicitamente (`ConversaController::sair()`), que remove o cookie e limpa a Sessao ativa.

**Estado**: implementado desde a Sprint 12/17/20 (`ConversaController::criarNovaSessao()`, `sair()`).

## Princípio comum às nove etapas

Nenhuma etapa deste fluxo autoriza a ECO a sair do método socrático ([Metodo-Socratico.md](Metodo-Socratico.md)) ou a ultrapassar os limites permanentes ([Limites-da-ECO.md](Limites-da-ECO.md)). O fluxo descreve *quando* cada tipo de resposta ocorre — nunca abre exceção para *o que* a ECO pode dizer.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Manifesto.md](Manifesto.md)
- [Metodo-Socratico.md](Metodo-Socratico.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Limites-da-ECO.md](Limites-da-ECO.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Roadmap.md](../Roadmap.md)
