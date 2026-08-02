# Inteligência Artificial

## Metadados

- **Categoria**: Inteligência Artificial
- **Tópico**: Inteligência Artificial (IA)
- **Definição**: Campo da Ciência da Computação dedicado ao estudo e à construção de sistemas capazes de executar tarefas que, quando realizadas por humanos, requerem o que se costuma chamar de inteligência — percepção, raciocínio, aprendizado, planejamento e uso de linguagem.
- **Área científica de origem**: Ciência da Computação.
- **Referências principais**: Russell, S.; Norvig, P. (2020). *Artificial Intelligence: A Modern Approach* (4th ed.). Pearson. ISBN 978-0-13-461099-3.
- **Tópicos relacionados**: [Aprendizado de Máquina](aprendizado-de-maquina.md); [Neuro-Symbolic AI](neuro-symbolic-ai.md); [Arquiteturas Cognitivas](../Arquiteturas-Cognitivas/arquiteturas-cognitivas.md)
- **Status**: Catalogado
- **Observações**: Termo guarda-chuva desta categoria — os demais seis tópicos são subcampos ou técnicas específicas dentro de IA.

## Aplicação no PsycheAI

Fundamentação científica de topo para todos os componentes de extração/qualificação automatizada de dado já catalogados nas categorias 1 e 2 desta área — classificação de texto, geração de linguagem, transcrição de áudio. O PsycheAI não implementa "IA" como componente único; usa técnicas específicas de IA (LLMs, ASR) através dos serviços já catalogados.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/` (namespace completo) — todos os componentes já catalogados em [Large Language Models](../Processamento-Computacional-da-Linguagem/large-language-models.md) e [Whisper](../Processamento-de-Audio/whisper.md).

## Relação com a Base Científica

IA fundamenta a capacidade técnica de extrair e qualificar dado do discurso — nunca decide, por si, o que desse dado é clinicamente relevante; essa decisão é exclusiva da Fundamentação Psicanalítica, aplicada apenas depois que o dado já foi processado por IA.

## Relação com os Motores

Freud Engine (via LLM) e ECO (via LLM) dependem diretamente. Discourse Engine depende de IA apenas indiretamente através da transcrição (ASR). Lacan Engine não depende diretamente de IA nesta versão — reclassifica rótulos já produzidos pelo Freud Engine com regras determinísticas (`ReclassificadorLacaniano`).

## Relação com a Representação Computacional

Alimenta indiretamente todas as oito representações catalogadas em [../../../Representacao-Computacional/README.md](../../../Representacao-Computacional/README.md), na medida em que cada uma depende de dado previamente extraído/qualificado por algum componente de IA.

## Referências cruzadas do projeto

- [README.md](README.md)
- [aprendizado-de-maquina.md](aprendizado-de-maquina.md)
- [../Processamento-Computacional-da-Linguagem/large-language-models.md](../Processamento-Computacional-da-Linguagem/large-language-models.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
