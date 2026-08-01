# Modelo de Documento — Biblioteca Teórica

> Este documento define a estrutura única e obrigatória de todo documento de obra ou de autor dentro da Biblioteca Teórica. Nenhum documento de obra ou autor deve se desviar desta estrutura. Novos campos só podem ser adicionados por revisão explícita deste modelo, nunca ad-hoc em um documento individual.

## Propósito

Cada documento da Biblioteca Teórica é um registro de metadados, não um resumo, não uma resenha e não uma interpretação. Ele existe para permitir rastreabilidade: de onde vem um conceito, quem o autor, em que obra, e quais motores do PsycheAI dependem dele.

## Campos obrigatórios — documento de Obra

Usado em `Freud/Obras/`, `Lacan/Escritos/`, `Lacan/Outros-Escritos/`, `Lacan/Seminarios/`.

- **Autor** — nome do autor da obra.
- **Título** — título em português, na tradução de referência adotada pelo projeto (ver Observações de cada obra quando houver mais de uma tradução corrente).
- **Título original** — título na língua de composição/publicação original.
- **Ano** — ano de composição ou de primeira publicação/apresentação (quando distintos, ambos são registrados).
- **Idioma** — idioma original de composição.
- **Tipo** — classificação fechada: Livro, Ensaio, Conferência, Carta, Prefácio, Artigo Técnico, Caso Clínico, Seminário (Aula), Seminário (Volume).
- **Área** — área disciplinar de origem (ex.: Psicanálise, Filosofia, Linguística, Antropologia, Psiquiatria/Neurologia, Literatura). Quando a disciplina tem pasta própria de primeiro nível na Biblioteca, o valor coincide com o nome da pasta; áreas sem pasta dedicada nesta versão (ex.: Literatura) são registradas do mesmo jeito, como valor textual — a ausência de pasta não impede a catalogação do autor em `Referencias/`.
- **Conceitos** — lista de conceitos principais tratados na obra, restrita a nomes de conceito (sem explicação do conceito neste campo).
- **Autores relacionados** — outros autores da Biblioteca cuja obra dialoga diretamente com esta.
- **Obras relacionadas** — outras obras da Biblioteca em relação direta de precedência, resposta ou desenvolvimento com esta.
- **Motores do PsycheAI relacionados** — qual(is) dos motores conceituais do PsycheAI ([Documento-Mestre.md §7](../Documento-Mestre.md#7-arquitetura-conceitual): Discourse Engine, Freud Engine, Lacan Engine, Modo Socrático) usam ou poderão usar este conceito como fundamentação. "Nenhum (catalogação apenas)" é um valor válido quando a obra ainda não tem uso computacional definido.
- **Status** — Catalogado (metadados registrados, sem resumo) ou A verificar (metadados prováveis, pendentes de confirmação bibliográfica — usado quando a fonte consultada diverge ou é incerta).
- **Observações** — notas estritamente bibliográficas (edição de referência, tradutor, obra publicada postumamente, divergência de datação entre fontes). Nunca uma nota interpretativa sobre o conteúdo da obra.

## Campos obrigatórios — documento de Autor

Usado em `Referencias/` e `Psicanalise/`, onde a Sprint cataloga o autor (não obra a obra).

- **Autor** — nome completo, e nome usualmente citado quando distinto.
- **Nascimento / Morte** — anos (ou "A verificar" quando incerto).
- **Nacionalidade**
- **Área** — área disciplinar principal.
- **Vínculo com Freud/Lacan** — como este autor se relaciona ao núcleo Freud/Lacan da Biblioteca (fonte direta, interlocutor, herdeiro teórico, crítico, etc. — descrito de forma factual, não interpretativa).
- **Conceitos** — conceitos principais associados a este autor.
- **Autores relacionados**
- **Obras relacionadas** — obras da Biblioteca (deste autor ou de outros) diretamente associadas.
- **Motores do PsycheAI relacionados**
- **Status**
- **Observações**

## Campos obrigatórios — documento de Conceito

Usado exclusivamente em `Conceitos/` — os conceitos canônicos já definidos com rigor em [Ontologia-Freud.md §3](../Ontologia-Freud.md#3-conceitos-fundamentais) e [Ontologia-Lacan.md §3](../Ontologia-Lacan.md#3-conceitos-fundamentais). Diferente dos documentos de Obra/Autor, o documento de Conceito é o único ponto da Biblioteca Teórica autorizado a descrever **uso computacional** — porque é o único nível em que a fundamentação teórica já é rigorosa o bastante (vocabulário fixado, relações mapeadas, limites explícitos) para sustentar essa ponte sem virar interpretação ad-hoc. Um documento de Conceito tem três blocos:

**Bloco 1 — Metadados** (mesmos campos do documento de Obra: Autor, Conceito, Obra de origem, Ano, Idioma, Área, Conceitos relacionados, Autores relacionados, Obras relacionadas, Status, Observações).

**Bloco 2 — `## Aplicação Computacional`** (obrigatório em todo documento de Conceito, decisão de arquitetura registrada nesta Sprint):

- **Objetivo computacional** — o que, computacionalmente, este conceito justifica observar ou organizar (nunca "o que ele significa").
- **Fundamentação científica** — remissão direta à(s) obra(s)/seção(ões) da Biblioteca que sustentam este uso.
- **Dados necessários** — quais dados já existentes no Domínio (`EventoDiscursivo`, `Sessao`, etc.) são indispensáveis para qualquer operação computacional ligada a este conceito.
- **Dados opcionais** — dados que enriquecem mas não são pré-requisito.
- **Eventos que podem originá-lo** — que tipo de evento discursivo/registro pode disparar a observação deste conceito.
- **Relações com outros conceitos** — remissão às relações já mapeadas em Ontologia-Freud.md §4 / Ontologia-Lacan.md §4, sem introduzir relação nova.
- **Componentes do PsycheAI que utilizam este conceito** — classes/serviços reais do código (quando existirem) ou "Nenhum implementado nesta versão".
- **Pode ser observado automaticamente? (Sim/Não)**
- **Pode ser organizado automaticamente? (Sim/Não)**
- **Pode ser classificado automaticamente? (Sim/Não)**
- **Depende de confirmação do sujeito? (Sim/Não)**
- **Depende de validação do analista? (Sim/Não)**
- **Gera hipótese clínica?** — campo fixo, sempre "Nunca automaticamente" (Regras 7–11, [Regras-Dominio.md](../Regras-Dominio.md)); não é uma pergunta Sim/Não.
- **Evidências produzidas pelo sistema** — o que o sistema efetivamente devolve (recorrência, rótulo estrutural, pergunta) — nunca "interpretação" ou "diagnóstico".
- **Limitações computacionais** — o que este conceito, por definição teórica, não pode ser automatizado a partir de [Ontologia-Freud.md §5](../Ontologia-Freud.md#5-limites) / [Ontologia-Lacan.md §5](../Ontologia-Lacan.md#5-limites).
- **Trabalhos científicos relacionados** — obras da Biblioteca.
- **Motores impactados**.

Nenhum motor novo do PsycheAI pode ser desenvolvido sem que o(s) conceito(s) que ele operacionaliza tenha(m) sua Aplicação Computacional documentada aqui primeiro — decisão de arquitetura registrada nesta Sprint, vinculante para sprints futuras de implementação.

**Bloco 3 — `## Representação Computacional`** (obrigatório em todo documento de Conceito, decisão de arquitetura v2 desta Sprint): descreve como o conceito pode aparecer computacionalmente para cada um dos dois públicos do sistema — nunca a mesma resposta para os dois, porque são interfaces distintas ([Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos) — princípio de separação Sujeito/Analista).

### Visão do Sujeito

- **Como este conceito interfere na conversa?**
- **O sujeito pode perceber sua existência?** (Sim/Não)
- **Como a IA deve se comportar diante dele?**
- **Quais perguntas podem ser feitas?**
- **Quais perguntas nunca podem ser feitas?**

### Visão do Analista

- **Como o conceito é apresentado?**
- **Quais visualizações são produzidas?**
- **Quais relações podem ser exibidas?**
- **Quais evidências sustentam essa representação?**
- **Quais motores produzem essa informação?**
- **Quais componentes do sistema participam dessa construção?**

O princípio que rege este bloco: a escrita lacaniana e qualquer estrutura produzida pelos motores (recorrência, circuito, rótulo estrutural, hipótese) pertencem exclusivamente à Visão do Analista. A Visão do Sujeito nunca inclui vocabulário técnico, classificação ou afirmação — apenas, quando aplicável, a pergunta socrática que o conceito pode motivar (ver [Documento-Mestre.md §6.7](../Documento-Mestre.md#67-modo-de-enunciação-o-método-socrático)).

## O que este modelo não permite

- Resumo de conteúdo da obra ou do pensamento do autor além dos "Conceitos" nomeados.
- Juízo de valor sobre a obra ou autor.
- Afirmação de que um motor "usa" um conceito quando esse uso ainda não existe no código — o campo "Motores do PsycheAI relacionados" descreve fundamentação teórica potencial ou já implementada, nunca implementação futura como fato consumado. Ver [Como-os-Motores-Usam-a-Biblioteca.md](Como-os-Motores-Usam-a-Biblioteca.md).
- No documento de Conceito, o campo "Componentes do PsycheAI que utilizam este conceito" só pode citar classes/serviços que existem de fato no código nesta data — é um campo auditável contra o repositório, não uma lista de intenções.
- Na "Visão do Sujeito" da seção Representação Computacional, nunca listar uma pergunta que exponha vocabulário técnico, rótulo estrutural ou classificação ao Sujeito — isso pertence exclusivamente à "Visão do Analista" (princípio de separação Sujeito/Analista, [Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos)).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Como-os-Motores-Usam-a-Biblioteca.md](Como-os-Motores-Usam-a-Biblioteca.md)
- [Documento-Mestre.md](../Documento-Mestre.md)
- [Roadmap.md](../Roadmap.md)
