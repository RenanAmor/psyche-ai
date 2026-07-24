# Ontologia Freud — Psyche AI

> Versão 0.1 — Sprint 2 (Ontologia Freud)
> Este documento é exclusivamente ontológico: organiza conceitualmente o vocabulário freudiano que fundamentará o futuro Freud Engine. Não há, nesta fase, algoritmos, regras de negócio, banco de dados, classes, código ou especificação do Freud Engine. Não há interpretações clínicas.

## 1. Objetivo da ontologia

Esta ontologia tem por finalidade organizar, de forma computacionalmente estruturável, os conceitos fundamentais da obra de Sigmund Freud que o Psyche AI adota como núcleo conceitual — o **Freud Engine**, já referido em [Arquitetura.md](Arquitetura.md#4-visão-arquitetural-de-longo-prazo--motores-conceituais).

Conforme estabelecido em [Documento-Mestre.md (6)](Documento-Mestre.md#6-modelo-teórico-fundamental), a arquitetura do sistema respeita a distinção entre os conceitos fundamentais desenvolvidos por Freud e a estrutura de leitura proposta por Lacan. Esta ontologia trata exclusivamente do primeiro polo: define **o que** o sistema observa, sem definir **como** o discurso é lido estruturalmente — tarefa que caberá a uma futura Ontologia Lacan.

O propósito desta ontologia é, portanto:

- fixar um vocabulário conceitual comum, fiel à obra de Freud, para os termos que o projeto usará em todas as fases futuras;
- explicitar a função de cada conceito dentro da teoria freudiana;
- explicitar as relações entre esses conceitos;
- indicar, para cada conceito, por que ele importa para o modelo computacional do discurso já estabelecido em [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md) — sem, em nenhum momento, definir como esse conceito seria detectado, inferido ou operacionalizado.

Esta ontologia é vocabulário e organização conceitual. Não é especificação técnica, e não é o Freud Engine.

## 2. Escopo teórico

A obra de Freud se estende por mais de três décadas e por sucessivas revisões teóricas (primeira e segunda tópicas, a virada de 1920 com a pulsão de morte, entre outras). Delimitar um escopo é necessário para que esta ontologia seja tratável.

Nesta versão, a ontologia considera os conceitos fundamentais listados no capítulo 3, tomados em sua formulação estrutural mais estabelecida na obra freudiana, cobrindo tanto a primeira tópica (consciente/pré-consciente/inconsciente) quanto elementos da segunda tópica e da virada pulsional de 1920, na medida em que são necessários para articular os dez conceitos selecionados.

Esta ontologia **não** pretende, nesta versão, cobrir a totalidade da obra de Freud, nem resolver tensões e revisões internas à sua teoria que não sejam diretamente relevantes aos dez conceitos aqui tratados.

Esta é uma construção **incremental**: novos conceitos, refinamentos e desdobramentos poderão ser adicionados em sprints futuras, sem que isso invalide o que é estabelecido nesta versão. Toda expansão futura deve preservar a coerência com o que aqui é definido, ou justificar e registrar explicitamente sua revisão.

## 3. Conceitos fundamentais

### 3.1 Inconsciente

**Definição conceitual.** Sistema psíquico distinto da consciência, regido por uma lógica própria (processo primário): condensação, deslocamento, ausência de negação, de contradição e de referência ao tempo cronológico. Não é apenas "aquilo que não está consciente no momento", mas um sistema dinamicamente ativo, que produz efeitos sobre o pensamento, a fala e o comportamento sem ser diretamente acessível à consciência.

**Função na teoria.** É a hipótese fundadora da psicanálise: a vida psíquica não se esgota na consciência. O inconsciente explica a existência de formações — sonhos, atos falhos, chistes, sintomas — que não podem ser plenamente compreendidas a partir da lógica consciente.

**Relação com os demais conceitos.** Todos os demais conceitos desta ontologia são modalidades, mecanismos ou vias de manifestação do inconsciente: o recalque é o que mantém conteúdos no inconsciente; a pulsão é a força que busca expressão através dele; o ato falho, o chiste e os sonhos são suas vias privilegiadas de manifestação; a repetição e a transferência são formas de seu retorno no tempo e na relação com o outro.

**Importância para o modelo computacional.** O Psyche AI **não opera sobre o inconsciente** — isso já está registrado como limite em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema) e em [Modelo-Computacional-Discurso.md (2)](Modelo-Computacional-Discurso.md#2-objeto-computacional-do-sistema). O conceito de inconsciente importa aqui apenas como **justificativa teórica** para que o sistema trate recorrências e relações no discurso registrado como potencialmente relevantes — é a hipótese que torna a busca por estruturas discursivas cientificamente motivada, sem que o sistema jamais afirme ter acesso ao que ela nomeia.

### 3.2 Recalque

**Definição conceitual.** Operação psíquica pela qual certas representações — ligadas a moções pulsionais inconciliáveis com as exigências do eu ou da realidade — são mantidas fora da consciência, permanecendo ativas no sistema inconsciente e continuando a buscar expressão.

**Função na teoria.** É a operação constitutiva do inconsciente dinâmico: não simplesmente aquilo que "não chegou" à consciência, mas aquilo que é ativamente mantido fora dela, e que retorna de forma transformada — o chamado "retorno do recalcado".

**Relação com os demais conceitos.** Age sobre representantes da pulsão; seu resultado típico é a formação de compromisso; o ato falho, o chiste e os sonhos são vias pelas quais o recalcado retorna de forma disfarçada.

**Importância para o modelo computacional.** O recalque fornece a justificativa teórica para tratar descontinuidades, lacunas, substituições e correções no discurso registrado como categorias dignas de preservação no [modelo de Evento Discursivo](Modelo-Computacional-Discurso.md#3-unidade-fundamental-do-sistema--o-evento-discursivo) — sem que o sistema infira, a partir delas, o que estaria sendo recalcado.

### 3.3 Pulsão

**Definição conceitual.** Conceito-limite entre o somático e o psíquico: uma força constante (não um instinto fixo), definida por fonte, pressão (*Drang*), finalidade (*Ziel*) e objeto (*Objekt*) — sendo o objeto o elemento mais variável e contingente da pulsão.

**Função na teoria.** É a força motriz da vida psíquica, sempre em busca de satisfação, capaz de deslocar-se entre objetos e vias de expressão diferentes (sublimação, recalque, inversão em seu contrário).

**Relação com os demais conceitos.** O desejo se articula à pulsão sem se confundir com ela; o recalque atua sobre representantes psíquicos da pulsão; formações de compromisso, sintomas e demais formações são destinos possíveis da pulsão.

**Importância para o modelo computacional.** A pulsão sustenta teoricamente a ideia de que certos elementos discursivos podem se apresentar com **insistência** — retornando, se deslocando, buscando novas vias —, o que se conecta diretamente ao eixo relacional de recorrência já definido em [Modelo-Computacional-Discurso.md (4)](Modelo-Computacional-Discurso.md#4-estrutura-do-discurso).

### 3.4 Desejo

**Definição conceitual.** Na obra freudiana, o desejo (*Wunsch*) está ligado ao traço mnêmico de uma experiência de satisfação; busca reencontrar, de forma alucinatória ou real, essa satisfação original.

**Função na teoria.** É o motor da formação onírica — o sonho como realização de desejo — e de boa parte da vida associativa psíquica.

**Relação com os demais conceitos.** Articula-se à pulsão como sua expressão psíquica; encontra nos sonhos sua manifestação privilegiada; relaciona-se à repetição como busca de reencontro de uma satisfação perdida.

**Importância para o modelo computacional.** O desejo, nesta acepção freudiana, fundamenta teoricamente por que certas associações e repetições ao longo do tempo — e não apenas seu conteúdo isolado — podem constituir candidatas a estrutura discursiva relevante, reforçando a importância da temporalidade estabelecida em [Modelo-Computacional-Discurso.md (5)](Modelo-Computacional-Discurso.md#5-temporalidade).

> Nota de escopo: o termo "desejo" recebe, na releitura lacaniana, uma formulação distinta que não se confunde com esta acepção freudiana. A articulação entre as duas leituras fica reservada à futura Ontologia Lacan (ver capítulo 5).

### 3.5 Formação de compromisso

**Definição conceitual.** Produção psíquica — sintoma, sonho, ato falho, chiste — resultante de um conflito entre uma moção inconsciente (ligada a uma pulsão) e as forças defensivas que a ela se opõem (recalque); um compromisso que satisfaz parcialmente, e de forma disfarçada, ambos os lados do conflito.

**Função na teoria.** Explica como o conteúdo recalcado retorna transformado, e não eliminado — é o mecanismo geral por trás de sintomas, sonhos, atos falhos e chistes.

**Relação com os demais conceitos.** É a categoria geral sob a qual se organizam o ato falho, o chiste e os sonhos, tratados nesta ontologia como suas espécies.

**Importância para o modelo computacional.** Fornece a base teórica para que certos Eventos Discursivos — em especial os associados a atos falhos, chistes e relatos de sonho — sejam registrados com atenção particular, não porque o sistema saiba que são formações de compromisso, mas porque a teoria freudiana lhes atribui densidade especial.

### 3.6 Ato falho

**Definição conceitual.** Erro de fala, ação, memória ou escrita que manifesta uma intenção inconsciente interferindo sobre um ato conscientemente pretendido.

**Função na teoria.** Uma das vias régias, ao lado dos sonhos, de acesso ao inconsciente na vida cotidiana (*Psicopatologia da Vida Cotidiana*).

**Relação com os demais conceitos.** Espécie de formação de compromisso; ligado ao recalque (o que retorna) e ao desejo (o que busca expressão).

**Importância para o modelo computacional.** Justifica teoricamente que interrupções, autocorreções e desvios no discurso registrado sejam preservados como classe própria de Evento Discursivo, sem que o sistema infira qual seria a intenção inconsciente subjacente — inferência que permanece, por definição, fora de seu alcance.

### 3.7 Chiste

**Definição conceitual.** Construção verbal ou conceitual que produz prazer por meio de técnicas — condensação, deslocamento, duplo sentido, alusão — análogas ao trabalho do sonho, permitindo a expressão breve e socialmente admitida de conteúdos (agressivos ou obscenos) de outro modo recalcados (*Os Chistes e sua Relação com o Inconsciente*).

**Função na teoria.** Evidencia mecanismos inconscientes operando de forma compartilhada e comunicável — ao contrário do sonho, privado, o chiste depende de um interlocutor para se realizar plenamente.

**Relação com os demais conceitos.** Compartilha técnicas com os sonhos; é formação de compromisso; distingue-se por exigir um outro para se completar.

**Importância para o modelo computacional.** A exigência de um interlocutor reforça, dentro desta ontologia, a importância do contexto de enunciação já previsto na definição de Evento Discursivo — o chiste é, por definição, relacional, nunca isolado.

### 3.8 Sonhos

**Definição conceitual.** Via régia de acesso ao inconsciente, formada pelo trabalho do sonho — condensação, deslocamento, consideração pela figurabilidade e elaboração secundária — que transforma pensamentos oníricos latentes em conteúdo manifesto, a serviço da realização de um desejo (*A Interpretação dos Sonhos*).

**Função na teoria.** É o modelo fundador de todo o mecanismo de formação de compromisso e de funcionamento do processo primário.

**Relação com os demais conceitos.** Modelo para o chiste e para o sintoma como formações de compromisso; ligado ao desejo (realização de desejo) e ao recalque (razão da distorção entre conteúdo latente e manifesto).

**Importância para o modelo computacional.** As operações do trabalho do sonho — condensação (vários elementos representados por um) e deslocamento (o valor afetivo migrando de um elemento a outro) — são o precedente teórico mais direto para tratar recorrência e deslocamento no discurso registrado como estruturalmente relevantes, sem que o sistema jamais interprete o sonho relatado: apenas registra o relato como material discursivo.

### 3.9 Repetição

**Definição conceitual.** Tendência a repetir experiências displacenteiras ou traumáticas, não redutível à busca de satisfação prevista pelo princípio do prazer (*Além do Princípio do Prazer*).

**Função na teoria.** Explica a recorrência de determinados padrões — em sintomas, em relações, na transferência — que não se explicam apenas pela busca de prazer; introduz o conceito de pulsão de morte.

**Relação com os demais conceitos.** Intersecta-se com a transferência (repetição encenada na relação analítica em lugar de lembrada) e com a pulsão (como um de seus destinos possíveis).

**Importância para o modelo computacional.** É o conceito que mais diretamente fundamenta o eixo temporal do modelo computacional — [Modelo-Computacional-Discurso.md (5)](Modelo-Computacional-Discurso.md#5-temporalidade) — ao estabelecer que a própria recorrência ao longo do tempo, e não apenas o conteúdo repetido, é objeto teórico relevante.

### 3.10 Transferência

**Definição conceitual.** Processo pelo qual o analisando redireciona, na relação analítica, afetos, expectativas e padrões relacionais originalmente dirigidos a figuras anteriores; inclui transferência positiva e negativa.

**Função na teoria.** É simultaneamente obstáculo e principal instrumento do tratamento psicanalítico: o que não é lembrado é repetido na transferência (*A Dinâmica da Transferência*; *Recordar, Repetir e Elaborar*).

**Relação com os demais conceitos.** Forma de repetição encenada na cena analítica; dirigida ao analista como objeto, articulando-se ao desejo.

**Importância para o modelo computacional.** A transferência lembra que todo Evento Discursivo ocorre dentro de uma relação situada — reforçando o "contexto de enunciação" já exigido pelo modelo — e reafirma, de forma direta, que qualquer hipótese organizada pelo sistema só tem sentido e validade dentro da relação analítica real, nunca de forma autônoma.

## 4. Relações conceituais

Os dez conceitos desta ontologia não são uma lista plana: organizam-se em quatro agrupamentos que se articulam entre si.

- **Núcleo estrutural** — Inconsciente e Recalque: o inconsciente como sistema, o recalque como a operação que o constitui dinamicamente, mantendo representações ativas fora da consciência.
- **Força motriz** — Pulsão e Desejo: o que impele a vida psíquica a buscar expressão e satisfação, e que o recalque, ao atuar, transforma em vez de eliminar.
- **Formações e vias de manifestação** — Formação de compromisso como categoria geral, da qual Ato falho, Chiste e Sonhos são espécies: os pontos em que o conflito entre pulsão e recalque se torna observável no discurso.
- **Temporalidade e vínculo** — Repetição e Transferência: as formas pelas quais tudo o que precede se inscreve no tempo e na relação com o outro, e não apenas em um conteúdo isolado.

Esses quatro agrupamentos se encadeiam: o recalque opera sobre representantes da pulsão; o resultado desse conflito se expressa como formação de compromisso; o desejo anima particularmente os sonhos; e a repetição e a transferência situam todo esse funcionamento no tempo e na relação com um outro — seja o interlocutor do chiste, seja o analista na transferência.

Esta descrição é relacional e conceitual. Nenhuma representação computacional (grafo, esquema de dados, diagrama técnico) é definida nesta fase.

## 5. Limites

### O que a Ontologia Freud descreve

- As definições conceituais dos dez elementos listados no capítulo 3.
- A função de cada conceito dentro da teoria freudiana.
- As relações entre esses conceitos, conforme o capítulo 4.
- Um vocabulário estável que sprints futuras — em particular a especificação técnica do Freud Engine — poderão utilizar como referência.

### O que ela não descreve

- Não descreve como o sistema poderia computacionalmente detectar, inferir ou operacionalizar qualquer um destes conceitos a partir do discurso registrado — isso pertence a uma especificação técnica futura do Freud Engine, fora do escopo desta sprint.
- Não descreve critérios de interpretação clínica.
- Não define, por si só, o que constitui uma estrutura discursiva no sistema — essa definição permanece exclusivamente a de [Modelo-Computacional-Discurso.md (6)](Modelo-Computacional-Discurso.md#6-estruturas-discursivas); esta ontologia apenas fornece vocabulário teórico que poderá, no futuro, qualificar hipóteses, sem jamais confirmá-las.
- Não altera nem relativiza qualquer limite já estabelecido em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema) ou em [Modelo-Computacional-Discurso.md (8)](Modelo-Computacional-Discurso.md#8-limites).

### O que dependerá da Ontologia Lacan

- A leitura estrutural desses conceitos — como eles se articulam à cadeia significante, à metáfora e à metonímia, e aos registros Simbólico, Imaginário e Real — pertence a uma futura Ontologia Lacan, ainda não desenvolvida.
- A reformulação lacaniana do desejo, distinta da acepção freudiana aqui registrada (ver nota de escopo em 3.4), será tratada e articulada à presente ontologia nesse documento futuro.
- A forma como Freud Engine e Lacan Engine se comunicam — conforme a visão de pipeline registrada em [Arquitetura.md (4)](Arquitetura.md#4-visão-arquitetural-de-longo-prazo--motores-conceituais) — permanece indefinida até que ambas as ontologias existam.

## 6. Referências

Esta seção estrutura, sem ainda consolidar, a bibliografia freudiana que fundamenta os conceitos desta ontologia. A consolidação — com edições, traduções e páginas específicas — é matéria de sprint futura.

- Obra relacionada ao **Inconsciente**: *O Inconsciente* (1915).
- Obra relacionada ao **Recalque**: *A Repressão* / *O Recalque* (1915).
- Obra relacionada à **Pulsão**: *Pulsões e seus Destinos* (1915); *Três Ensaios sobre a Teoria da Sexualidade* (1905).
- Obra relacionada ao **Desejo** e aos **Sonhos**: *A Interpretação dos Sonhos* (1900).
- Obra relacionada à **Formação de compromisso** e ao **Ato falho**: *Psicopatologia da Vida Cotidiana* (1901).
- Obra relacionada ao **Chiste**: *Os Chistes e sua Relação com o Inconsciente* (1905).
- Obra relacionada à **Repetição**: *Além do Princípio do Prazer* (1920).
- Obra relacionada à **Transferência**: *A Dinâmica da Transferência* (1912); *Recordar, Repetir e Elaborar* (1914).

## 7. Referências cruzadas do projeto

- [Documento-Mestre.md](Documento-Mestre.md)
- [Modelo-Computacional-Discurso.md](Modelo-Computacional-Discurso.md)
- [Arquitetura.md](Arquitetura.md)
- [Roadmap.md](Roadmap.md)
