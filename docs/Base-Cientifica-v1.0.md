# Base Científica v1.0 — Psyche AI

> Sprint 30 — Consolidação Científica v1.0. Documento de certificação oficial: encerra a Fase 1 (Fundação Científica) do PsycheAI, consolidando toda a documentação científica produzida entre a Sprint 25 (Biblioteca Teórica) e a Sprint 29 (Representação Computacional), auditada, corrigida e padronizada nesta Sprint. A partir deste documento, a Base Científica do PsycheAI está oficialmente congelada na versão 1.0 — qualquer alteração de princípio permanente exige o processo descrito em ["Critérios para futuras alterações"](#critérios-para-futuras-alterações) abaixo.
>
> **Nota da Sprint 33**: a expansão da Biblioteca Teórica com o segundo eixo [Biblioteca-Teorica/Fundamentos-Computacionais/](Biblioteca-Teorica/Fundamentos-Computacionais/README.md) (44 documentos) é uma adição estritamente aditiva à Fundamentação Psicanalítica já certificada nesta versão — não altera, revisa ou substitui nenhum dos oito princípios permanentes abaixo, apenas os estende com fundamentação computacional (§9 de [Arquitetura-Cientifica.md](Arquitetura-Cientifica.md#9-fundamentos-computacionais--segundo-eixo-da-base-científica)). Não configura, portanto, abertura da Base Científica v2.0 — as condições completas para essa abertura permanecem as registradas em ["Condições para abertura da Base Científica v2.0"](#condições-para-abertura-da-base-científica-v20).

## Objetivo científico do PsycheAI

O PsycheAI nasce inspirado no projeto freudiano de construir uma Psicologia Científica. A hipótese de trabalho da plataforma é que tecnologias computacionais atuais permitem registrar, organizar e analisar longitudinalmente regularidades do discurso humano descritas pela teoria psicanalítica, preservando rigorosamente a distinção entre observação computacional e interpretação clínica. O sistema não pretende substituir o analista nem produzir interpretações automáticas. Seu propósito é construir uma base observacional digital, rastreável e auditável, capaz de apoiar a investigação científica do discurso e oferecer novas ferramentas para o trabalho clínico (fonte: [Documento-Mestre.md §6.0](Documento-Mestre.md#60-objetivo-científico-do-psycheai)).

O objeto de pesquisa é a organização computacional do discurso, com foco na observação longitudinal das recorrências discursivas ([Documento-Mestre.md §6.2](Documento-Mestre.md#62-objeto-de-pesquisa)). A hipótese central: é possível identificar recorrências estruturais no discurso de um sujeito ao longo do tempo, sem que isso implique a representação ou a identificação automática de significantes — tarefa que permanece exclusiva do analista ou do próprio sujeito ([Documento-Mestre.md §6.3](Documento-Mestre.md#63-hipótese-central)).

## Arquitetura científica definitiva

A arquitetura científica do PsycheAI se organiza em uma cadeia de rastreabilidade obrigatória, sem elos ausentes, verificada nesta Sprint como idêntica em todo lugar onde é reproduzida por extenso:

```
Biblioteca Teórica → Modelo Observacional → Modelo Relacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

| Camada | O que resolve | Onde vive |
|---|---|---|
| **Biblioteca Teórica** | De onde vem o conceito — autores, obras, fundamentação bibliográfica (Fundamentação Psicanalítica); infraestrutura técnica de extração/qualificação do dado (Fundamentação Computacional, Sprint 33) | [Biblioteca-Teorica/](Biblioteca-Teorica/README.md) — 245 documentos + 44 ([Fundamentos-Computacionais/](Biblioteca-Teorica/Fundamentos-Computacionais/README.md)) |
| **Modelo Observacional** | O que, do discurso registrado, é fenômeno observável | [Modelo-Observacional/](Modelo-Observacional/README.md) + [Modelo-Observacional.md](Modelo-Observacional.md) — 24 documentos |
| **Modelo Relacional** | Como os 21 conceitos canônicos se relacionam entre si | [Modelo-Relacional/](Modelo-Relacional/README.md) — 37 documentos |
| **Representação Computacional** | Como uma observação chega a ser vista pelo Analista/Sujeito | [Representacao-Computacional/](Representacao-Computacional/README.md) — 14 documentos |
| **Ontologia** | Vocabulário conceitual fixado | [Ontologia-Freud.md](Ontologia-Freud.md) / [Ontologia-Lacan.md](Ontologia-Lacan.md) |
| **Modelo Computacional** | Seção "Aplicação Computacional" de cada Conceito | dentro de [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/) |
| **Implementação** | Código real | `app/` |
| **Testes** | Suíte automatizada | `tests/` |

A **ECO — Estrutura Computacional de Observação** ([ECO/](ECO/README.md) — 10 documentos) ocupa, nesta cadeia, o lugar do modo de enunciação: a camada que transforma o que os motores trazem em pergunta dirigida ao sujeito, nunca em afirmação. Não é uma nona camada da cadeia de rastreabilidade — é a interface conversacional que a atravessa transversalmente, do lado do Sujeito.

A arquitetura técnica (camadas de Domínio/Aplicação/Infraestrutura/Apresentação, componentes, dependências) é tratada por [Arquitetura.md](Arquitetura.md) e [Arquitetura-Camadas.md](Arquitetura-Camadas.md) — documentos distintos desta Base Científica, que trata exclusivamente da fundamentação científica e dos princípios permanentes.

## Princípios permanentes

Nenhuma sprint futura pode contradizer os princípios abaixo sem revisão explícita e registrada:

1. **Cadeia de rastreabilidade obrigatória** — nenhuma camada pode ser pulada ([Arquitetura-Cientifica.md §1](Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória)).
2. **Separação de interface entre Sujeito e Analista** — o Sujeito nunca visualiza significantes, recorrências, circuito pulsional, hipóteses, classificações, escrita lacaniana ou qualquer estrutura produzida pelos motores; o Analista pode, como apoio à escuta clínica, nunca como diagnóstico automático ([Arquitetura-Cientifica.md §2](Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista); Regra 11, [Regras-Dominio.md](Regras-Dominio.md)).
3. **A escrita lacaniana pertence ao analista** — nunca compõe a resposta ao Sujeito ([Arquitetura-Cientifica.md §3](Arquitetura-Cientifica.md#3-a-escrita-lacaniana-pertence-ao-analista)).
4. **Princípio da Neutralidade Observacional** — o PsycheAI não mede sucesso pelo desfecho clínico; casos concluídos, interrompidos, abandonados ou inconclusivos têm igual valor científico ([Arquitetura-Cientifica.md §4](Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional)).
5. **Identidade permanente da ECO** — única interface conversacional do Sujeito; nunca conversa diretamente com os motores ([Arquitetura-Cientifica.md §5](Arquitetura-Cientifica.md#5-eco--identidade-da-interface-conversacional)).
6. **Modelo oficial de Representação Computacional** — cinco atributos obrigatórios (observacional, rastreável, auditável, reproduzível, fundamentada) e quatro proibições permanentes (interpretar, diagnosticar, concluir, produzir hipótese clínica) — [Arquitetura-Cientifica.md §6](Arquitetura-Cientifica.md#6-representação-computacional--modelo-oficial); [Representacao-Computacional/Principios.md](Representacao-Computacional/Principios.md).
7. **As onze Regras do Domínio** ([Regras-Dominio.md](Regras-Dominio.md)) — preservação integral do discurso, continuidade histórica, rastreabilidade, reprodutibilidade, neutralidade clínica, separação entre observação e interpretação; nenhum algoritmo produz diagnóstico (Regra 9); toda interpretação é responsabilidade exclusiva do analista (Regra 10).
8. **Os dez limites permanentes da ECO** ([ECO/Limites-da-ECO.md](ECO/Limites-da-ECO.md)) e sua missão de sustentar um espaço de associação livre, sem jamais interpretar, aconselhar, diagnosticar, conduzir a respostas esperadas ou ocupar o lugar do analista ([ECO/Principios.md](ECO/Principios.md)).

## Hipóteses científicas

- **Hipótese central**: recorrências estruturais no discurso de um sujeito, ao longo do tempo, são identificáveis computacionalmente sem que isso implique identificação automática de significante ([Documento-Mestre.md §6.3](Documento-Mestre.md#63-hipótese-central)).
- **Questão de pesquisa em aberto, sem resposta definitiva**: como representar computacionalmente um significante sem reduzi-lo a uma simples palavra ([Documento-Mestre.md §6.6](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto); [Ontologia-Lacan.md §5](Ontologia-Lacan.md#5-limites)) — nenhuma das 21 concepções canônicas resolve essa questão; permanece registrada como limite estrutural, não como lacuna de implementação.
- **Hipótese de equivalência metodológica**: o método socrático (maiêutica) é um modo de enunciação computacionalmente sustentável para provocar associação livre sem entregar conteúdo interpretativo — validado em produção desde a Sprint 17, refinado nas Sprints 20-24, com identidade oficial (ECO) desde a Sprint 28.

## Limites éticos

- **Não substituição do profissional**: o PsycheAI não substitui psicólogos, psicanalistas ou qualquer profissional de saúde mental ([Documento-Mestre.md §5](Documento-Mestre.md#5-princípios-éticos)).
- **Privacidade e confidencialidade** como padrão máximo de proteção de dados sensíveis.
- **Transparência** sobre limitações e natureza assistiva do sistema.
- **Responsabilidade** diante de pessoas em situação de vulnerabilidade.
- **Rigor técnico e teórico** — nenhuma aplicação de conceito psicanalítico sem fundamentação registrada na Biblioteca Teórica.
- **Separação Sujeito/Analista** e **exclusividade lacaniana ao Analista** (ver Princípios Permanentes 2-3).
- **O sistema nunca interpreta, nunca atribui significado, nunca substitui a escuta clínica** ([Documento-Mestre.md §6.5](Documento-Mestre.md#65-limites-do-sistema)).
- **Toda interpretação é exclusiva do analista ou do próprio sujeito** (Regra 10, [Regras-Dominio.md](Regras-Dominio.md)).

## Estrutura documental

```
docs/
├── Documento-Mestre.md              — fundação institucional + modelo teórico (§6-7)
├── Arquitetura-Cientifica.md        — princípios científicos permanentes consolidados
├── Arquitetura.md / Arquitetura-Camadas.md — arquitetura técnica
├── Ontologia-Freud.md (10 conceitos) / Ontologia-Lacan.md (11 conceitos)
├── Modelo-Computacional-Discurso.md — estrutura de dados do discurso registrado
├── Modelo-Observacional.md          — princípios gerais da observação
├── Regras-Dominio.md                — 11 regras do domínio
├── Roadmap.md                       — histórico de sprints
├── Base-Cientifica-v1.0.md          — este documento
│
├── Biblioteca-Teorica/   (245 docs) — autores, obras, 21 Conceitos canônicos
│   └── Fundamentos-Computacionais/ (44 docs, Sprint 33) — segundo eixo da Base Científica
├── Modelo-Observacional/ (24 docs)  — fenômeno observável, 1:1 por conceito
├── Modelo-Relacional/    (37 docs)  — relações, matrizes, grafos científicos
├── Representacao-Computacional/ (14 docs) — 8 representações + princípios + interfaces
└── ECO/                  (10 docs) — identidade, método, ética, limites, interfaces
```

**351 documentos markdown** no total em `docs/` nesta certificação (Sprint 30; 350 na cadeia científica + arquitetura técnica; 1 documento operacional de infraestrutura em `docs/architecture/`, fora do escopo desta certificação). **A partir da Sprint 33**, mais 53 documentos markdown (README + Modelo-de-Documento + Índice de Tópicos + 6 READMEs de categoria + 44 documentos de tópico) foram adicionados em [Biblioteca-Teorica/Fundamentos-Computacionais/](Biblioteca-Teorica/Fundamentos-Computacionais/README.md) — adição estritamente aditiva, fora do escopo desta certificação de v1.0 (ver nota no topo deste documento).

## Cobertura da documentação

Auditoria completa realizada nesta Sprint, por três frentes de trabalho independentes cobrindo a totalidade dos 351 documentos, complementadas por verificação mecânica de 3.895 links internos.

### Cobertura por conceito — 21/21/21/21

Os 21 conceitos canônicos (10 freudianos + 11 lacanianos, [Ontologia-Freud.md §3](Ontologia-Freud.md#3-conceitos-fundamentais) + [Ontologia-Lacan.md §3](Ontologia-Lacan.md#3-conceitos-fundamentais)) têm cobertura **completa e verificada byte-a-byte** nas quatro camadas:

| Camada | Cobertura |
|---|---|
| Biblioteca Teórica (`Conceitos/`) | 21/21 — Blocos 1 (Metadados), 2 (Aplicação Computacional) e 3 (Representação Computacional) presentes em todos |
| Modelo Observacional (`Conceitos/`) | 21/21 — template fixo de 13 pontos, sem desvio estrutural |
| Modelo Relacional (`Conceitos/`) | 21/21 — template fixo de 6 pontos, sem desvio estrutural |
| Representação Computacional | por tipo de representação (não por conceito) — 8 representações cobrindo todos os fenômenos hoje observáveis; ver [Representacao-Computacional/Evidencias.md](Representacao-Computacional/Evidencias.md) |

### Auditoria de status "Catalogado"/"A verificar"

229 documentos de Obra/Autor catalogados na Biblioteca Teórica: 205 "Catalogado", 24 "A verificar". Nenhum valor fora desse vocabulário fechado encontrado em nenhum dos 245 arquivos.

### Zero links quebrados

3.895 links internos verificados em 351 arquivos — **zero arquivo inexistente, zero âncora sem correspondência**.

## Estado atual da implementação

Auditado contra o código real em `app/` nesta data. Classificação em quatro estados, conforme exigido por esta Sprint:

| Componente | Estado | Evidência |
|---|---|---|
| Discourse Engine (organização do discurso, sem interpretar) | **Implementado** | Sprint 14, `CicloDeObservacaoService`/`GeradorObservacoes` |
| Motor Freud — detecção de recorrência | **Implementado** | Sprint 15, `DetectorRecorrencias` |
| Motor Freud — classificação estrutural via LLM | **Implementado** | revisão pós-Sprint 17, `ClassificadorFreudianoLLM`/`TipoFormacaoFreudiana` |
| Motor Lacan — reclassificação de Metonímia | **Implementado** | Sprint 16, `ReclassificadorLacaniano::reclassificar()`/`reclassificarComTrajeto()` |
| Motor Lacan — reclassificação de Metáfora (por ponte com Motor Freud) | **Implementado** | revisão pós-Sprint 17, `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()` — status corrigido nesta Sprint 30 em 5 documentos que o descreviam incorretamente como nunca produzido |
| Circuito de recorrências (nomeado "Circuito Pulsional" nas matrizes, sem operacionalizar Pulsão) | **Implementado** | revisão pós-Sprint 16, `DetectorRecorrencias::detectarCircuito()` |
| Grafo do Circuito/Trajeto (D3) | **Implementado** | Sprint 19, `GrafoCircuitoViewModel` |
| Timeline / Memória Longitudinal / Indicadores | **Implementado** | Sprints 8/13 |
| ECO (interface conversacional, método socrático) | **Implementado** | Sprint 12, refinado 17/20/22-24, identidade oficial Sprint 28 |
| Demais estruturas lacanianas (cadeia significante, Outro, Falta, Objeto a, RSI, desejo lacaniano) | **Especificado** | fundamentação teórica completa na Biblioteca Teórica; sem representação computacional própria — questão de pesquisa em aberto para o Significante |
| Quatro dos cinco grafos científicos (Freud, Lacan, Integrado, Conceitos) | **Especificado** | [Modelo-Relacional/Grafos/](Modelo-Relacional/Grafos/README.md) — nós, arestas e propriedades topológicas documentados, sem estrutura de dados real |
| Grafo temporal, grafo de recorrências (dois dos cinco tipos de [Representacao-Computacional/Grafos.md](Representacao-Computacional/Grafos.md)) | **Planejado** | especificação nova desta Sprint 29, sem componente algum |
| "Status do Caso" (sete valores observacionais) | **Planejado** | registrado como conceito no Modelo Observacional (Sprint 26); decisão de representação no Domínio/API ainda não tomada |
| Histórico próprio do Sujeito, consentimentos, edição de perfil | **Planejado** | especificados em [ECO/Interface-Sujeito.md](ECO/Interface-Sujeito.md), sem tela própria |
| "Mudanças" e "Encerramentos" (Timeline/Circuitos) | **Planejado** | sem componente que compare conteúdo entre sessões ou detecte ausência prolongada |

Nenhuma funcionalidade futura foi apresentada como implementada em nenhum documento desta Base — auditoria confirmada pelas três frentes de trabalho desta Sprint.

## Auditoria científica — resumo executivo

Auditoria realizada por três frentes independentes (Biblioteca Teórica; Modelo Observacional + Modelo Relacional; ECO + Ontologias + núcleo institucional), complementada por verificação mecânica de cobertura, terminologia e links. Total: **2 achados bloqueantes, 7 moderados, 8 menores, 3 cosméticos** — todos os bloqueantes e moderados corrigidos nesta Sprint; os cosméticos remanescentes (formato do diagrama da cadeia em ASCII vertical vs. horizontal; nomenclatura "Desejo (Freud)" vs. "Desejo" nos títulos de seção das Ontologias — ambos sem ambiguidade real, já desambiguados no corpo dos documentos) foram avaliados e mantidos por não representarem risco de leitura equivocada.

### Correções aplicadas nesta Sprint

- **Status da Metáfora corrigido em 5 documentos** ([Biblioteca-Teorica/Conceitos/metafora.md](Biblioteca-Teorica/Conceitos/metafora.md), [Modelo-Observacional/Conceitos/metafora.md](Modelo-Observacional/Conceitos/metafora.md), [Modelo-Observacional/README.md](Modelo-Observacional/README.md), [Modelo-Relacional/Conceitos/metafora.md](Modelo-Relacional/Conceitos/metafora.md), [ECO/Interface-Analista.md](ECO/Interface-Analista.md)) — todos afirmavam que a reclassificação lacaniana de Metáfora nunca era produzida na prática; `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()`, em produção desde a revisão pós-Sprint 17 e alcançada por `ObservacaoApplicationService::consultarCircuito()`, contradiz essa afirmação. Corrigido para refletir que Metáfora é produzida por reclassificação indireta (ponte com o Motor Freud), nunca por observação direta.
- **Contradição cross-camada em Transferência** — Modelo Relacional negava componentes (`Sessao`, `ContextoConversaDTO`, ECO) que o Modelo Observacional, uma camada antes na cadeia, já documentava. Reconciliado a favor da versão mais detalhada e auditada.
- **Contradição em Objeto a** ("Validação do analista": Sim vs. "Não se aplica") — padronizado para "Sim", alinhado aos demais conceitos de limite absoluto (Significante, Inconsciente).
- **Três erros aritméticos nos grafos científicos**: `Grafo-Lacan.md` contava 17 arestas onde a própria lista somava 18; `Grafo-Integrado.md` herdava a inconsistência (22+17+11=50 ≠ 51 anunciado); `Grafo-Motores.md` atribuía o maior grau a "Interface do Analista e Memória Discursiva (6 cada)" quando a contagem real é Memória Discursiva=9, Motor Freud=9, Interface do Analista=7. Todos corrigidos e reconciliados entre si.
- **Elo ausente na cadeia de rastreabilidade**: `Biblioteca-Teorica/Como-os-Motores-Usam-a-Biblioteca.md` reproduzia a cadeia sem "Modelo Relacional" (defasada desde a Sprint 25); e os 21 documentos de `Modelo-Relacional/Conceitos/` descreviam a si mesmos como situados "entre o Modelo Observacional e a futura Ontologia computacional", omitindo inteiramente a Representação Computacional (defasado desde a Sprint 27, nunca atualizado após a Sprint 29). Ambos corrigidos — o segundo via correção em lote nos 21 arquivos.
- **Nomenclatura ECO/"Modo Socrático"**: seis ocorrências estruturais (Matrizes, Grafos, dois documentos de Repetição) padronizadas para "ECO", com nota histórica. Nos 229 documentos de Obra/Autor da Biblioteca Teórica, gerados por script (`_gerador/*.php`) a partir de dados fixados na Sprint 25, "Modo Socrático" foi mantido como valor de campo — decisão registrada, não lacuna — com nota de equivalência adicionada em `Biblioteca-Teorica/README.md` e `Modelo-de-Documento.md`.
- **Terminologia "Desejo" sem qualificador** em 3 documentos (pulsao.md, ato-falho.md, sonhos.md) — padronizado para "Desejo (Freud)".
- **Terminologia "Ontologia computacional"** (nome de etapa divergente do canônico "Ontologia") corrigida em 22 documentos (`Representacao-Computacional/README.md` + 21 `Modelo-Relacional/Conceitos/*.md`, na mesma correção do elo ausente).
- **Cabeçalho de tabela sem "do"** ("Interface Analista"/"Interface Sujeito") em `Matrizes/Motor-x-Conceito.md` — padronizado para "Interface do Analista"/"Interface do Sujeito".
- **Caveat de não-operacionalização do "Circuito Pulsional"** ausente em `Modelo-Observacional/Conceitos/pulsao.md` — adicionado, alinhado ao mesmo caveat já presente em `Modelo-Relacional/Conceitos/pulsao.md`.
- **Divergência de completude entre `Representacao-Computacional/Interface-Sujeito.md` e `ECO/Interface-Sujeito.md`** — a primeira omitia o estado de implementação (Implementado/Não implementado/Parcial) de cada item; adicionado.

### Achados não corrigidos, aceitos com nota

Divergência bibliográfica na "Obra de origem" entre 5 documentos de `Modelo-Relacional/Conceitos/` (falta, registro-imaginario, registro-real, registro-simbolico, outro) e a bibliografia de referência de `Ontologia-Lacan.md §6` — o próprio §6 se autodeclara "sem ainda consolidar". Não é contradição factual (ambas as fontes são obras reais de Lacan sobre o mesmo conceito), apenas ausência de uma fonte bibliográfica única de verdade. Fica registrada como pendência explícita para a Base Científica v2.0.

## Critérios para futuras alterações

Qualquer alteração aos **Princípios permanentes** listados acima exige:

1. Decisão explícita do usuário, registrada por escrito (mesmo padrão já usado para o Princípio da Neutralidade Observacional e para a identidade da ECO).
2. Atualização coordenada de todos os documentos que reproduzem o princípio, na mesma sessão de trabalho — nunca uma alteração parcial que deixe documentos contraditórios.
3. Revalidação de links internos e da cadeia de rastreabilidade após a alteração.
4. Registro da alteração no Roadmap, com justificativa.

Alterações que **não** exigem esse processo — porque não tocam princípio permanente: adição de novos conceitos observacionais (desde que sigam o Modelo de Documento já fixado), extensão de motores já existentes dentro dos limites já documentados, novas representações computacionais que sigam os cinco atributos obrigatórios, correções de auditoria (como as desta Sprint).

## Condições para abertura da Base Científica v2.0

A Base Científica v1.0 permanece a referência vigente até que **todas** as condições abaixo sejam satisfeitas, decisão que cabe exclusivamente ao usuário:

1. **Resolução da questão de pesquisa em aberto** ([Documento-Mestre.md §6.6](Documento-Mestre.md#66-questão-de-pesquisa-em-aberto)) — como representar computacionalmente um significante sem reduzi-lo a uma palavra — ou decisão explícita de que a questão permanece aberta indefinidamente e não bloqueia a v2.0.
2. **Consolidação bibliográfica única** para os 5 conceitos com divergência de "Obra de origem" identificada nesta Sprint (ver "Achados não corrigidos" acima).
3. **Decisão de arquitetura sobre o "Status do Caso"** — onde e como representá-lo no Domínio, hoje apenas um conceito observacional sem estrutura de dados.
4. Qualquer mudança que introduza uma nova camada na cadeia de rastreabilidade, um novo motor conceitual, ou revise qualquer um dos Princípios Permanentes listados nesta Sprint.
5. Nova auditoria completa, no mesmo formato desta Sprint 30, imediatamente antes da abertura formal da v2.0.

Até lá, toda sprint documental ou de implementação deve se referir a este documento como a Base Científica vigente.

## Fase 1 — Fundação Científica: encerrada

Com a conclusão desta Sprint, a **Fase 1 — Fundação Científica do PsycheAI** está oficialmente encerrada: Documento Mestre, Ontologias, Modelo Observacional, Modelo Relacional, Representação Computacional, ECO e esta certificação consolidam um corpo científico auditado, rastreável e internamente consistente.

Inicia-se a **Fase 2 — Desenvolvimento Experimental do PsycheAI**: sprints futuras poderão implementar, testar e expandir os motores já fundamentados nesta Base, sempre respeitando a cadeia de rastreabilidade e os princípios permanentes aqui certificados. Nenhuma implementação experimental da Fase 2 pode contradizer esta Base sem reabrir, explicitamente, o processo descrito em "Critérios para futuras alterações".

## Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura-Cientifica.md](Arquitetura-Cientifica.md)
- [Biblioteca-Teorica/README.md](Biblioteca-Teorica/README.md)
- [Biblioteca-Teorica/Fundamentos-Computacionais/README.md](Biblioteca-Teorica/Fundamentos-Computacionais/README.md)
- [Modelo-Observacional/README.md](Modelo-Observacional/README.md)
- [Modelo-Relacional/README.md](Modelo-Relacional/README.md)
- [Representacao-Computacional/README.md](Representacao-Computacional/README.md)
- [ECO/README.md](ECO/README.md)
- [Ontologia-Freud.md](Ontologia-Freud.md)
- [Ontologia-Lacan.md](Ontologia-Lacan.md)
- [Regras-Dominio.md](Regras-Dominio.md)
- [Roadmap.md](Roadmap.md)
