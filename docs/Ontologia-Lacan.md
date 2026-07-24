# Ontologia Lacan — Psyche AI

> Versão 0.1 — Sprint 3 (Ontologia Lacan)
> Este documento é exclusivamente ontológico: organiza conceitualmente o vocabulário lacaniano que fundamentará o futuro Lacan Engine. Não há, nesta fase, algoritmos, regras de negócio, banco de dados, classes, código, APIs ou especificação do Lacan Engine / Discourse Engine. Não há interpretações clínicas.

## 1. Objetivo da ontologia

Esta ontologia tem por finalidade organizar, de forma computacionalmente estruturável, os conceitos fundamentais da obra de Jacques Lacan que o Psyche AI adota como estrutura de leitura — o **Lacan Engine**, já referido em [Arquitetura.md](Arquitetura.md#4-visão-arquitetural-de-longo-prazo--motores-conceituais).

Conforme estabelecido em [Documento-Mestre.md (6)](Documento-Mestre.md#6-modelo-teórico-fundamental), a arquitetura do sistema respeita a distinção entre os conceitos fundamentais desenvolvidos por Freud e a estrutura de leitura proposta por Lacan. A [Ontologia-Freud.md](Ontologia-Freud.md) já organizou o primeiro polo, definindo **o que** o sistema observa. Esta ontologia trata do segundo polo: define **como** esse material é lido estruturalmente.

**Esta ontologia complementa, reorganiza e amplia a Ontologia Freud — não a substitui.** Lacan não abandona os conceitos freudianos; ele os relê sob uma chave estrutural, a partir da hipótese de que o inconsciente é estruturado como uma linguagem. Cada conceito lacaniano tratado aqui será, portanto, explicitamente relacionado ao conceito freudiano correspondente já registrado em [Ontologia-Freud.md](Ontologia-Freud.md), explicitando continuidade, deslocamento ou reformulação — nunca substituição silenciosa.

O propósito desta ontologia é, portanto:

- fixar um vocabulário conceitual comum, fiel à obra de Lacan, para os termos que o projeto usará em todas as fases futuras;
- explicitar a função de cada conceito dentro da teoria lacaniana;
- explicitar as relações entre esses conceitos;
- explicitar como cada conceito reorganiza o conceito correspondente da Ontologia Freud;
- indicar, para cada conceito, por que ele importa para o modelo computacional do discurso já estabelecido em [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md) — sem, em nenhum momento, definir como esse conceito seria detectado, inferido ou operacionalizado.

Esta ontologia é vocabulário e organização conceitual. Não é especificação técnica, e não é o Lacan Engine.

## 2. Escopo teórico

A obra de Lacan se estende por mais de vinte e cinco anos de seminários e escritos, com sucessivas reformulações — do "retorno a Freud" centrado na linguagem (anos 1950) à formalização crescente dos registros Real, Simbólico e Imaginário e da lógica do objeto a e do gozo (anos 1960 e 1970). Delimitar um escopo é necessário para que esta ontologia seja tratável.

Nesta versão, a ontologia considera os onze conceitos listados no capítulo 3, tomados prioritariamente em sua formulação estrutural do chamado "retorno a Freud" — o significante, a cadeia significante, a metáfora e a metonímia, os três registros (RSI) e a lógica da falta, do Outro, do objeto a e do desejo. Esta ontologia **não** pretende, nesta versão, cobrir a totalidade da obra de Lacan, nem suas formalizações posteriores (matemas, nós borromeanos, teoria dos discursos, gozo em suas distinções ulteriores), que poderão ser objeto de expansões futuras.

Esta é uma construção **incremental**, na mesma lógica já adotada em [Ontologia-Freud.md (2)](Ontologia-Freud.md#2-escopo-teórico): novos conceitos, refinamentos e desdobramentos poderão ser adicionados em sprints futuras, sem que isso invalide o que é estabelecido nesta versão. Toda expansão futura deve preservar a coerência com o que aqui é definido, ou justificar e registrar explicitamente sua revisão.

## 3. Conceitos fundamentais

### 3.1 Significante

**Definição conceitual.** Unidade da ordem simbólica que representa o sujeito para outro significante — não para outro sujeito, e não por corresponder a uma palavra isolada ou a um conteúdo positivo. Seu valor é diferencial e posicional: um significante só significa em relação aos demais significantes da cadeia, nunca isoladamente.

**Função na teoria.** É a base da tese lacaniana de que o inconsciente é estruturado como uma linguagem: desloca o eixo da análise do conteúdo (o que uma palavra "quer dizer") para a posição relacional de cada elemento na cadeia.

**Relação com os demais conceitos.** Só existe em cadeia significante; produz efeitos de sentido pela metáfora e pela metonímia; articula-se ao Outro (tesouro dos significantes de onde é extraído); ao representar o sujeito, deixa sempre um resto — o que fundamenta a falta e o objeto a.

**Relação com a Ontologia Freud.** Relê, sob a categoria estrutural de significante, o que Freud tratava como representante psíquico e traço mnêmico (ver [Ontologia-Freud.md, Inconsciente e Recalque](Ontologia-Freud.md#31-inconsciente)): o que é recalcado, para Lacan, é sempre da ordem do significante.

**Importância para o modelo computacional.** É aqui que o limite mais central deste projeto se aplica diretamente: o Psyche AI **não identifica significantes**. Como já registrado em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema), o significante não é uma palavra — é uma representação que só o sujeito que diz pode confirmar. O sistema pode, no máximo, registrar estruturas discursivas candidatas; jamais atribuir a elas o estatuto de significante.

### 3.2 Cadeia significante

**Definição conceitual.** Sequência de significantes articulados entre si pelas leis da linguagem, na qual o valor de cada elemento depende de sua posição relativa aos demais. O sentido, nessa cadeia, surge de forma retroativa — um elemento posterior pode reconfigurar o valor de um elemento anterior.

**Função na teoria.** É o modelo estrutural do inconsciente como linguagem: não um depósito de conteúdos, mas um encadeamento cujo sentido nunca está fixado de antemão.

**Relação com os demais conceitos.** É o campo onde o significante ganha valor; metáfora e metonímia são as operações que a movimentam; o Outro é o tesouro do qual ela se extrai.

**Relação com a Ontologia Freud.** Reformula estruturalmente a associação livre e o encadeamento de representações já observado clinicamente por Freud (ver [Ontologia-Freud.md, Inconsciente](Ontologia-Freud.md#31-inconsciente)), lendo-o não como fluxo psicológico associativo, mas como efeito de uma lei estrutural da linguagem.

**Importância para o modelo computacional.** Fundamenta teoricamente por que o [Modelo-Computacional-Discurso.md (4)](Modelo-Computacional-Discurso.md#4-estrutura-do-discurso) trata o discurso como sequência organizada de Eventos Discursivos, e não como unidades isoladas — mas o sistema organiza sequências de eventos, nunca uma cadeia significante propriamente dita, cuja existência só o sujeito confirma.

### 3.3 Metáfora

**Definição conceitual.** Operação de substituição de um significante por outro, produzindo um efeito de sentido novo; o significante substituído não desaparece, mas passa a atuar de forma latente.

**Função na teoria.** Para Lacan, o sintoma tem estrutura de metáfora: é o modelo formal para pensar como um elemento substitui outro na cadeia significante, gerando sentido.

**Relação com os demais conceitos.** Opera sobre a cadeia significante; é uma das duas leis fundamentais de seu funcionamento, ao lado da metonímia.

**Relação com a Ontologia Freud.** É a releitura estrutural da condensação (*Verdichtung*) descrita no trabalho do sonho (ver [Ontologia-Freud.md, Sonhos](Ontologia-Freud.md#38-sonhos)): Lacan formaliza a condensação freudiana como operação de substituição significante.

**Importância para o modelo computacional.** Fornece base teórica — não implementação — para que substituições e recorrências temáticas no discurso registrado sejam tratadas como candidatas a estrutura discursiva, sempre como candidatas, nunca como metáforas confirmadas.

### 3.4 Metonímia

**Definição conceitual.** Operação de deslizamento do sentido por contiguidade: um significante remete a outro por proximidade na cadeia, sem substituição, mantendo o sentido em deslocamento contínuo.

**Função na teoria.** É o modelo formal do desejo como deslizamento metonímico — o desejo se desloca de significante em significante, de objeto em objeto, sem se fixar plenamente em nenhum (ver 3.11).

**Relação com os demais conceitos.** Opera sobre a cadeia significante, ao lado da metáfora; sustenta a lógica do desejo e sua relação com a falta.

**Relação com a Ontologia Freud.** É a releitura estrutural do deslocamento (*Verschiebung*) descrito no trabalho do sonho (ver [Ontologia-Freud.md, Sonhos](Ontologia-Freud.md#38-sonhos)).

**Importância para o modelo computacional.** Fundamenta teoricamente o registro de deslocamentos e sequências de associação por proximidade no discurso ao longo do tempo, complementando o eixo relacional e temporal já definido em [Modelo-Computacional-Discurso.md (4 e 5)](Modelo-Computacional-Discurso.md#4-estrutura-do-discurso).

### 3.5 Registro Simbólico

**Definição conceitual.** Ordem da linguagem, da lei e da estrutura significante — o campo em que o sujeito se inscreve através de uma linguagem que o precede e o determina.

**Função na teoria.** Um dos três registros (Real, Simbólico, Imaginário — RSI) através dos quais Lacan pensa a experiência humana; é o registro da cadeia significante, do Outro e da fala.

**Relação com os demais conceitos.** Articula-se com o Imaginário (identificação especular) e com o Real (o que resiste à simbolização); é o registro por excelência em que a cadeia significante opera.

**Relação com a Ontologia Freud.** Reorganiza estruturalmente o aparelho psíquico freudiano — o inconsciente descrito por Freud (ver [Ontologia-Freud.md, Inconsciente](Ontologia-Freud.md#31-inconsciente)) é relido como efeito da ordem simbólica.

**Importância para o modelo computacional.** O discurso registrado — objeto do sistema, conforme [Modelo-Computacional-Discurso.md (2)](Modelo-Computacional-Discurso.md#2-objeto-computacional-do-sistema) — pertence, por definição, ao registro Simbólico: é linguagem fixada. O sistema só tem acesso a este registro.

### 3.6 Registro Imaginário

**Definição conceitual.** Ordem da imagem, da identificação e da relação especular com o semelhante, fundada na experiência do estádio do espelho, em que o eu se constitui a partir de uma imagem antecipada e unificada de si.

**Função na teoria.** Campo das identificações e das relações duais eu-outro, frequentemente fonte de ilusões de completude e de mal-entendidos.

**Relação com os demais conceitos.** Contrasta com o Simbólico (lei, diferença) e é atravessado por ele; o Real escapa a ambos.

**Relação com a Ontologia Freud.** Relê o narcisismo freudiano e a formação do eu como fenômeno fundamentalmente especular, e não apenas como investimento libidinal.

**Importância para o modelo computacional.** Reforça um limite explícito: o sistema não tem acesso a relações especulares, identificações ou fenômenos do registro Imaginário — apenas ao registro Simbólico do discurso registrado.

### 3.7 Registro Real

**Definição conceitual.** O que escapa à simbolização e à imagem — aquilo que resiste à representação, frequentemente associado ao trauma, ao impossível e ao que insiste em retornar ao mesmo lugar.

**Função na teoria.** O Real é o limite de qualquer sistema simbólico ou imaginário; não se confunde com "a realidade", mas designa o que nenhuma linguagem consegue capturar por inteiro.

**Relação com os demais conceitos.** Articula-se ao objeto a, resto que a operação significante não consegue capturar; é o que causa a insistência do sintoma.

**Relação com a Ontologia Freud.** Relê a pulsão de morte e o "além do princípio do prazer" (ver [Ontologia-Freud.md, Repetição](Ontologia-Freud.md#39-repetição)) como pontos em que a teoria freudiana já tocava o Real, antes de sua formalização por Lacan.

**Importância para o modelo computacional.** Estabelece um limite absoluto: por definição, o Real é o que nenhum sistema baseado em registro discursivo — necessariamente simbólico — pode capturar. Este conceito fundamenta formalmente por que o sistema jamais afirma ter acesso ao inconsciente ou a estados mentais ocultos, conforme [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema).

### 3.8 Outro (grande Outro)

**Definição conceitual.** Lugar da linguagem e do código, tesouro dos significantes que preexiste ao sujeito e a partir do qual o sujeito se constitui — distinto do "outro" especular, imaginário. É também o lugar de onde é endereçada ao sujeito a pergunta por seu desejo.

**Função na teoria.** Fundamenta a alienação constitutiva do sujeito na linguagem: o sujeito fala a partir de um lugar — o Outro — que não é seu.

**Relação com os demais conceitos.** É o tesouro do qual a cadeia significante se extrai; o desejo do sujeito se estrutura em relação ao desejo do Outro; a falta no próprio Outro é condição para o objeto a.

**Relação com a Ontologia Freud.** Retoma, sob a categoria estrutural de Outro simbólico, a alteridade constitutiva já presente em Freud — as figuras parentais e o supereu como instâncias externas internalizadas.

**Importância para o modelo computacional.** Todo discurso registrado pelo sistema é dirigido a alguém. O conceito de Outro fundamenta teoricamente por que o contexto de enunciação, já exigido na definição de Evento Discursivo ([Modelo-Computacional-Discurso.md, 3](Modelo-Computacional-Discurso.md#3-unidade-fundamental-do-sistema--o-evento-discursivo)), não é acessório, mas constitutivo. O sistema não modela o Outro; apenas preserva o contexto relacional em que o discurso ocorre.

### 3.9 Objeto a

**Definição conceitual.** O objeto causa de desejo — não um objeto do mundo, mas o resto irredutível perdido na constituição do sujeito pela linguagem, que passa a funcionar como causa, e não como alvo, do desejo.

**Função na teoria.** Articula, no registro do objeto, o que no sujeito escapa à simbolização plena — é o correlato objetal da falta constitutiva instaurada pela entrada na linguagem.

**Relação com os demais conceitos.** Liga-se à falta (é seu correlato objetal), ao Real (é da ordem do que não se simboliza) e ao desejo (é sua causa, não seu objeto de satisfação).

**Relação com a Ontologia Freud.** Reformula o objeto pulsional freudiano — sempre contingente, conforme [Ontologia-Freud.md, Pulsão](Ontologia-Freud.md#33-pulsão) — e a perda constitutiva já presente na elaboração freudiana da experiência de satisfação.

**Importância para o modelo computacional.** Reforça, de forma explícita e definitiva, por que o sistema não pode identificar objeto a algum: trata-se, por definição, daquilo que escapa a qualquer captura, simbólica ou computacional.

### 3.10 Falta

**Definição conceitual.** Incompletude estrutural e constitutiva do sujeito, instaurada pela própria entrada na linguagem: o significante representa o sujeito, mas nunca o esgota, deixando sempre um resto não representado.

**Função na teoria.** É a condição de possibilidade do desejo — que só existe porque há falta — e do movimento metonímico da cadeia significante.

**Relação com os demais conceitos.** Articula-se ao objeto a (seu correlato objetal), ao Outro (a falta no Outro) e ao desejo (que só existe porque há falta).

**Relação com a Ontologia Freud.** Relê a noção freudiana de perda de objeto e a busca de reencontro de uma satisfação original (ver [Ontologia-Freud.md, Desejo](Ontologia-Freud.md#34-desejo)) sob a categoria de uma falta estrutural e constitutiva, e não meramente contingente.

**Importância para o modelo computacional.** Reforça que qualquer hipótese organizada pelo sistema é necessariamente parcial, por princípio teórico: o modelo nunca pretende — nem poderia — representar o sujeito por inteiro, apenas estruturas discursivas parciais, conforme já estabelecido em [Modelo-Computacional-Discurso.md (7)](Modelo-Computacional-Discurso.md#7-hipóteses).

### 3.11 Desejo (formulação lacaniana)

**Definição conceitual.** Distinto tanto da necessidade biológica (satisfeita por um objeto específico) quanto da demanda (endereçada ao Outro, sempre demanda de amor além de qualquer objeto), o desejo lacaniano é o que resta quando se subtrai a necessidade da demanda: desejo do desejo do Outro, metonímico, sem objeto que o satisfaça plenamente.

**Função na teoria.** Reorganiza inteiramente a lógica do desejo: não busca reencontrar uma satisfação passada, mas se sustenta na falta e se desloca metonimicamente de significante em significante, sem jamais se fixar.

**Relação com os demais conceitos.** Liga-se à falta (sua condição), ao objeto a (sua causa), ao Outro (desejo do desejo do Outro) e à metonímia (sua lógica de funcionamento).

**Relação com a Ontologia Freud.** Como já anotado em [Ontologia-Freud.md (3.4)](Ontologia-Freud.md#34-desejo), esta não é uma continuação direta do desejo freudiano, mas uma reformulação: Lacan desloca o desejo de uma lógica de satisfação — ligada ao traço mnêmico de uma experiência vivida — para uma lógica estrutural de falta e deslocamento contínuo.

**Importância para o modelo computacional.** Reforça teoricamente por que o sistema não pode tratar uma recorrência discursiva como "satisfação de um desejo identificado": o desejo, na leitura lacaniana, é por definição aquilo que não se fixa, não se esgota e não se confirma automaticamente a partir de nenhum padrão observável isoladamente.

## 4. Relações conceituais

Os onze conceitos desta ontologia se organizam em três agrupamentos que se articulam entre si.

- **Estrutura da linguagem** — Significante, Cadeia significante, Metáfora e Metonímia: o vocabulário que descreve *como* o material discursivo se organiza e produz efeitos de sentido, por substituição ou por deslizamento.
- **Registros** — Simbólico, Imaginário e Real (RSI): o quadro topológico dentro do qual toda a experiência — e, em particular, a estrutura da linguagem — se situa. O sistema, por definição, só acessa o registro Simbólico.
- **Sujeito e falta** — Outro, Falta, Objeto a e Desejo: a lógica pela qual o sujeito se constitui como incompleto na relação com a linguagem e com o Outro, e pela qual o desejo se sustenta precisamente dessa incompletude.

Esses agrupamentos se encadeiam: a cadeia significante opera no registro Simbólico, por metáfora e metonímia; ao representar o sujeito, todo significante deixa um resto — a falta — cujo correlato objetal é o objeto a; é dessa falta, articulada ao Outro, que o desejo se sustenta, sempre deslizando metonimicamente, sem se fixar.

### Como a Ontologia Lacan reorganiza a Ontologia Freud

A Ontologia Lacan não substitui os dez conceitos já estabelecidos em [Ontologia-Freud.md](Ontologia-Freud.md); ela os relê estruturalmente:

- **Inconsciente e Recalque** passam a ser lidos como efeitos da ordem Simbólica e da cadeia significante — o que é recalcado é sempre da ordem do significante.
- **Condensação** (presente em Sonhos e Chiste) é formalizada como **Metáfora**.
- **Deslocamento** (presente em Sonhos) é formalizado como **Metonímia**.
- **Pulsão** encontra, no objeto contingente que a caracteriza, o antecedente do **Objeto a** — o resto que causa o desejo.
- **Desejo** freudiano (ligado ao reencontro de uma satisfação vivida) é deslocado para uma lógica estrutural de **Falta** e deslizamento metonímico contínuo.
- **Repetição**, em sua dimensão que excede o princípio do prazer, antecipa o que Lacan formaliza como **Real** — o que insiste e resiste à simbolização.
- **Transferência** permanece fenômeno da relação analítica, mas passa a ser lida à luz do **Outro** e da pergunta pelo desejo que o sujeito endereça a esse lugar.

Esta descrição é relacional e conceitual. Nenhuma representação computacional (grafo, esquema de dados, diagrama técnico) é definida nesta fase.

## 5. Limites

### O que a Ontologia Lacan descreve

- As definições conceituais dos onze elementos listados no capítulo 3.
- A função de cada conceito dentro da teoria lacaniana.
- As relações entre esses conceitos, conforme o capítulo 4.
- Como cada conceito lacaniano reorganiza o conceito freudiano correspondente, sem substituí-lo.
- Um vocabulário estável que sprints futuras — em particular a especificação técnica do Lacan Engine — poderão utilizar como referência.

### O que ela não descreve

- Não descreve como o sistema poderia computacionalmente detectar, inferir ou operacionalizar qualquer um destes conceitos a partir do discurso registrado — isso pertence a uma especificação técnica futura do Lacan Engine, fora do escopo desta sprint.
- Não descreve critérios de interpretação clínica.
- Não define, por si só, o que constitui uma estrutura discursiva no sistema — essa definição permanece exclusivamente a de [Modelo-Computacional-Discurso.md (6)](Modelo-Computacional-Discurso.md#6-estruturas-discursivas); esta ontologia apenas fornece vocabulário teórico que poderá, no futuro, qualificar hipóteses, sem jamais confirmá-las.
- Não altera nem relativiza qualquer limite já estabelecido em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema) ou em [Modelo-Computacional-Discurso.md (8)](Modelo-Computacional-Discurso.md#8-limites).

### O que permanece dependente do processo analítico

- A confirmação de que uma estrutura discursiva observada pelo sistema corresponde, de fato, a um significante, a uma metáfora, a uma metonímia ou a qualquer outro elemento desta ontologia.
- A leitura de qualquer material discursivo em termos dos registros Simbólico, Imaginário e Real, do Outro, do objeto a ou da falta de um sujeito específico.
- A validade e a relevância clínica de qualquer hipótese que o sistema venha a organizar, conforme já estabelecido em [Modelo-Computacional-Discurso.md (7)](Modelo-Computacional-Discurso.md#7-hipóteses).

### Sobre o significante, de forma explícita

**O significante não é uma palavra, e não pode ser identificado automaticamente pelo sistema.** Seu papel depende inteiramente das relações que estabelece na cadeia discursiva — relações que o sistema não tem meios, nem autoridade teórica, para determinar por conta própria.

**Somente o sujeito, no processo analítico, pode confirmar o estatuto de um significante.** O Psyche AI organiza estruturas discursivas candidatas; a passagem dessas estruturas ao estatuto de significante é um ato que pertence exclusivamente ao sujeito que fala, mediado pelo analista — nunca ao sistema. Este limite já registrado em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema) é reafirmado aqui como fundamento explícito de toda a Ontologia Lacan.

## 6. Referências

Esta seção estrutura, sem ainda consolidar, a bibliografia lacaniana que fundamenta os conceitos desta ontologia. A consolidação — com edições, traduções e páginas específicas — é matéria de sprint futura, na mesma lógica já adotada em [Ontologia-Freud.md (6)](Ontologia-Freud.md#6-referências).

- Obra relacionada ao **Significante**, à **Cadeia significante**, à **Metáfora** e à **Metonímia**: *A instância da letra no inconsciente ou a razão desde Freud* (Écrits, 1957); *O Seminário, Livro III: As Psicoses* (1955-56).
- Obra relacionada aos **Registros Simbólico, Imaginário e Real**: *O Simbólico, o Imaginário e o Real* (1953); *O Seminário, Livro XXII: R.S.I.* (1974-75).
- Obra relacionada ao **Outro**: *Função e campo da fala e da linguagem em psicanálise* (Écrits, 1953); *O Seminário, Livro V: As Formações do Inconsciente* (1957-58).
- Obra relacionada ao **Objeto a**: *O Seminário, Livro X: A Angústia* (1962-63).
- Obra relacionada à **Falta**: *O Seminário, Livro XI: Os Quatro Conceitos Fundamentais da Psicanálise* (1964).
- Obra relacionada ao **Desejo** (formulação lacaniana): *O Seminário, Livro VI: O Desejo e sua Interpretação* (1958-59); *Subversão do sujeito e dialética do desejo no inconsciente freudiano* (Écrits, 1960).

## 7. Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)
- [Ontologia-Freud.md](Ontologia-Freud.md)
- [Arquitetura.md](Arquitetura.md)
- [Roadmap.md](Roadmap.md)
