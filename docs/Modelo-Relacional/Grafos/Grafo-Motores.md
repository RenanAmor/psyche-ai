# Grafo dos Motores — Especificação

> Especificação, sem implementação, do grafo bipartido entre os 21 conceitos canônicos e os componentes do PsycheAI que os operacionalizam (ou não). Fonte: [../Matrizes/Motor-x-Conceito.md](../Matrizes/Motor-x-Conceito.md).

## Nós

- **21 nós de conceito** — mesmos de [Grafo-Integrado.md](Grafo-Integrado.md).
- **8 nós de componente**: Motor Freud, Motor Lacan, Memória Discursiva, Timeline, Circuito Pulsional, Interface do Analista, Interface do Sujeito, ECO — Estrutura Computacional de Observação (chamada de "Modo Socrático" até a Sprint 28; ver [Arquitetura.md §4](../../Arquitetura.md#4-visão-arquitetural-de-longo-prazo--motores-conceituais) para a definição de cada componente e [ECO/README.md](../../ECO/README.md) para a identidade oficial).

## Arestas

Grafo bipartido conceito↔componente — nenhuma aresta conceito↔conceito ou componente↔componente. Extraídas de [../Matrizes/Motor-x-Conceito.md](../Matrizes/Motor-x-Conceito.md):

```
Formação de compromisso → Motor Freud, Motor Lacan, Memória Discursiva, Timeline, Interface do Analista
Ato falho → Motor Freud, Memória Discursiva, Timeline, Interface do Analista
Chiste → Motor Freud, Motor Lacan, Memória Discursiva, Timeline, Interface do Analista
Sonhos → Motor Freud, Memória Discursiva, Timeline, Interface do Analista
Repetição → Motor Freud, Motor Lacan, Memória Discursiva, Timeline, Circuito Pulsional, Interface do Analista, ECO
Metonímia → Motor Freud (observação-base), Motor Lacan, Memória Discursiva, Timeline, Interface do Analista
Pulsão → Motor Freud (fundo), Circuito Pulsional (nome, sem operacionalização)
Desejo (Freud) → Motor Freud (fundo)
Significante → Motor Lacan (questão de pesquisa aberta)
Metáfora → Motor Freud (classificação de origem: Chiste/Sonho), Motor Lacan (reclassificação), Interface do Analista (exibe quando disparada)
Cadeia significante → Memória Discursiva (organiza sequências, sem nomear)
Registro Simbólico → Memória Discursiva (opera sobre o registro, sem nomear)
Outro → Memória Discursiva (preserva contexto, sem nomear)
```

Os 8 conceitos restantes (Inconsciente, Recalque, Transferência, Registro Imaginário, Registro Real, Falta, Objeto a, Desejo lacaniano) não têm aresta para nenhum componente — nós isolados neste grafo específico, embora conectados no [Grafo-Integrado.md](Grafo-Integrado.md).

## Propriedades topológicas

- **Componente de maior grau** (corrigido na Sprint 30 — contagem anterior estava errada): Memória Discursiva e Motor Freud (9 arestas cada) — todo conceito com alguma observação computacional passa, sem exceção, pela Memória Discursiva, e o Motor Freud é a origem de toda classificação estrutural, inclusive a que fundamenta a reclassificação lacaniana de Metáfora. Interface do Analista vem em seguida (7 arestas), consistente com [Arquitetura-Cientifica.md §2](../../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista).
- **Interface do Sujeito tem grau zero**: nenhum conceito é exposto diretamente ao Sujeito — o único nó de componente completamente isolado neste grafo, por decisão arquitetônica explícita, não por lacuna de fundamentação.
- **Conceito de maior grau**: Repetição (7 arestas) — único conceito conectado a todos os 8 componentes exceto Interface do Sujeito.
- **8 conceitos isolados** (grau zero neste grafo bipartido) — mapeiam exatamente a lista de "fundamentação teórica de fundo, sem observação própria" e "limites absolutos" já auditada em [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md), com a exceção de Significante (grau 1, questão em aberto) e Metáfora (grau 3, corrigido na Sprint 30 — reclassificada por ponte com o Motor Freud, não apenas mapeada).

## Restrição

Especificação apenas. Nenhum componente foi criado, alterado ou inferido nesta Sprint — todas as arestas reproduzem exatamente o já auditado contra o código real em [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md) e nos campos "Motores impactados" de [Biblioteca-Teorica/Conceitos/](../../Biblioteca-Teorica/Conceitos/).

## Referências cruzadas do projeto

- [README.md](README.md)
- [../Matrizes/Motor-x-Conceito.md](../Matrizes/Motor-x-Conceito.md)
- [Grafo-Integrado.md](Grafo-Integrado.md)
- [Arquitetura.md](../../Arquitetura.md)
- [Modelo-Observacional/README.md](../../Modelo-Observacional/README.md)
