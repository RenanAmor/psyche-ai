# Proveniência de Dados

## Metadados

- **Categoria**: Engenharia Científica
- **Tópico**: Proveniência de Dados (Data Provenance)
- **Definição**: Registro explícito e estruturado da origem de um dado, das transformações que sofreu e dos agentes/processos responsáveis por cada etapa, desde sua criação até seu estado atual.
- **Área científica de origem**: Ciência da Computação / Ciência da Informação.
- **Referências principais**: Moreau, L.; Groth, P. (2013). *Provenance: An Introduction to PROV*. Morgan & Claypool. ISBN 978-1-60845-750-4; W3C (2013). *PROV-DM: The PROV Data Model*. W3C Recommendation.
- **Tópicos relacionados**: [Rastreabilidade](rastreabilidade.md); [Auditoria Científica](auditoria-cientifica.md)
- **Status**: Catalogado
- **Observações**: Distinto de rastreabilidade — proveniência registra a história de um dado específico; rastreabilidade é a capacidade geral de seguir essa história até a fonte, quando necessário.

## Aplicação no PsycheAI

Fundamenta cientificamente a exigência de que todo `EventoDiscursivo` preserve a origem de sua criação (voz transcrita, texto importado) e todo dado derivado (Recorrência, Observação) preserve referência ao dado de origem que a produziu — nunca um dado "solto", sem histórico auditável.

## Componentes da Plataforma relacionados

`Domain/Entities/EventoDiscursivo` (preserva `Sessao` e `criadoEm` de origem); `Domain/Entities/Recorrencia` (referencia as ocorrências que a compõem) — proveniência já implícita na modelagem de Domínio desde Sprints anteriores, sem um componente de proveniência nomeado ou centralizado.

## Relação com a Base Científica

Proveniência garante que todo dado consumido pela Fundamentação Psicanalítica (para observação e organização) tenha história auditável — condição de confiança para que a leitura clínica subsequente seja científica, não especulativa.

## Relação com os Motores

Discourse Engine depende diretamente — toda observação organizada preserva referência aos `EventoDiscursivo` de origem.

## Relação com a Representação Computacional

Fundamenta o atributo "auditável", um dos cinco atributos obrigatórios de toda representação ([../../../Representacao-Computacional/Principios.md](../../../Representacao-Computacional/Principios.md)).

## Referências cruzadas do projeto

- [README.md](README.md)
- [rastreabilidade.md](rastreabilidade.md)
- [auditoria-cientifica.md](auditoria-cientifica.md)
- [../../../Representacao-Computacional/Principios.md](../../../Representacao-Computacional/Principios.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
