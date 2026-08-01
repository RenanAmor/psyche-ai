# Arquitetura — Psyche AI

> Versão 0.4 — Sprint 4 (Fundação Arquitetural); §4 acrescentada na Sprint 14 para
> resolver a âncora `#4-visão-arquitetural-de-longo-prazo--motores-conceituais`
> citada por `Ontologia-Freud.md` e `Ontologia-Lacan.md` desde a Sprint 2/3, que
> nunca existira de fato neste documento; §9 (Base Científica e Princípios de
> Representação) acrescentada na Sprint da Biblioteca Teórica.

Este documento define a arquitetura conceitual do PsycheAI. O detalhamento
técnico das camadas, componentes e decisões por sprint vive em
[Arquitetura-Camadas.md](Arquitetura-Camadas.md), atualizado a cada sprint —
este documento permanece no nível conceitual.

---

# 1. Stack Tecnológica

- PHP 8.2+
- Composer
- PHPUnit
- PSR-4
- Ecossistema L369

---

# 2. Estrutura do Projeto

```text
psyche-ai/
├── app/
├── config/
├── docs/
├── storage/
├── tests/
├── README.md
├── composer.json
├── .env.example
└── .gitignore
```

---

# 3. Convenções

- Namespace seguindo PSR-4.
- Configurações em `.env`.
- `storage/` contém somente dados produzidos pela aplicação.
- O domínio permanece independente da infraestrutura.

---

# 4. Visão Arquitetural de Longo Prazo — Motores Conceituais

O PsycheAI é concebido, conceitualmente, como um pipeline de três motores,
cada um consumindo a saída do anterior sem sobrepor sua responsabilidade:

```text
Discourse Engine
        │
        ▼
Freud Engine
        │
        ▼
Lacan Engine
```

- **Discourse Engine**: organiza o discurso registrado (Sujeito → Sessão →
  Discurso → Evento Discursivo → Memória Longitudinal) e expõe as
  recorrências detectadas ao longo do tempo — implementado desde a
  Sprint 14 do [Roadmap.md](Roadmap.md), sem persistir nenhum resultado
  derivado (recalculado a cada consulta).
- **Freud Engine**: aplica "atenção flutuante" sobre o que o Discourse
  Engine expõe — escuta tudo sem hierarquizar importância e só traz o
  que se repete, a partir da Ontologia Freud
  ([Ontologia-Freud.md](Ontologia-Freud.md)) — planejado para a Sprint 15.
- **Lacan Engine**: reclassifica as mesmas repetições trazidas pelo Freud
  Engine com vocabulário lacaniano (ex.: metáfora/metonímia), a partir da
  Ontologia Lacan ([Ontologia-Lacan.md](Ontologia-Lacan.md)) — sem
  acrescentar leitura de sentido nem afirmar estatuto de significante
  confirmado (ver [Ontologia-Lacan.md (5)](Ontologia-Lacan.md#5-limites))
  — planejado para a Sprint 16.

Nenhum dos três motores produz hipótese, diagnóstico ou identifica
significante automaticamente — apenas o analista ou o próprio sujeito
confirma qualquer leitura, conforme [Regras-Dominio.md](Regras-Dominio.md).

---

# 5. Arquitetura do Domínio

O PsycheAI é um sistema para observação longitudinal do discurso humano.

Seu domínio consiste em organizar, preservar e tornar observáveis as recorrências presentes na história do discurso.

O sistema não interpreta o sujeito.

O sistema não realiza diagnósticos.

O sistema não atribui significado clínico.

A interpretação permanece responsabilidade do analista.

---

## Fluxo Arquitetural

```text
Discurso
    │
    ▼
Sessão
    │
    ▼
Memória Longitudinal
    │
    ▼
Recorrências
    │
    ▼
Observações
```

---

### Discurso

Representa todo conteúdo produzido pelo sujeito.

---

### Sessão

Agrupa temporalmente os discursos produzidos em um mesmo contexto.

---

### Memória Longitudinal

Preserva toda a história discursiva do sujeito.

---

### Recorrências

Representam elementos que reaparecem ao longo da história do discurso.

São registradas sem interpretação clínica.

---

### Observações

São registros estruturados produzidos pelo sistema a partir das recorrências identificadas.

Observações nunca representam interpretações.

---

# 6. Princípios Arquiteturais

- O domínio do PsycheAI é o discurso.
- O sistema preserva a história do discurso.
- O sistema organiza eventos discursivos.
- O sistema constrói memória longitudinal.
- O sistema identifica recorrências.
- O sistema não interpreta o sujeito.
- O sistema não realiza diagnósticos.
- O sistema não identifica significantes.
- O sistema não produz conclusões clínicas.
- Toda interpretação permanece responsabilidade do analista.

---

# 7. Estado Atual

As camadas de domínio, aplicação, infraestrutura e apresentação (API REST
e interface web) estão implementadas — ver
[Arquitetura-Camadas.md](Arquitetura-Camadas.md) e
[Estrutura-de-Pastas.md](Estrutura-de-Pastas.md) para o detalhamento
técnico atualizado a cada sprint, e [Roadmap.md](Roadmap.md) para o
histórico completo. Do pipeline conceitual da Seção 4, o Discourse Engine
está implementado (Sprint 14); Freud Engine e Lacan Engine estão
planejados para as Sprints 15 e 16.

---

# 8. Próximos Passos

Ver as sprints planejadas em [Roadmap.md](Roadmap.md): Motor Freud
(Sprint 15), Motor Lacan (Sprint 16), Interface Conversacional
(Sprint 17) e Plataforma/autenticação (Sprint 18).

---

# 9. Base Científica e Princípios de Representação (Biblioteca Teórica)

Adicionado na Sprint da Biblioteca Teórica — princípio permanente da arquitetura, não específico de uma sprint.

## 9.1 Cadeia de rastreabilidade obrigatória

Nenhuma camada abaixo pode ser pulada ao implementar ou estender qualquer motor:

```text
Biblioteca Teórica
        │
        ▼
Modelo Observacional
        │
        ▼
Representação Computacional
        │
        ▼
Ontologia
        │
        ▼
Modelo Computacional
        │
        ▼
Implementação
        │
        ▼
Testes
```

A fundamentação científica (autores, obras, conceitos) vive em
[Biblioteca-Teorica/](Biblioteca-Teorica/README.md); o "Modelo
Computacional" de cada conceito é a seção "Aplicação Computacional" do seu
documento em [Biblioteca-Teorica/Conceitos/](Biblioteca-Teorica/Conceitos/);
como o conceito pode aparecer computacionalmente ao Sujeito e ao Analista é
a seção "Representação Computacional" do mesmo documento — ver
[Biblioteca-Teorica/Modelo-de-Documento.md](Biblioteca-Teorica/Modelo-de-Documento.md).
Nenhum motor novo é desenvolvido sem que essa cadeia já exista para o(s)
conceito(s) que ele operacionaliza.

## 9.2 Separação de interface entre Sujeito e Analista

Princípio já em prática desde `PortaoDeAnalista` (Sprint 18) e a Regra 11
de [Regras-Dominio.md](Regras-Dominio.md), formalizado nesta Sprint como
princípio permanente também em [Documento-Mestre.md §5](Documento-Mestre.md#5-princípios-éticos):

- O Sujeito (`/conversa*`) nunca visualiza significantes, recorrências,
  circuito pulsional, hipóteses, classificações, escrita lacaniana ou
  qualquer estrutura produzida pelos motores.
- O Analista (rotas atrás do Portão) pode visualizar essas estruturas,
  como apoio à escuta clínica — nunca como diagnóstico automático.

## 9.3 A escrita lacaniana pertence ao analista

A capacidade do sistema de representar estruturalmente o discurso segundo
a teoria lacaniana (hoje: rótulo "deslize metonímico"/"circuito" de
`ReclassificadorLacaniano`) existe exclusivamente para a interface do
Analista. Essa representação nunca é utilizada para compor a resposta ao
Sujeito — a superfície de conversa (`RespostaSocraticaService`) já é
isolada dela desde a Sprint 23.