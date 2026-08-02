# Princípios — Representação Computacional

> Sprint 29. Define oficialmente os cinco atributos que toda representação computacional do PsycheAI deve possuir, e as quatro operações que nenhuma representação pode executar. Estes princípios regem todos os demais documentos desta pasta e qualquer representação futura — nenhuma sprint de implementação pode contradizê-los sem revisão explícita deste documento.

## Os cinco atributos obrigatórios

Toda representação computacional produzida pelo PsycheAI — Timeline, Memória Longitudinal, Recorrências, Formações Freudianas, Representações Lacanianas, Circuitos, Grafos, Indicadores, ou qualquer visualização futura — deve ser:

1. **Observacional** — descreve apenas o que foi registrado ou derivado deterministicamente do que foi registrado, nunca o que isso significaria. Mesmo limite do [Modelo Observacional §1](../Modelo-Observacional.md#1-objetivo-da-observação): o objetivo é produzir observações confiáveis do discurso, nunca produzir sucesso terapêutico.
2. **Rastreável** — toda representação declara explicitamente de qual conceito da Biblioteca Teórica, fenômeno do Modelo Observacional e relação do Modelo Relacional ela deriva. Nenhuma representação "solta", sem essa cadeia, é admitida (ver [Evidencias.md](Evidencias.md)).
3. **Auditável** — toda afirmação de "implementado" é verificável contra o código real em `app/` nesta data, na mesma disciplina já aplicada pela Biblioteca Teórica, pelo Modelo Observacional, pelo Modelo Relacional e pela ECO. Nada é descrito como em produção por antecipação.
4. **Reproduzível** — dado o mesmo discurso registrado, a mesma representação deve ser produzida de forma determinística. Nenhuma representação depende de acaso, de estado oculto ou de um LLM não determinístico para decidir *se* algo é mostrado — quando um LLM participa (ex.: `ClassificadorFreudianoLLM`), participa da classificação de um fato já ocorrido, nunca da decisão de exibir ou ocultar a representação.
5. **Fundamentada na Biblioteca Teórica** — nenhuma representação introduz vocabulário, categoria ou estrutura que não esteja já registrada em [Biblioteca-Teorica/](../Biblioteca-Teorica/README.md), [Ontologia-Freud.md](../Ontologia-Freud.md) ou [Ontologia-Lacan.md](../Ontologia-Lacan.md).

## As quatro proibições permanentes

Nenhuma representação computacional pode:

- **Interpretar** — atribuir sentido, intenção ou significado ao discurso de um sujeito específico (Regra 7, [Regras-Dominio.md](../Regras-Dominio.md)).
- **Diagnosticar** — nenhum algoritmo do sistema produz diagnóstico, em nenhuma circunstância (Regra 9).
- **Concluir** — nenhuma representação afirma um desfecho, resultado ou fechamento de caso; mesmo o "encerramento" de um circuito ([Circuitos.md](Circuitos.md)) é um fato observável sobre o discurso registrado, nunca uma conclusão clínica.
- **Produzir hipótese clínica** — campo fixo em todo documento de Conceito da Biblioteca Teórica ([Biblioteca-Teorica/Modelo-de-Documento.md](../Biblioteca-Teorica/Modelo-de-Documento.md#campos-obrigatórios--documento-de-conceito)): "Nunca automaticamente". A mesma restrição vale, sem exceção, para toda representação desta camada.

## Relação com o Princípio da Neutralidade Observacional

Estes cinco atributos não avaliam o desfecho clínico de nenhum caso — apenas a qualidade da representação em si. Uma representação de um caso interrompido, abandonado ou inconclusivo é igualmente observacional, rastreável, auditável, reproduzível e fundamentada, nunca inferior a uma de caso concluído — mesmo princípio já estabelecido em [Arquitetura-Cientifica.md §4](../Arquitetura-Cientifica.md#4-princípio-da-neutralidade-observacional) e no ["Status do Caso"](../Modelo-Observacional.md#3-status-do-caso).

## Relação com a separação Sujeito/Analista

Estes cinco atributos valem igualmente para toda representação, independente de quem a visualiza. O que muda entre Sujeito e Analista não é a qualidade científica da representação, mas o **direito de acesso** a ela — ver [Interface-Sujeito.md](Interface-Sujeito.md) e [Interface-Analista.md](Interface-Analista.md). Uma representação nunca é "mais simples" ou "mais segura" para chegar ao Sujeito por meio de simplificação de conteúdo — as representações estruturadas por definição nunca chegam a ele; o que chega é, no máximo, uma pergunta socrática motivada por elas (Documento-Mestre.md §6.7).

## Referências cruzadas do projeto

- [README.md](README.md)
- [Interface-Sujeito.md](Interface-Sujeito.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Evidencias.md](Evidencias.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Modelo-Observacional.md](../Modelo-Observacional.md)
- [../Biblioteca-Teorica/Modelo-de-Documento.md](../Biblioteca-Teorica/Modelo-de-Documento.md)
