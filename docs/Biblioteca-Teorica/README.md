# Biblioteca Teórica — Psyche AI

> Base de Conhecimento Científico do PsycheAI, parte permanente da arquitetura do sistema (ver [Documento-Mestre.md §6.0](../Documento-Mestre.md#60-objetivo-científico-do-psycheai) e [Arquitetura.md §9](../Arquitetura.md#9-base-científica-e-princípios-de-representação-biblioteca-teórica)). Toda regra computacional presente ou futura deve possuir rastreabilidade até a literatura científica que a fundamenta.

## O que esta Biblioteca é

Uma estrutura documental que organiza autores, obras e conceitos — e as relações entre eles — servindo de base científica auditável para o PsycheAI. **Não é** um resumo interpretativo da obra de Freud, Lacan ou de qualquer outro autor: é catalogação de metadados, seguindo rigorosamente o modelo único definido em [Modelo-de-Documento.md](Modelo-de-Documento.md).

## Cadeia de rastreabilidade

```
Biblioteca Teórica → Modelo Observacional → Representação Computacional → Ontologia → Modelo Computacional → Implementação → Testes
```

Nenhum conceito é implementado no código sem essa cadeia completa. Ver [Como-os-Motores-Usam-a-Biblioteca.md](Como-os-Motores-Usam-a-Biblioteca.md) para a explicação completa de como cada motor do PsycheAI se apoia nela.

## Estrutura

| Pasta | Conteúdo | Itens catalogados |
|---|---|---|
| [Freud/Obras/](Freud/) | Catálogo completo das obras de Sigmund Freud | 94 |
| [Lacan/Escritos/](Lacan/Escritos/) | Écrits (1966) | 30 |
| [Lacan/Outros-Escritos/](Lacan/Outros-Escritos/) | Autres écrits (2001) | 17 |
| [Lacan/Seminarios/](Lacan/Seminarios/) | O Seminário, I–XXVII | 27 |
| [Referencias/](Referencias/) | Referências primárias de Freud e Lacan (filosofia, linguística, antropologia, psiquiatria/neurologia, literatura) | 27 |
| [Psicanalise/](Psicanalise/) | Autores da tradição psicanalítica pós-freudiana/pós-lacaniana | 13 |
| [Conceitos/](Conceitos/) | Os 21 conceitos canônicos de Ontologia-Freud.md/Ontologia-Lacan.md, únicos com Aplicação Computacional e Representação Computacional | 21 |
| [Filosofia/](Filosofia/), [Linguistica/](Linguistica/), [Antropologia/](Antropologia/), [Psiquiatria/](Psiquiatria/), [Inteligencia-Artificial/](Inteligencia-Artificial/), [Engenharia-de-Software/](Engenharia-de-Software/) | Ciências auxiliares — estrutura reservada para documentação futura | 0 (deliberado) |
| [Indices/](Indices/) | Seis índices navegáveis (Autor, Obra, Ano, Área, Conceito, Motor) | — |

**Total: 229 documentos catalogados** nesta Sprint, mais [Valor-Cientifico-dos-Casos.md](Valor-Cientifico-dos-Casos.md) — fundamentação histórica do Princípio da Neutralidade Observacional (ver [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)).

## Modelo único de documento

Todo documento de Obra ou Autor segue os mesmos campos: Autor, Título/Conceito, Título original, Ano, Idioma, Tipo, Área, Conceitos, Autores relacionados, Obras relacionadas, Motores do PsycheAI relacionados, Status, Observações. Documentos de Conceito (`Conceitos/`) têm, adicionalmente, as seções "Aplicação Computacional" e "Representação Computacional" (Visão do Sujeito / Visão do Analista). Ver [Modelo-de-Documento.md](Modelo-de-Documento.md) para a especificação completa.

## Critério de catalogação e decisões arquitetônicas

- **Profundidade "obra por obra" para Freud e Lacan** foi uma decisão explícita do usuário (opção "um arquivo por obra, literalmente todas") — um documento por obra, não uma tabela consolidada.
- **Precisão sobre exaustividade**: onde a datação ou a própria existência de uma obra/atribuição não pôde ser confirmada com confiança nesta Sprint (principalmente em Lacan/Outros-Escritos/ e nos Seminários sem edição oficial estabelecida por Jacques-Alain Miller), o `Status` do documento é `A verificar` em vez de apresentar falsa precisão — uma Biblioteca auditável não pode fabricar certeza. Ver [Indices/Indice-Obras.md](Indices/Indice-Obras.md) para localizar essas entradas.
- **"Motores do PsycheAI relacionados" só cita um motor quando o uso já é real ou está diretamente fundamentado** nas duas Ontologias — a maioria das 229 entradas tem "Nenhum (catalogação apenas)" neste campo, por design: catalogar o corpus completo de Freud e Lacan não significa que todo ele já fundamenta código. Ver [Indices/Indice-Motores.md](Indices/Indice-Motores.md).
- **Aplicação Computacional e Representação Computacional existem só no nível Conceito** (21 documentos), nunca em Obra ou Autor — ver a justificativa completa em [Modelo-de-Documento.md](Modelo-de-Documento.md) e [Como-os-Motores-Usam-a-Biblioteca.md](Como-os-Motores-Usam-a-Biblioteca.md).
- **Referências Primárias e Psicanálise catalogam autores, não obra a obra** — a Sprint pediu a catalogação dos autores dessas duas listas, não o inventário de suas obras completas (diferente de Freud/Lacan, onde "catálogo completo das obras" foi explícito).
- **Geração assistida por script**: os 229 documentos de Obra/Autor/Conceito e os 6 índices são gerados a partir de datasets estruturados em [_gerador/](_gerador/) (PHP, script de apoio — não é código de domínio/aplicação/infraestrutura do PsycheAI), garantindo que documento e índice nunca divirjam. Qualquer correção de dado deve ser feita no dataset, nunca só no `.md` gerado.

## Restrições desta Sprint

Nenhuma interpretação foi escrita. Nenhuma obra foi resumida de forma opinativa. Nenhum motor foi implementado. Nenhum código, API ou banco de dados foi alterado. Esta Sprint é exclusivamente documental.

## Referências cruzadas do projeto

- [Modelo-de-Documento.md](Modelo-de-Documento.md)
- [Como-os-Motores-Usam-a-Biblioteca.md](Como-os-Motores-Usam-a-Biblioteca.md)
- [Valor-Cientifico-dos-Casos.md](Valor-Cientifico-dos-Casos.md)
- [../Documento-Mestre.md](../Documento-Mestre.md)
- [../Arquitetura.md](../Arquitetura.md)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md)
- [../Modelo-Observacional.md](../Modelo-Observacional.md)
- [../Ontologia-Freud.md](../Ontologia-Freud.md)
- [../Ontologia-Lacan.md](../Ontologia-Lacan.md)
- [../Regras-Dominio.md](../Regras-Dominio.md)
- [../Roadmap.md](../Roadmap.md)
