# Transporte de Produção do PsycheAI

## 1. Contexto

O deploy do PsycheAI sob `investimentos369.com/psycheai` (ver `Integracao-PsycheAI.md` no repositório `investimentos369`) precisa publicar este repositório inteiro (`psycheai-app/` na árvore de produção) — não é um arquivo de saída como Collector369/Sonus AI entregam, é a aplicação completa: `app/`, `bin/`, `config/`, `public/`, `vendor/` (com `composer install --no-dev` já rodado), `composer.json`, `composer.lock`.

## 2. Por que FTPS, não SSH

Mesma descoberta já validada pelo Collector369 (`collector369/docs/arquitetura/Transporte-Producao-Collector369.md`): a conta Hostinger (`u196460065`) não tem SSH — shell `/sbin/nologin`. Não há como clonar o repositório nem rodar `composer install` diretamente no servidor. O caminho é:

1. Build **local**: clonar `psyche-ai`, rodar `composer install --no-dev` aqui mesmo.
2. Publicar via **FTPS explícito**, numa conta FTP dedicada e restrita a um diretório próprio (`u196460065.psycheai`, restrita a `psycheai-app/` — nunca ao `public_html` inteiro), criada manualmente no hPanel.

## 3. Comando oficial

```
php bin/transportar-producao.php
```

Lê `PRODUCTION_FTP_HOST` / `PRODUCTION_FTP_PORT` / `PRODUCTION_FTP_USER` / `PRODUCTION_FTP_PASSWORD` / `PRODUCTION_FTP_REMOTE_PATH` do `.env` local (copiar de `.env.example`) via um loader mínimo embutido no próprio script — o psyche-ai não depende de nenhuma lib de dotenv (em produção só usa `getenv()`, populado pelo front controller do `investimentos369`); este script de terminal é o único lugar do projeto que precisa ler um `.env`, então o loader não virou dependência do projeto inteiro.

## 4. Diferença central para o transporte do Collector369

O Collector369 transporta **um arquivo por provider**. Aqui é uma **árvore inteira de diretórios**, o que muda três coisas no desenho (`app/Transport/ProductionTransport.php`):

- **Caminhos incluídos são uma lista fixa no topo**, não descoberta dinâmica: `app/`, `bin/`, `config/`, `public/`, `vendor/` (recursivos) + `composer.json`/`composer.lock`. Tudo mais na raiz (`tests/`, `docs/`, `README.md`, `.git/`, `.env`) fica de fora — nunca é lido nem considerado.
- **`storage/` nunca é sincronizado.** É onde vive `storage/data/psyche-ai.sqlite` e o áudio gravado (Sprint 22) de sujeitos reais em produção. O transporte só garante que `storage/cache`, `storage/data` e `storage/logs` existem no servidor (cria se não existir, idempotente) — nunca lista, lê ou sobrescreve o que já está lá dentro. Isso é o que resolve o "Pendente #3" (permissão de escrita) do documento de integração: os diretórios passam a existir com o dono correto (a própria conta de hospedagem), e a aplicação cria o SQLite e os arquivos de áudio nela mesma na primeira escrita.
- **Verificação por hash sempre acontece para arquivo remoto já existente** (baixa e compara SHA-256 contra o local), igual ao Collector369 — uma versão anterior pulava esse download quando o tamanho já batia, por performance, mas isso causou um bug real (ver seção 5.2): `vendor/composer/platform_check.php` mudou de conteúdo sem mudar de tamanho e ficou desatualizado silenciosamente. O custo é uma chamada FTP a mais por arquivo já publicado em cada redeploy — aceito, porque a alternativa é confiar cegamente no tamanho.
  - Arquivo remoto **não existe**: upload para `.tmp_`, confirma tamanho, baixa de volta e compara hash contra o local, só então `ftp_rename` para o nome final.
  - Arquivo remoto **já existe com conteúdo idêntico** (hash bate): `already_current`, sem novo upload.
  - Arquivo remoto **já existe com conteúdo diferente**: **sobrescrito**, pela mesma rotina segura de `.tmp` + verificação de hash + rename. Isso é deliberadamente diferente do Collector369, onde essa mesma situação é `conflict` (aborta, exige investigação humana) — lá cada nome de arquivo já embute um timestamp, então uma colisão de nome com conteúdo diferente é uma anomalia real. Aqui os caminhos são fixos (é código versionado em git, não dado com timestamp): conteúdo diferente é só "o arquivo mudou desde o último deploy", o caso normal de uma redeploy. Tratar isso como conflito bloquearia toda atualização de código depois do primeiro deploy — um bug real descoberto na prática (ver seção 5.1) antes de chegar à primeira versão publicada deste documento.

## 5. Descoberta em produção: a sessão FTP tem limite

Confirmado contra o FTP real (não só teoria): as duas primeiras execuções completas do transporte pararam por volta do mesmo número de comandos FTP (entre ~150 e ~300, contando `size`/`put`/`get`/`rename`/`mkdir`), independente de quais arquivos estavam sendo processados no momento — sintoma de que a conta `u196460065.psycheai` (ou a Hostinger em geral) derruba a sessão depois de um certo volume de comandos/conexões de dados PASV, não por erro de rede aleatório. Com milhares de arquivos (`vendor/` sozinho passa de 3800), uma única sessão persistente do início ao fim sempre estoura esse limite.

Mitigação: `ProductionTransport` reconecta periodicamente (a cada `reconnectEveryFiles` itens processados, padrão 20) em vez de manter uma conexão só. Cada arquivo já processado com sucesso antes de uma queda continua íntegro (o desenho de `.tmp` + rename garante isso); reexecutar o comando depois de uma falha parcial é sempre seguro — os arquivos já publicados batem em tamanho (`already_current`, sem novo upload) e só os que faltaram são reenviados.

## 5.1. Bug descoberto no primeiro redeploy real: "conflict" bloqueava atualização de código

A primeira versão deste transporte copiou a semântica de `conflict` do Collector369 ao pé da letra: tamanho remoto diferente do local → aborta, exige investigação humana. Isso só se revelou um bug ao tentar corrigir a incompatibilidade de PHP (seção 4 de `Integracao-PsycheAI.md`) e reenviar um `composer.lock` com conteúdo genuinamente diferente — o transporte reportou `conflict` e recusou a atualizar, porque a única diferença entre os dois casos (dado com timestamp vs. código versionado) não tinha sido considerada na primeira versão. Corrigido removendo o status `conflict`: tamanho remoto diferente agora é sempre tratado como "arquivo mudou, sobrescrever" (mesma rotina segura de `.tmp` + hash + rename), nunca mais um bloqueio.

## 5.2. Segundo bug descoberto: colisão de tamanho não é tão improvável quanto parecia

Depois de corrigir o bug da seção 5.1 (removendo `conflict`), o redeploy do `composer.lock` e do `vendor/` corrigidos ainda deixou a produção quebrada: `/psycheai/` continuava 500, mesmo com a árvore reportando sucesso. Investigação (via um script de diagnóstico temporário, `psycheai/_diag.php` no repositório `investimentos369`, removido depois) revelou que `vendor/composer/platform_check.php` — o arquivo que causa o próprio erro de incompatibilidade de PHP — tinha sido pulado como `already_current`: a versão antiga (exigindo PHP `>= 8.4.1`) e a nova (exigindo PHP `>= 8.2.0`) têm **exatamente o mesmo tamanho em bytes** (`"8.4.1"` e `"8.2.0"` têm o mesmo número de caracteres). A justificativa original de que "colisão de tamanho com conteúdo diferente em código versionado é um cenário extremamente improvável" (seção 4, versão anterior deste documento) se provou errada na prática, no próprio arquivo que motivou a correção anterior. Corrigido voltando a verificar hash sempre, mesmo com tamanho igual — o custo de performance é aceito (ver seção 4).

## 6. Upload seguro por arquivo

Igual ao Collector369: `.tmp_{nome}` → verifica tamanho → baixa e compara hash → `ftp_rename` para o nome final. `.tmp` órfão de uma execução anterior interrompida é removido antes de cada tentativa. Nenhum arquivo é sobrescrito silenciosamente.

## 7. Idempotência e reexecução

Rodar o comando de novo depois de um deploy já publicado (ou de uma queda de sessão no meio do caminho, ver seção 5) é seguro: a maioria dos arquivos bate por hash (`already_current`), só os que realmente mudaram ou faltaram (normalmente dentro de `app/`, raramente `vendor/`) são reenviados — mais lento que uma checagem só por tamanho, mas correto (ver seção 5.2). Não há cron nem gatilho automático — a publicação é sempre uma decisão manual do operador, mesma postura do Collector369.

## 8. Testes

`tests/Unit/Transport/ProductionTransportTest.php`, com `FakeFtpClient` (`tests/Support/FakeFtpClient.php`) — sem rede real. Cobre: upload da árvore incluída, exclusão de `tests/`/`docs/`/`README.md`/`.git`/`.env`, proteção total de `storage/` (arquivos locais ali nunca sobem, mesmo existindo), `already_current` por hash, sobrescrita quando o conteúdo remoto difere, limpeza de `.tmp` órfão, detecção de corrupção pós-upload, retry de conexão, reconexão periódica e callback de progresso.

## 9. O que este documento não cobre

- Criação da conta FTP no hPanel (feita manualmente por Renan — ver histórico de decisão em `investimentos369/docs/arquitetura/Integracao-PsycheAI.md`).
- Preenchimento de `ANTHROPIC_API_KEY`/`OPENAI_API_KEY` (ficam no `.env` do `investimentos369/psycheai/`, não neste `.env`, que só tem as credenciais de transporte).
- Cron do worker de transcrição (`bin/transcrever-gravacoes.php`) — decisão de infraestrutura adiada, fora do escopo deste transporte.

---

**Fim do Documento**
