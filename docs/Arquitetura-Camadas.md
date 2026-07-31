# Arquitetura em Camadas — PsycheAI

> Versão 1.7

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

## Discourse Engine — exposição sem persistência (Sprint 14)

A Sprint 14 expõe pela primeira vez, via API e tela, a infraestrutura de
recorrência que já existia desde antes da Sprint 7 em memória
(`DetectorRecorrencias`, `RecorrenciaMinimaSpecification`,
`GeradorObservacoes`, encadeados por `CicloDeObservacaoService`) mas
nunca era consultável de fora de um teste.

- **Recalcular, não persistir.** Diferente do plano original desta
  Sprint (que previa migration e repositório para `Recorrencia`/
  `Observacao`), a decisão final foi recalcular a cada consulta, no
  mesmo padrão de `LinhaDoTempoApplicationService`/
  `ConsolidacaoApplicationService` (Sprint 13). Persistir uma Recorrência
  tende, com o tempo, a ser lida como constatação/diagnóstico armazenado
  — na contramão das Regras 7-10 de
  [Regras-Dominio.md](Regras-Dominio.md) — além de criar um problema de
  invalidação: uma Recorrência gravada ficaria desatualizada se a sessão
  de origem fosse editada ou removida depois. Na escala esperada
  (centenas de eventos por Sujeito), recalcular a cada leitura é
  irrelevante em custo.
- **Nenhuma entidade nova.** `ObservacaoApplicationService` monta uma
  `MemoriaLongitudinal` transitória (nunca persistida, usando o próprio
  id do Sujeito como identificador) e delega inteiramente a
  `CicloDeObservacaoService::executar()`, já existente e inalterado —
  esta Sprint é só o Application Service e o endpoint ao redor dele, não
  uma nova classe de resultado.
- **Um endpoint, e apenas um.** `GET /subjects/{id}/observations` é o
  único endpoint REST novo, seguindo exatamente o padrão de
  `GET /subjects/{id}/timeline`.
- **Tela própria, não embutida no Histórico.** A tela de Observações
  (`ObservacoesSujeitoController`) foi deliberadamente separada da tela
  de Histórico (Sprint 13), apesar de ambas combinarem duas respostas de
  API — a Sprint 16 (Motor Lacan) vai estender exatamente esta tela com
  os rótulos lacanianos lado a lado, e antecipar essa separação evita
  reabrir `HistoricoSujeitoController` quando isso acontecer.
- **Circuito/trajeto (revisão pós-Sprint 16).** A mesma tela — e o mesmo
  `ObservacaoApplicationService` — ganham
  `GET /subjects/{id}/observations/circuito`: onde/quando cada
  Recorrencia do Motor Freud reaparece através das Sessões, ver seção
  própria abaixo.
- **Motores continuam fora da conversa.** Esta Sprint não altera
  `RespostaAutomaticaInterface`/`ConversaController`, que seguem com
  `RespostaFixaService` — a integração dos motores com a conversa
  permanece reservada para a Sprint de Interface Conversacional (deixa
  de valer na Sprint 17, ver seção abaixo).

---

## Interface Conversacional (Sprint 17)

Referência de produto confirmada com o usuário: "algo como o ChatGPT" só
na experiência (streaming/atualização incremental, sensação de
fluidez) — não na inteligência por trás. A decisão de "sem LLM" que
vale desde a Sprint 1 não é reaberta nesta Sprint: `LLMInterface`
continua sem nenhuma implementação (essa decisão viria a ser revertida
só na revisão do Motor Freud, ver seção própria abaixo).

- **Motores passam a tocar a conversa.** Decisão fechada com o usuário
  ao planejar esta Sprint: `RespostaAutomaticaInterface` ganha um novo
  binding padrão, `RespostaEcoRecorrenciaService`
  (`Infrastructure/AI/`), que reaproveita os mesmos Use Cases do
  Discourse Engine/Motor Freud (`ConstruirMemoriaLongitudinalHandler` +
  `DetectarRecorrenciasHandler`, Sprints 13-15) para checar se a
  mensagem recebida já foi dita antes pelo Sujeito; se sim, devolve uma
  pergunta-eco que só nomeia a repetição, nunca uma interpretação
  (Regra 7, [Regras-Dominio.md](Regras-Dominio.md)). `RespostaFixaService`
  não é removida — passa a ser a resposta de reserva de
  `RespostaEcoRecorrenciaService` para quando ainda não há repetição
  alguma. Para isso, o contrato `RespostaAutomaticaInterface::responder()`
  ganha um segundo parâmetro, `$sujeitoId` (com valor padrão `''`, para
  não quebrar implementações que não precisam dele), que
  `MensagemApplicationService` preenche via
  `SessaoRepository::sujeitoIdDaSessao()` (já existente desde a Sprint 13).
- **Identidade por cookie substitui o Sujeito "visitante" fixo.** A nota
  da Sprint 12 acima (`ConversaController` garante um Sujeito fixo
  "visitante" compartilhado por todo mundo) deixa de valer: cada
  navegador passa a receber um cookie de longa duração
  (`psyche_pessoa_id`, 1 ano, `HttpOnly`, independente de `$_SESSION`)
  na primeira vez que uma nova Sessao precisa ser criada, reaproveitado
  nas visitas seguintes mesmo depois que `$_SESSION` expira. Isso isola
  o discurso de cada pessoa — necessário para que
  `RespostaEcoRecorrenciaService` (e as telas dos motores Freud/Lacan)
  observem recorrências de UM sujeito, não a mistura de todos os
  visitantes. Continua sem login/conta real: é uma identidade anônima
  estável por navegador, que a Sprint 18 (Plataforma/autenticação) pode
  ligar a uma conta sem perder o histórico já acumulado.
- **fetch() troca HTML pronto por HTML pronto, sem WebSocket/SSE.** A
  resposta do sistema é sempre determinística e computada de forma
  síncrona — não há nada gerado token a token para transmitir em
  chunks. "Streaming" nesta Sprint significa apenas: `POST
  /conversa/mensagens` (novo método `ConversaController::mensagens()`)
  devolve, em JSON, o mesmo fragmento HTML que `ConversaAreaComponent`
  (`Presentation/Web/Components/`, novo) já monta para a página cheia; o
  `<script>` da view troca o `innerHTML` de `#conversa-area` por esse
  fragmento, sem recarregar a página. Nenhuma lógica de montagem de
  mensagem é duplicada em JavaScript — o HTML sempre vem pronto do
  servidor, no mesmo espírito minimalista do restante do projeto (sem
  bundler, sem framework front-end). `POST /conversa/enviar` (a rota
  clássica da Sprint 12) continua existindo e funcional: se `fetch()`
  falhar ou JavaScript estiver desabilitado, o formulário recai nela.

---

## Revisão das Sprints 15-16 — Portão do Analista e Circuito/Trajeto (pós-Sprint 16)

Uma conversa posterior à conclusão das Sprints 14-17 aprofundou "mapear a
pulsão, todo o caminho" e "Lacan é a linguagem", motivando uma revisão
aditiva — nenhum contrato publicado nas Sprints 14-17 muda.

- **Dois públicos, duas regras.** O Sujeito que fala em `/conversa` nunca
  vê os motores (já garantido desde a Sprint 14, reafirmado aqui). O
  analista/administrador é para quem os motores trabalham de verdade —
  a Regra 10 ("toda interpretação pertence ao analista",
  [Regras-Dominio.md](Regras-Dominio.md)) não exige conservadorismo com o
  próprio analista, só que a interpretação final seja sempre dele.
- **Portão de sessão, não conta de usuário.** `Presentation/Web/Security/PortaoDeAnalista`
  é deliberadamente simples: compara a senha recebida com
  `getenv('PSYCHEAI_SENHA_ANALISTA')` via `hash_equals()` e marca
  `$_SESSION['psyche_analista_autenticado']` — sem entidade de Domínio,
  sem persistência própria. `proteger(callable $handler): Closure`
  envolve cada handler no momento do registro em `Web/Routes.php`
  (`/`, `/sujeitos*`, `/sessoes*`, `/discursos*`, `/memorias*`,
  `/eventos-discursivos`); redireciona (302) para `/entrar` quando não
  autenticado. `/conversa*`, `/erros/*`, `/entrar` e `/sair` ficam fora
  do Portão. A chave de sessão é deliberadamente distinta de
  `psyche_pessoa_id`/`psyche_conversa_sessao_id` (identidade do Sujeito),
  para que a Sprint 18 possa apagar `PortaoDeAnalista` e
  `AutenticacaoAnalistaController` inteiros, sem dívida de migração, ao
  substituí-los por contas reais. A API REST não é protegida nesta
  passada — só é chamada servidor-a-servidor por `ApiHttpClient`.
- **Circuito, não uma nova classe de resultado.** "Mapear a pulsão, todo
  o caminho" = o circuito/trajeto de uma Recorrencia ao longo do tempo
  (quando/onde ela reaparece através das Sessões), não uma nova
  interpretação. `DetectorRecorrencias::detectarCircuito()` vive na
  mesma classe de `detectar()` (mesma `normalizar()`, para que "mesma
  recorrência" nunca divirja entre contagem e circuito) e devolve
  `array<string, OcorrenciaRecorrencia[]>` — `OcorrenciaRecorrencia`
  (novo Value Object) ancora `momento` na data da Sessão, não no
  timestamp técnico do Evento, mesma ancoragem estrutural do tempo já
  adotada desde a Sprint 13. `ObservacaoApplicationService::consultarCircuito()`
  usa o resultado já filtrado (limiar ≥2) de
  `CicloDeObservacaoService::executar()` como única fonte de quais
  Recorrencias existem — o circuito nunca introduz uma Recorrencia que o
  Motor Freud não tenha trazido.
- **Lacan como gramática sobre o circuito, não interpretação nova.**
  "Lacan é a linguagem" pede a transcrição do aparato formal lacaniano
  como notação sobre o material do Freud — nunca uma leitura de sentido.
  `ReclassificadorLacaniano::reclassificar()` (Sprint 16) fica congelado;
  o método aditivo `reclassificarComTrajeto()` apenas diferencia, com o
  mesmo tipo de constatação estrutural contável do rótulo original, duas
  situações: a recorrência atravessa ≥2 Sessões distintas (rótulo de
  circuito) ou não (mesmo rótulo de sempre, deslize metonímico).
- **Os quatro discursos ficam de fora, explicitamente.** Os quatro
  discursos lacanianos (Seminário 17) não têm base ontológica
  ([Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)
  não os documenta) nem estrutural (`EventoDiscursivo` não modela
  interlocutor, papel de enunciação ou laço social) — mapeá-los exige
  uma sprint própria de ontologia antes de qualquer código
  (docs/Roadmap.md, "Sprints futuras").

## Revisão do Motor Freud — classificação estrutural via LLM (2026-07-30)

Reverte a decisão de "sem LLM" que vinha desde a Sprint 1 (reafirmada na
Sprint 17, seção acima) — o usuário constatou que a restrição vinha de
um entendimento equivocado sobre o método socrático do sistema
([Documento-Mestre.md §6.7](Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático)),
não de uma limitação real: reconhecer a forma de ato falho/chiste/sonho/
formação de compromisso num conteúdo discursivo exige conhecimento
conceitual que a comparação literal de string de `DetectorRecorrencias`
não fornece.

- **Primeira implementação concreta de `LLMInterface`.**
  `Infrastructure/AI/AnthropicLLMService` usa o SDK oficial
  `anthropic-ai/sdk` (primeira dependência de runtime do projeto —
  `composer.json` só listava `php` até aqui) e o modelo
  `claude-haiku-4-5`. A interface em si (`complete(LLMRequestDTO):
  LLMResponseDTO`) não muda — só ganha, pela primeira vez, quem a
  implementa de verdade.
- **O guardrail é de sistema, não de prompt.** A chamada usa
  `output_config.format` com um JSON Schema cujo único campo é um enum
  fechado de 6 strings (`Domain/ValueObjects/TipoFormacaoFreudiana`) —
  não há onde o modelo colocar interpretação. A resposta bruta nunca é
  confiada: `Infrastructure/AI/ClassificadorFreudianoLLM` valida contra
  `TipoFormacaoFreudiana::tryFrom()`; qualquer coisa fora do vocabulário
  fechado (JSON inválido, valor desconhecido, falha de rede/API) cai em
  `NaoClassificado`, nunca um valor solto ou texto livre.
- **Nova porta, não reaproveitamento direto de `RespostaAutomaticaInterface`.**
  `Infrastructure/Contracts/ClassificadorEstruturalInterface` é o
  contrato que a Application conhece — a Application nunca importa
  `AnthropicLLMService` nem `ClassificadorFreudianoLLM` diretamente,
  mesmo padrão de isolamento já usado para `RespostaAutomaticaInterface`/
  `RespostaEcoRecorrenciaService` desde a Sprint 17.
- **Motor Lacan continua sem LLM algum.** `ReclassificadorLacaniano`
  sempre foi "não analisa dado novo, só reclassifica com vocabulário
  lacaniano" — o método aditivo `reclassificarPorTipoFreudiano()` é uma
  tabela de lookup determinística sobre o `TipoFormacaoFreudiana` já
  classificado por outra camada (Chiste/Sonho → metáfora; Ato
  falho/Repetição → deslize metonímico; Formação de compromisso →
  indeterminado entre as duas), fiel a
  [Ontologia-Lacan.md §4](Ontologia-Lacan.md#4-relações-conceituais).
  Nenhuma chamada a modelo entra no Motor Lacan.
- **Sem endpoint/tela nesta revisão.** Fica em
  Domain/Infrastructure/Application — a exposição via API/tela foi
  deliberadamente adiada aqui para não colidir com o trabalho simultâneo
  da revisão anterior (Portão do Analista/Circuito). Ligada ao
  composition root só na revisão "Motor Lacan — fundamentação teórica
  para o analista", ver seção própria abaixo e docs/Roadmap.md.

---

## Camada de Visualização Gráfica — Grafo do Circuito/Trajeto (Sprint 19)

Extensão estritamente de Presentation/Web sobre o circuito/trajeto já
exposto pela revisão pós-Sprint 16 — zero mudança em Domain, Application
ou API REST. Não é a cadeia de significantes lacaniana:
[Ontologia-Lacan.md §4](Ontologia-Lacan.md#4-relações-conceituais) não
define nenhuma representação computacional para ela ainda; o dado é
Freud-side (recorrência através de Sessões), só rotulado pelo Motor Lacan
quando cruza ≥2 sessões.

- **Stack**: SVG + D3.js via CDN, sem bundler/Node — decisão deliberada
  para não introduzir pipeline de build num projeto 100% PHP server-side.
  `public/web/assets/js/grafo-circuito.js` é servido como arquivo
  estático (mesmo docroot do front controller da Web,
  `public/web/index.php`), sem passar pelo `Router`.
- **Nova rota Web, não API**: `GET
  /sujeitos/{id}/observacoes/grafo-circuito`
  (`ObservacoesSujeitoController::grafoCircuito()`), protegida por
  `PortaoDeAnalista::proteger()` como toda rota de análise. É Web (não
  `Presentation/Routes.php`, a API REST) porque o `fetch()` do navegador
  precisa do mesmo cookie de sessão da página — a API REST só é chamada
  servidor-a-servidor.
- **`GrafoCircuitoViewModel`** reformata `CircuitoRecorrenciaViewModel[]`
  (o mesmo dado que `CircuitoTrajetoComponent` já lista como texto) em
  nós (Sessões distintas) e arestas (elo entre ocorrências consecutivas
  de uma Recorrencia), servido via `Response::json()`.
- **Fallback textual preservado**: `CircuitoTrajetoComponent` continua
  renderizado no servidor, sempre — se o CDN do D3 falhar, o analista não
  perde o dado.
- **Codificação visual do princípio ético**: aresta tracejada + rótulo
  visível quando `rotuloLacaniano` não é nulo (Motor Lacan já
  reclassificou como "estrutura candidata: circuito"); traço sólido
  quando é só constatação de recorrência (Motor Freud). Nunca apresentado
  como interpretação confirmada (Regra 10).

---

## Sprint 18 — Plataforma: contas reais de analista

Substitui a senha única de `PSYCHEAI_SENHA_ANALISTA`
(`Presentation/Web/Security/PortaoDeAnalista`, revisão pós-Sprint 16) por
contas reais — exatamente a dívida que aquela revisão já previa e
desenhou para ser paga sem migração. Escopo desta passagem, alinhado
antes com o usuário: só a conta do analista (um único papel, sem
permissões/administração granulares); o Sujeito em `/conversa*` continua
anônimo por cookie, sem conta — "dois públicos, duas regras" (revisão
pós-Sprint 16) permanece valendo.

- **`Analista`, não um conceito do Modelo Computacional do Discurso.**
  `Domain/Entities/Analista` (com `Domain/ValueObjects/Email` novo) é uma
  conta de acesso ao sistema, sem nenhum significado psicanalítico —
  paralela a Sujeito/Sessao/Discurso na camada, mas fora do vocabulário
  teórico das duas Ontologias. `verificarSenha()` (`password_verify`)
  fica na Entidade, não vaza para fora da fronteira de Domínio.
  `Application/DTOs/AnalistaDTO` nunca expõe `senhaHash`.
- **API REST ganha seu primeiro endpoint de autenticação.**
  `POST /auth/login` (`Presentation/Controllers/AutenticacaoController`,
  `AnalistaApplicationService::autenticar()`) devolve `{id, email}` em
  200 ou 401 (`HttpException::naoAutorizado()`, novo) — nunca distingue,
  na resposta, "e-mail não existe" de "senha errada". Sem endpoint de
  cadastro exposto por HTTP nesta passagem (ver CLI abaixo).
- **A Web nunca fala com o banco.** Mesmo padrão do resto do sistema:
  `Presentation/Web/Controllers/AutenticacaoAnalistaController` chama
  `POST /auth/login` via `HttpClientInterface` (o mesmo cliente injetado
  em todo Controller Web) — nunca importa `AnalistaApplicationService`
  nem qualquer repositório diretamente. `ApiHttpClient::erroParaStatus()`
  passa a mapear 401 para o mesmo `ErrorType::VALIDACAO` de 400/409/422.
- **`PortaoDeAnalista` perde a verificação de senha, mantém a sessão.**
  `autenticar(string $senha): bool` foi removido; `abrirSessao(string
  $analistaId): void` (chamado só depois que a API confirma a credencial)
  e `analistaId(): ?string` são novos. `estaAutenticado()`, `sair()` e
  `proteger()` continuam intocados — nenhuma rota protegida precisou
  mudar em `Web/Routes.php`.
- **Provisionamento via CLI, não tela de cadastro.** `bin/criar-analista.php
  <email> <senha>` bootstrapa `ApplicationServiceProvider::comSQLite()`
  direto (mesmo padrão de composição de `public/index.php`), sem rota
  HTTP — decisão explícita do usuário: o único uso real hoje é o próprio
  dono do sistema, então uma tela de registro público seria superfície de
  ataque sem necessidade real.
- **Fora de escopo nesta passagem** (ver `docs/Roadmap.md`, "Sprints
  futuras"): múltiplos papéis/permissões, telas de administração de
  conta, e contas reais para o Sujeito — ficam para quando (se) o produto
  precisar delas.

---

## Motor Lacan — fundamentação teórica para o analista (2026-07-30)

Fecha a lacuna deixada na revisão do Motor Freud/LLM ("Sem
endpoint/tela nesta revisão", acima): `ClassificadorFreudianoLLM` +
`AnthropicLLMService` passam a ser instanciados por padrão em
`ApplicationServiceProvider::comPDO()`, e
`ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — até aqui
uma tabela de lookup nunca chamada por nenhuma camada superior — passa
a ser usada de fato em `ObservacaoApplicationService::consultarCircuito()`.

- **Um rótulo único por Recorrência, com regra de precedência.** Quando
  `comLeituraLacaniana=true`: circuito (≥2 sessões) tem prioridade e
  **não chama o Motor Freud/LLM** — economia de custo/latência, já que
  o circuito já é a leitura mais rica disponível; senão, classifica via
  `ClassificarFormacaoFreudianaHandler` e reclassifica com
  `reclassificarPorTipoFreudiano()`; sem classificador ou
  `NaoClassificado`, cai no rótulo padrão de sempre.
- **Nova porta injetável, mesmo padrão de `$respostaAutomatica`.**
  `ObservacaoApplicationService` ganha parâmetro construtor opcional
  `?ClassificarFormacaoFreudianaHandler $classificarFormacaoFreudiana`
  — permite testes injetarem um classificador stub, sem chamada de rede
  real. `ApplicationServiceProvider::comPDO()` monta o default
  concreto; sem `ANTHROPIC_API_KEY`, `ClassificadorFreudianoLLM` já
  captura a falha e cai em `NaoClassificado` (comportamento existente).
- **Fundamentação é exclusiva do analista (Regra 11,
  Regras-Dominio.md).** `ReclassificadorLacaniano::fundamentacaoPara()`
  (novo, Domain, tabela de lookup determinística sem LLM) devolve a
  regra da ontologia que gerou o rótulo — nunca uma leitura do que ele
  significaria para o sujeito específico, que continua exclusiva do
  analista (Regra 10). Propaga por `CircuitoRecorrenciaDTO` →
  `CircuitoResponse`/`CircuitoRecorrenciaViewModel` →
  `CircuitoTrajetoComponent` (`<small class="fundamentacao-lacaniana">`).
  A conversa do sujeito (`/conversa*`) não muda em nada.

---

## Sprint 20 — Contas reais do Sujeito

Decisão de produto: o cookie pseudônimo `psyche_pessoa_id` (Sprint 17)
não garante um "espaço singular" de verdade — não sobrevive a troca de
navegador/dispositivo. Diferente da Sprint 18 (conta do analista, só
CLI, sem tela pública), aqui o Sujeito se cadastra sozinho — auto-registro
público é a superfície certa para quem fala em `/conversa*`.

- **A conta não substitui o cookie, complementa.** `Sujeito` (Domain)
  ganha `?Email $email`/`?string $senhaHash` opcionais — a maioria dos
  Sujeitos continua anônima. Cadastrar-se (`POST /subjects/{id}/account`)
  liga e-mail/senha ao Sujeito que o cookie **já aponta**, preservando
  as Sessões acumuladas (mesmo padrão de reconstrução de
  `SujeitoApplicationService::atualizar()`, que também recria a
  Entidade preservando o grafo existente). Nenhuma "adoção"/merge entre
  dois Sujeitos diferentes é necessária — é sempre o mesmo id.
- **Login troca o cookie, não busca um Sujeito por id.** Diferente do
  cadastro (que já sabe o id via cookie), o login
  (`POST /auth/subject/login`) não sabe o id de antemão — por isso é
  endpoint próprio, não sub-recurso de `/subjects/{id}`. No sucesso,
  `ConversaController::autenticar()` chama `definirCookiePessoa()`
  (refatorado de `pessoaIdAtivaOuNova()`) para apontar o cookie deste
  navegador para o Sujeito da conta — é assim que o mesmo espaço volta a
  aparecer em outro navegador/dispositivo. A Sessão ativa anterior
  (`psyche_conversa_sessao_id`) é descartada, porque pertencia à
  identidade anterior.
- **Nunca vaza qual e-mail tem conta.**
  `SujeitoApplicationService::autenticar()` devolve `null` tanto para
  e-mail inexistente quanto para senha errada — mesmo cuidado de
  `AnalistaApplicationService::autenticar()` (Sprint 18).
- **Status de login não é mostrado na tela.** `conversa/index.php` ganha
  só dois links estáticos ("Criar conta"/"Entrar"), sem indicar se o
  visitante atual já tem conta — evita uma chamada de API extra a cada
  carregamento de `/conversa` só para personalizar essa mensagem, mesma
  lógica de redução de escopo de "sem recuperação de senha".
- **Continua fora do Portão do Analista.** Todas as rotas novas
  (`/conversa/cadastro`, `/conversa/entrar`, `/conversa/sair`) ficam na
  superfície pública do Sujeito — o Portão nunca as protege.

---

## Sprint 21 — Prefixo de base para a interface web

Decisão de integração: o PsycheAI vai rodar (ao menos na fase de testes)
sob `investimentos369.com/psycheai` — sem subdomínio disponível na
hospedagem atual. Diferente de sonus-ai/Collector369 (integrados por
chamada direta de código/leitura de arquivo, nenhuma rota própria), o
PsycheAI é uma aplicação web completa e precisa aprender um prefixo
configurável para não assumir mais que é dona da raiz do domínio.

- **Prefixo removido na borda de entrada, reaplicado na borda de
  saída — nunca no meio.** `Presentation/Web/Http/BasePath` (holder
  estático) é definido uma vez em `public/web/index.php` a partir de
  `PSYCHEAI_BASE_PATH` (novo, `.env.example`); o front controller remove
  o prefixo do `REQUEST_URI` antes de montar a `Request`, então
  `Router`/`Routes.php` nunca sabem que ele existe — rotas continuam
  declaradas limpas (`/conversa`, `/sujeitos`). O prefixo só reaparece
  quando algo gera um caminho de volta para o navegador.
- **Só dois pontos concentram quase toda a geração de link da árvore de
  Views** (confirmado por busca exaustiva antes de tocar código):
  `ButtonComponent::link()` e `FormComponent::render()`. Prefixar só
  esses dois cobre todo `href`/`action` de toda tela CRUD. Os outros
  pontos (`Response::redirecionar()`, `partials/sidebar.php`, cookie de
  `ConversaController`, `fetch()` em `conversa/index.php`,
  `data-endpoint`/`<script src>` em `observacoes/mostrar.php`, cookie de
  sessão nativo do PHP) foram tratados individualmente.
- **Sem prefixo configurado, `BasePath::url()` é *no-op*** — string
  vazia antes de qualquer caminho não muda nada. Por isso todo o
  desenvolvimento local e as 532 asserções já existentes continuam
  passando sem qualquer alteração de comportamento.
- **Achado da verificação de ponta a ponta**: o servidor embutido do PHP
  (`php -S`), ao receber `return false` do roteador achando que é
  arquivo estático, procura o arquivo pelo `REQUEST_URI` **original**
  (ainda com o prefixo) — não bate com o caminho físico em disco.
  Corrigido servindo o arquivo diretamente (`readfile()`) nesse caso; só
  relevante para teste local, produção (Apache/Nginx) nunca invoca PHP
  para um asset estático.
- **Fora de escopo**: onde exatamente os arquivos ficam no servidor da
  Hostinger (decisão de infraestrutura do usuário, mesmo padrão que o
  próprio investimentos369 já usa consigo mesmo — código fora do
  document root público); a API REST não precisa do prefixo, porque
  nunca é chamada direto pelo navegador.

---

## Sprint 22 — Captura de Áudio da Sessão

O sujeito passa a poder falar, não só digitar: `/conversa` grava a
sessão inteira em áudio contínuo (não um arquivo por turno), preservado
intacto para o analista ouvir e validar a transcrição — a fala revela o
que a escrita já filtra (hesitação, meia-palavra, ato falho).

- **Duas portas de infraestrutura que existiam desde a Sprint 7 sem
  implementação ganham a primeira**: `StorageInterface`
  (`LocalFilesystemStorage`, disco local) e `TranscriptionInterface`
  (`OpenAIWhisperTranscriptionService`, `response_format=verbose_json`,
  `temperature=0` — verbatim, sem correção da fala, deliberado).
- **A transcrição vira `EventoDiscursivo` pelo mesmo caminho que uma
  mensagem digitada** — `TranscreverGravacaoAudioHandler` reaproveita
  `RegistrarEventoDiscursivoHandler` (usado por
  `MensagemApplicationService::enviar()`), um evento por segmento
  detectado pelo provedor de transcrição, sem disparar
  `RespostaAutomaticaInterface` (é fala contínua, não um turno de
  pergunta/resposta). Nenhum Motor Freud/Lacan lê o conteúdo transcrito
  nesta sprint — a Regra 11 (`/conversa*` nunca expõe interpretação)
  continua intocada.
- **Pipeline assíncrono, não síncrono ao upload**: `POST
  /conversa/audio` só grava o binário bruto (`GravacaoAudio` fica
  `Pendente`); um worker CLI (`bin/transcrever-gravacoes.php`, agendado
  via cron do servidor — infraestrutura, não código desta sprint)
  processa as pendentes chamando `GravacaoAudioApplicationService::transcrever()`.
  Evita acoplar a resposta HTTP do navegador à latência de uma API de
  transcrição externa.
- **Primeiro corpo binário do projeto, em ambas as Requests HTTP**:
  `Presentation\Http\Request` (API) passa a decodificar JSON só sob
  demanda (`corpo()`), preservando o corpo bruto em `corpoBinario()`; o
  mesmo padrão é espelhado em `Presentation\Web\Http\Request`. Sem essa
  mudança, o áudio (que não é JSON) quebraria o parsing existente.
  `HttpClientInterface` ganha `postBinario()`/`getBinario()`
  (`BinaryApiResponse`) para a Web repassar/servir esses bytes sem
  nunca falar com `StorageInterface` diretamente — mesma disciplina de
  toda chamada Web → API já existente.
- **Áudio e texto convivem**: o textarea de `/conversa` continua
  funcionando sem nenhuma mudança; a gravação (via `MediaRecorder` no
  navegador, oculta quando o navegador não suporta) é um caminho
  adicional, não uma substituição — decisão do usuário, que precisa
  poder ouvir a sessão para validar o que o sistema escreveu.
- **v1 é upload único, não incremental**: o navegador só envia o áudio
  completo quando o sujeito clica "Encerrar e enviar gravação". Upload
  em pedaços (resiliente a fechar a aba no meio) fica para depois de
  validado o uso real — mesmo padrão de redução de escopo já usado nas
  Sprints 18/20.
- **Suíte completa sem regressão**: 569 testes / 1392 asserções (eram
  532/1318 ao final da Sprint 21), incluindo dois novos duplos de teste
  compartilhados (`StorageStub`/`TranscricaoStub`, mesmo padrão de
  `HttpClientStub`).

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