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
- **Verificação por hash só acontece uma vez por arquivo, não a cada execução.** O Collector369 baixa e compara hash SHA-256 mesmo quando o tamanho remoto já bate com o local, porque lá é um arquivo só, ocasional, com dado financeiro. Aqui a árvore tem milhares de arquivos (`vendor/` sozinho passa de 3800) — repetir download+hash em todos a cada redeploy tornaria o comando impraticavelmente lento. Em vez disso:
  - Arquivo remoto **não existe**: upload para `.tmp_`, confirma tamanho, baixa de volta e compara hash contra o local, só então `ftp_rename` para o nome final — mesma garantia contra corrupção de transferência que o Collector369 tem.
  - Arquivo remoto **já existe com o mesmo tamanho**: tratado como `already_current` sem download — aceita-se esse nível de confiança para código/dependências (ao contrário de dado financeiro, colisão de tamanho com conteúdo diferente em código versionado é um cenário extremamente improvável).
  - Arquivo remoto **já existe com tamanho diferente**: `conflict`, upload abortado, nada é sobrescrito — investigação humana necessária, mesma postura do Collector369.

## 5. Descoberta em produção: a sessão FTP tem limite

Confirmado contra o FTP real (não só teoria): as duas primeiras execuções completas do transporte pararam por volta do mesmo número de comandos FTP (entre ~150 e ~300, contando `size`/`put`/`get`/`rename`/`mkdir`), independente de quais arquivos estavam sendo processados no momento — sintoma de que a conta `u196460065.psycheai` (ou a Hostinger em geral) derruba a sessão depois de um certo volume de comandos/conexões de dados PASV, não por erro de rede aleatório. Com milhares de arquivos (`vendor/` sozinho passa de 3800), uma única sessão persistente do início ao fim sempre estoura esse limite.

Mitigação: `ProductionTransport` reconecta periodicamente (a cada `reconnectEveryFiles` itens processados, padrão 20) em vez de manter uma conexão só. Cada arquivo já processado com sucesso antes de uma queda continua íntegro (o desenho de `.tmp` + rename garante isso); reexecutar o comando depois de uma falha parcial é sempre seguro — os arquivos já publicados batem em tamanho (`already_current`, sem novo upload) e só os que faltaram são reenviados.

## 6. Upload seguro por arquivo

Igual ao Collector369: `.tmp_{nome}` → verifica tamanho → baixa e compara hash → `ftp_rename` para o nome final. `.tmp` órfão de uma execução anterior interrompida é removido antes de cada tentativa. Nenhum arquivo é sobrescrito silenciosamente.

## 7. Idempotência e reexecução

Rodar o comando de novo depois de um deploy já publicado (ou de uma queda de sessão no meio do caminho, ver seção 5) é seguro e rápido: a maioria dos arquivos bate em tamanho (`already_current`), só os que realmente mudaram ou faltaram (normalmente dentro de `app/`, raramente `vendor/`) são reenviados. Não há cron nem gatilho automático — a publicação é sempre uma decisão manual do operador, mesma postura do Collector369.

## 8. Testes

`tests/Unit/Transport/ProductionTransportTest.php`, com `FakeFtpClient` (`tests/Support/FakeFtpClient.php`) — sem rede real. Cobre: upload da árvore incluída, exclusão de `tests/`/`docs/`/`README.md`/`.git`/`.env`, proteção total de `storage/` (arquivos locais ali nunca sobem, mesmo existindo), `already_current`/`conflict` por tamanho, limpeza de `.tmp` órfão, detecção de corrupção pós-upload, retry de conexão, reconexão periódica e callback de progresso.

## 9. O que este documento não cobre

- Criação da conta FTP no hPanel (feita manualmente por Renan — ver histórico de decisão em `investimentos369/docs/arquitetura/Integracao-PsycheAI.md`).
- Preenchimento de `ANTHROPIC_API_KEY`/`OPENAI_API_KEY` (ficam no `.env` do `investimentos369/psycheai/`, não neste `.env`, que só tem as credenciais de transporte).
- Cron do worker de transcrição (`bin/transcrever-gravacoes.php`) — decisão de infraestrutura adiada, fora do escopo deste transporte.

---

**Fim do Documento**
