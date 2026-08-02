# Ética em Inteligência Artificial

## Metadados

- **Categoria**: Ética Computacional
- **Tópico**: Ética em Inteligência Artificial (AI Ethics)
- **Definição**: Campo dedicado a identificar e sistematizar os princípios normativos (autonomia, beneficência, não maleficência, justiça, explicabilidade) que devem orientar o design, desenvolvimento e uso de sistemas de Inteligência Artificial.
- **Área científica de origem**: Filosofia Aplicada / Ciência da Computação.
- **Referências principais**: Floridi, L.; Cowls, J. (2019). "A Unified Framework of Five Principles for AI in Society". *Harvard Data Science Review*, 1(1). DOI: 10.1162/99608f92.8cd550d1; Jobin, A.; Ienca, M.; Vayena, E. (2019). "The Global Landscape of AI Ethics Guidelines". *Nature Machine Intelligence*, 1, 389–399. DOI: 10.1038/s42256-019-0088-2.
- **Tópicos relacionados**: [Governança de IA](governanca-de-ia.md); [Pesquisa com Seres Humanos](pesquisa-com-seres-humanos.md)
- **Status**: Catalogado
- **Observações**: Complementa, sem substituir, a Ética da Psicanálise ([../../../ECO/Etica-da-Psicanalise.md](../../../ECO/Etica-da-Psicanalise.md)) — ver [README.md, "Princípio de não substituição"](README.md#princípio-de-não-substituição--registrado-explicitamente).

## Aplicação no PsycheAI

Fundamenta cientificamente princípios já em vigor na plataforma antes mesmo desta Sprint: não maleficência e transparência (limites éticos já registrados em [Base-Cientifica-v1.0.md, "Limites éticos"](../../../Base-Cientifica-v1.0.md#limites-éticos)), explicabilidade (toda saída de LLM é restrita a vocabulário fechado e auditável, nunca texto livre não rastreável).

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — princípio de design já aplicado através de `TipoFormacaoFreudiana` (vocabulário fechado, auditável) e `PortaoDeAnalista` (controle de acesso), sem componente de "ética de IA" isolado.

## Relação com a Base Científica

Ética em IA fundamenta a responsabilidade técnica do uso de LLMs e ASR na plataforma — complementar, nunca substituta, da Ética da Psicanálise, que fundamenta a posição clínica da ECO diante do sujeito.

## Relação com os Motores

Freud Engine e ECO dependem indiretamente — o vocabulário fechado usado por `ClassificadorFreudianoLLM` é, entre outras razões, uma escolha de explicabilidade.

## Relação com a Representação Computacional

Não alcança diretamente a Representação Computacional.

## Referências cruzadas do projeto

- [README.md](README.md)
- [governanca-de-ia.md](governanca-de-ia.md)
- [../../../ECO/Etica-da-Psicanalise.md](../../../ECO/Etica-da-Psicanalise.md)
- [../../../Base-Cientifica-v1.0.md](../../../Base-Cientifica-v1.0.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
