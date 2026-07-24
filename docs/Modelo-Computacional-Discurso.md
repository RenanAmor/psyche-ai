# Modelo Computacional do Discurso — Psyche AI

> Versão 0.1 — Sprint 1 (Modelo Computacional do Discurso)
> Este documento contém exclusivamente modelagem conceitual. Não há, nesta fase, algoritmos, regras de negócio, banco de dados, classes, código ou especificação de Freud Engine / Lacan Engine. Este é o fundamento sobre o qual esses componentes serão construídos em sprints futuras.

## 1. Objetivo do modelo computacional

Este documento define o modelo conceitual que servirá de fundamento para todo o desenvolvimento técnico futuro do Psyche AI.

Antes de qualquer motor teórico (Freud Engine, Lacan Engine) ou de qualquer componente de estruturação (Discourse Engine — ver [Arquitetura.md](Arquitetura.md)) poder ser especificado, é necessário responder a uma pergunta anterior: **o que, exatamente, o sistema recebe, retém e organiza?**

O objetivo deste modelo é estabelecer, em nível puramente conceitual:

- o que constitui o objeto sobre o qual o sistema opera;
- qual é a menor unidade reconhecida pelo sistema;
- como essa unidade pode se organizar em estruturas maiores;
- por que a ordem temporal é constitutiva, e não incidental;
- o que pode ser chamado de "estrutura discursiva" no sistema;
- como hipóteses derivadas dessas estruturas podem ser representadas;
- e, de forma explícita, o que o sistema permanece incapaz de afirmar.

Este modelo é a fundação conceitual referida no [Documento-Mestre.md](Documento-Mestre.md), especificamente na hipótese central (6.3) e no objeto de pesquisa (6.2).

## 2. Objeto computacional do sistema

O objeto sobre o qual o Psyche AI opera é o **discurso registrado**.

Isso significa, de forma explícita:

- o sistema opera sobre o **registro** do que foi dito — transcrições, anotações ou qualquer forma de fixação do discurso de uma sessão —, não sobre o ato de fala em si, nem sobre o sujeito que fala;
- o discurso registrado é **material bruto**: uma sequência de manifestações verbais situadas no tempo, ainda sem qualquer atribuição de sentido, valor clínico ou estrutura definitiva;
- o sistema não opera sobre o inconsciente, sobre o sujeito ou sobre estados mentais — apenas sobre o registro discursivo desses fenômenos, conforme já estabelecido nos limites do [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema).

Distinguir o discurso registrado do sujeito que o produz é condição para que o sistema não extrapole seus limites: o sistema organiza o registro; a relação entre esse registro e o sujeito é uma questão que só o processo analítico pode endereçar.

## 3. Unidade fundamental do sistema — o Evento Discursivo

A menor unidade reconhecida pelo sistema é o **Evento Discursivo**.

### 3.1 Definição conceitual

Um Evento Discursivo é uma ocorrência discreta e situada do discurso registrado — um ponto no fluxo discursivo de uma sessão, associado a um momento e a um contexto de enunciação.

Um Evento Discursivo **não é**:

- uma palavra isolada;
- uma unidade gramatical (frase, oração);
- um significante — nenhum Evento Discursivo carrega, por si só, o estatuto de significante, pelas razões já registradas em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema);
- uma unidade de sentido fechado.

Um Evento Discursivo **é**:

- um recorte do registro discursivo, delimitado por critérios a serem definidos tecnicamente em sprint futura (não nesta fase);
- portador de um contexto mínimo: quando ocorreu, em qual sessão, em qual posição da sequência;
- um ponto de referência a partir do qual relações com outros eventos podem, eventualmente, ser observadas.

### 3.2 Por que uma unidade própria

Adotar o Evento Discursivo como unidade — em vez de, por exemplo, a palavra ou a frase — evita que o sistema colapse prematuramente o discurso em categorias linguísticas fechadas. O Evento Discursivo é deliberadamente uma unidade "menos comprometida": ele registra que algo ocorreu no discurso, sem já decidir o que esse algo significa ou a que categoria pertence.

Esta seção define apenas o conceito. Sua implementação técnica (formato de registro, granularidade, critérios de segmentação) é matéria de sprint futura.

## 4. Estrutura do discurso

O discurso, no sistema, não é tratado como um bloco único, mas como uma **sequência organizada de Eventos Discursivos**.

Essa organização pode ser pensada em três eixos conceituais:

- **Eixo sequencial**: os Eventos Discursivos se sucedem dentro de uma sessão, preservando a ordem em que ocorreram.
- **Eixo relacional**: Eventos Discursivos podem estabelecer relações entre si — de recorrência, aproximação, deslocamento ou associação —, formando o material a partir do qual estruturas discursivas (capítulo 6) podem emergir.
- **Eixo longitudinal**: sessões se organizam entre si ao longo do tempo, permitindo que relações entre Eventos Discursivos ultrapassem os limites de uma única sessão.

Esses três eixos definem como o discurso *pode* ser organizado computacionalmente. Este documento não define o mecanismo de organização (estrutura de dados, algoritmo, persistência) — apenas o modelo conceitual que qualquer mecanismo futuro deverá respeitar.

## 5. Temporalidade

A sequência temporal das sessões é **constitutiva** do modelo, não um metadado acessório.

Isso decorre diretamente do objeto do sistema: um Evento Discursivo isolado, fora de sua posição no tempo, perde grande parte do que o torna relevante para uma eventual estrutura discursiva. A relevância de uma recorrência, por exemplo, só pode ser reconhecida em relação a algo que ocorreu antes — e o sentido de um Evento Discursivo anterior pode ser reconfigurado por algo que ocorre depois.

Por isso, o modelo exige que o sistema:

- preserve a ordem exata em que os Eventos Discursivos ocorreram, dentro e entre sessões;
- trate a posição temporal como parte inseparável de cada Evento Discursivo, nunca como informação descartável;
- permita que relações entre Eventos Discursivos sejam observadas tanto retrospectiva quanto prospectivamente, sem privilegiar apenas a ordem cronológica de descoberta.

A temporalidade é o que permite que o sistema acompanhe estruturas discursivas *ao longo do tempo*, conforme já estabelecido no princípio fundador do [Documento-Mestre.md (6.1)](Documento-Mestre.md#61-princípio-fundador).

## 6. Estruturas discursivas

Uma **estrutura discursiva** é um padrão de relação observado entre dois ou mais Eventos Discursivos — recorrência, deslocamento, aproximação ou associação — que se destaca do fluxo discursivo por se repetir, se transformar ou persistir ao longo do tempo.

É fundamental delimitar o que esse conceito não é:

- uma estrutura discursiva não é um significante;
- uma estrutura discursiva não é, por si, uma interpretação;
- uma estrutura discursiva não afirma nada sobre o sujeito, seu inconsciente ou seus estados mentais.

Uma estrutura discursiva é apenas aquilo que o nome indica: uma **estrutura observada no discurso registrado**. Seu eventual estatuto de significante, sua relevância clínica ou seu sentido são questões que **permanecem inteiramente fora do alcance do sistema**, conforme os limites já registrados em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema) — cabendo exclusivamente ao processo analítico confirmá-las, ou não.

## 7. Hipóteses

O produto que o sistema entrega ao analista não é uma conclusão, mas uma **hipótese**.

Conceitualmente, uma hipótese no Psyche AI é a associação entre:

- uma ou mais estruturas discursivas observadas (capítulo 6);
- os Eventos Discursivos que a fundamentam, de forma rastreável — nenhuma hipótese existe sem referência explícita ao material que a origina;
- um enunciado transparente do que foi observado, formulado de modo a não afirmar mais do que o próprio material permite;
- a indicação explícita de que se trata de uma hipótese, e não de uma constatação — sua confirmação ou refutação é sempre uma decisão do processo analítico, nunca do sistema.

Nenhum mecanismo de geração, cálculo ou algoritmo é definido nesta fase. Este capítulo estabelece apenas o que uma hipótese **é**, conceitualmente, dentro do modelo — não como ela é produzida.

## 8. Limites

O modelo computacional do discurso não autoriza o sistema a:

- tratar um Evento Discursivo como um significante;
- tratar uma estrutura discursiva como uma interpretação;
- atribuir sentido, causa ou valor clínico a qualquer Evento Discursivo ou estrutura discursiva;
- afirmar que uma recorrência observada corresponde a um fenômeno psíquico real (recalque, pulsão, repetição, transferência, ou qualquer outro);
- decidir, de forma autônoma, a relevância de uma estrutura discursiva;
- apresentar uma hipótese como fato confirmado;
- reter ou inferir qualquer informação sobre o sujeito que não esteja diretamente ancorada no discurso registrado.

Estes limites são específicos do modelo computacional do discurso e se somam — sem substituir — aos limites já estabelecidos em [Documento-Mestre.md (6.5)](Documento-Mestre.md#65-limites-do-sistema).

## 9. Referências

- [Documento-Mestre.md](Documento-Mestre.md)
- [Arquitetura.md](Arquitetura.md)
- [Roadmap.md](Roadmap.md)
