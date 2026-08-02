# Neuro-Symbolic AI

## Metadados

- **Categoria**: Inteligência Artificial
- **Tópico**: Neuro-Symbolic AI (IA Neuro-Simbólica)
- **Definição**: Abordagem de Inteligência Artificial que combina redes neurais (aprendizado estatístico a partir de dados) com representação simbólica explícita de conhecimento e regras (lógica, grafos de conhecimento), buscando unir capacidade de generalização estatística e interpretabilidade/rastreabilidade do raciocínio simbólico.
- **Área científica de origem**: Ciência da Computação / Inteligência Artificial.
- **Referências principais**: Garcez, A. d'Avila; Lamb, L. C. (2020). "Neurosymbolic AI: The 3rd Wave". arXiv:2012.05876; Marcus, G. (2020). "The Next Decade in AI: Four Steps Towards Robust Artificial Intelligence". arXiv:2002.06177.
- **Tópicos relacionados**: [Inteligência Artificial](inteligencia-artificial.md); [Aprendizado de Máquina](aprendizado-de-maquina.md)
- **Status**: A verificar
- **Observações**: Catalogado com a ressalva de que "neuro-simbólico" ainda não é um termo com definição única consensual na literatura — diferentes autores usam a expressão para arquiteturas distintas; a referência de Garcez e Lamb (2020) é a formulação mais citada nesta data.

## Aplicação no PsycheAI

Relevante como fundamentação de posicionamento científico: a arquitetura do PsycheAI já combina, na prática, um componente neural (LLM, estatístico) com um componente simbólico determinístico (`TipoFormacaoFreudiana` como enum fechado, `ReclassificadorLacaniano` como regras determinísticas de reclassificação) — um padrão estruturalmente próximo ao neuro-simbólico, embora não tenha sido desenhado com essa literatura como referência direta até esta Sprint.

## Componentes da Plataforma relacionados

`app/Domain/Enums/TipoFormacaoFreudiana` (componente simbólico — vocabulário fechado); `app/Infrastructure/AI/ClassificadorFreudianoLLM.php` (componente neural); `Domain/Services/ReclassificadorLacaniano` (regras simbólicas determinísticas sobre a saída do componente neural) — combinação já em produção, mesmo sem ter sido nomeada "neuro-simbólica" em nenhuma Sprint anterior.

## Relação com a Base Científica

Este padrão arquitetural é exatamente o que garante, na prática, que o LLM (componente neural, estatístico) nunca decida sozinho o vocabulário clínico usado — a Fundamentação Psicanalítica fixa o vocabulário fechado (o componente simbólico), e o LLM apenas classifica dentro dele.

## Relação com os Motores

Freud Engine e Lacan Engine já operam, na prática, segundo este padrão híbrido — registrado aqui como fundamentação científica retroativa de uma decisão de arquitetura já tomada em Sprints anteriores (pós-Sprint 17), nunca como mudança de comportamento desta Sprint.

## Relação com a Representação Computacional

Não alcança diretamente a Representação Computacional — é fundamentação do padrão arquitetural que já sustenta as Formações Freudianas e Representações Lacanianas catalogadas em [../../../Representacao-Computacional/README.md](../../../Representacao-Computacional/README.md).

## Referências cruzadas do projeto

- [README.md](README.md)
- [inteligencia-artificial.md](inteligencia-artificial.md)
- [../../../Representacao-Computacional/README.md](../../../Representacao-Computacional/README.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
