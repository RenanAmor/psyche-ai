# Arquitetura em Camadas — PsycheAI

> Versão 1.4

Este documento define a arquitetura em camadas do PsycheAI.

A arquitetura separa responsabilidades e garante independência entre domínio, aplicação e infraestrutura.

---

# Visão Geral

```text
Apresentação
        │
        ▼
Aplicação
        │
        ▼
Domínio
        │
        ▼
Infraestrutura
```

---

# Camada de Apresentação

Responsável pela interação com usuários e sistemas externos.

Responsabilidades:

- receber requisições;
- apresentar respostas;
- validar dados básicos de entrada;
- nunca conter regras de negócio.

## Interface Web

Desde a Sprint 11A, a Apresentação inclui uma interface web
(`Presentation/Web/`), inicialmente construída de forma independente da
API REST para não bloquear sua evolução em paralelo. Toda comunicação
passa por `HttpClientInterface`. Desde a Sprint 11B, sua implementação de
produção é `ApiHttpClient`, que fala HTTP de verdade (cURL) com a API
REST (Sprint 10) — nenhum mock permanece em produção. A troca confirmou
o isolamento buscado desde a Sprint 11A: Controllers, ViewModels,
Componentes e Views não precisaram mudar, apenas a implementação
concreta de `HttpClientInterface` injetada em `Web/Routes.php`.

## Primeiro Diálogo (Sprint 12)

A Sprint 12 valida o fluxo completo Interface → API → Application →
Domain → Persistência → Interface com a primeira conversa funcional,
sem introduzir inteligência psicanalítica nem qualquer campo novo no
Domínio:

- **Ausência de campo "autor" no Domínio.** Nem `Discurso` nem
  `EventoDiscursivo` sabem quem falou. Como a conversa desta Sprint
  sempre alterna estritamente usuário → sistema a cada envio, a
  Apresentação deriva o autor pela paridade da `Posicao` (par = usuário,
  ímpar = sistema) em vez de propor uma mudança de Domínio para um
  requisito que a própria estrutura de turnos já resolve.
- **Um endpoint novo, e apenas um.** `POST /sessions/{id}/messages`
  (`MensagemController` → `MensagemApplicationService`) é o único
  endpoint REST criado nesta Sprint. Foi necessário porque nenhuma
  composição dos endpoints CRUD já publicados (`/discourses`, `/events`)
  expressava o caso de uso real — "enviar uma mensagem e receber a
  resposta automática" —, e decidir o texto da resposta automática é
  regra de negócio, que não pode residir na Apresentação. A leitura do
  histórico de uma conversa, em contraste, não exigiu endpoint novo:
  `MensagemViewModel::historicoDaSessao()` filtra `GET /events` (já
  existente) por `sessaoId`.
- **Resposta automática isolada por porta própria.** A nova interface
  `RespostaAutomaticaInterface` (`Infrastructure/Contracts/`) — e não a
  já existente `LLMInterface` — é quem `MensagemApplicationService`
  consome. `LLMInterface` pressupõe negociação real com um provedor de
  LLM; a única implementação desta Sprint, `RespostaFixaService`
  (`Infrastructure/AI/`), apenas devolve uma resposta fixa, e usar o
  contrato de LLM para isso seria descrevê-la de forma enganosa. Os
  motores Freud e Lacan das sprints futuras substituirão
  `RespostaFixaService` implementando o mesmo contrato, sem exigir
  mudança em `MensagemApplicationService` nem em qualquer camada acima
  dela.
- **Sujeito "Visitante" padrão.** Como não há autenticação nesta fase e
  toda Sessao exige um Sujeito já existente (`SessaoApplicationService`),
  `ConversaController` garante um Sujeito fixo ("visitante") sob
  demanda via os endpoints `/subjects` já existentes, em vez de propor
  autenticação ou um vínculo de Domínio novo.

## Memória Discursiva Longitudinal (Sprint 13)

A Sprint 13 transforma o sistema em um observador longitudinal do
discurso: registra e organiza cronologicamente tudo o que já foi
produzido por um Sujeito, sem realizar nenhuma interpretação clínica.

- **Consulta, não interpretação.** `LinhaDoTempoApplicationService` e
  `ConsolidacaoApplicationService` apenas projetam e contam o que já
  existe — nenhuma comparação entre sessões, identificação de
  recorrência, significante ou associação livre automática.
  `DetectarRecorrencias`/`GerarObservacoes` (existentes desde antes da
  Sprint 7) permanecem intocados; a Consolidação desta Sprint é uma
  contagem estritamente aritmética.
- **Ancoragem estrutural do tempo, não inferência.** `Discurso` e
  `MemoriaLongitudinal` não têm timestamp próprio no Domínio. Em vez de
  acrescentar um campo novo só para a Linha do Tempo, cada Discurso é
  ancorado na data da Sessao que o contém, e cada Memória na data da
  última Sessao que consolida — uma decisão estrutural sobre dados já
  existentes, não uma leitura sobre o conteúdo do discurso.
- **Dois endpoints, e apenas dois.** `GET /subjects/{id}/timeline` e
  `GET /subjects/{id}/consolidation` são os únicos endpoints REST novos
  — "utilizar exclusivamente a API existente" significa que toda a
  consulta longitudinal é composta sobre os Repositórios de Domínio já
  publicados (`SujeitoRepository`, `SessaoRepository`,
  `MemoriaRepository`), acrescidos apenas da ponte
  `SessaoRepository::sujeitoIdDaSessao()` — necessária porque
  `MemoriaLongitudinal` não guarda o id do Sujeito a quem pertence.
- **Timeout como falha distinta de falha de conexão.** A exigência de
  tratar timeout separadamente expôs que `curl_errno() ===
  CURLE_OPERATION_TIMEDOUT` cobre tanto "nunca conectou" quanto "conectou
  e demorou a responder". `ApiHttpClient` agora usa
  `CURLINFO_CONNECT_TIME` para diferenciar os dois casos antes de
  escolher entre `ErrorType::TIMEOUT` (504) e `ErrorType::COMUNICACAO`
  (502).

---

# Camada de Aplicação

Responsável por coordenar os casos de uso.

Responsabilidades:

- executar casos de uso;
- controlar o fluxo da aplicação;
- utilizar serviços do domínio;
- não conter lógica clínica.

---

# Camada de Domínio

Representa o núcleo do PsycheAI.

Contém:

- entidades;
- value objects;
- eventos de domínio;
- agregados;
- serviços de domínio;
- regras de negócio.

Esta camada não depende de nenhuma outra.

---

# Camada de Infraestrutura

Responsável pelos detalhes técnicos.

Contém:

- persistência;
- banco de dados;
- arquivos;
- APIs externas;
- logs;
- configurações.

A infraestrutura depende do domínio, nunca o contrário.

## Contratos de Infraestrutura (Ports)

A Infraestrutura define, em `app/Infrastructure/Contracts/`, as portas que
isolam Domínio e Aplicação de qualquer tecnologia externa concreta (relógio,
log, persistência, armazenamento de arquivos, geração de UUID, transações,
despacho de eventos e mensagens, transcrição de áudio e provedores de LLM).

Apenas as interfaces e os DTOs de entrada/saída são definidos nesta camada;
as implementações concretas (SQLite, filas, provedores de IA, etc.) serão
adicionadas em sprints futuras, uma tecnologia de cada vez, sem alterar os
contratos publicados.

---

# Dependências

```text
Apresentação
      │
      ▼
Aplicação
      │
      ▼
Domínio

Infraestrutura
      │
      └────────► Domínio
```

---

# Princípios

- O domínio é independente.
- As dependências apontam para o domínio.
- Infraestrutura não contém regras de negócio.
- Casos de uso pertencem à aplicação.
- A apresentação apenas comunica com o usuário.