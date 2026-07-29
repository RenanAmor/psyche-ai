# Value Objects — PsycheAI

> Versão 1.0

Este documento define os Value Objects utilizados pelo domínio do PsycheAI.

Value Objects não possuem identidade própria.

São definidos exclusivamente por seus valores.

São imutáveis.

---

# DataHora

Representa um instante temporal.

Utilizado para registrar sessões, discursos, eventos e observações.

---

# Texto

Representa um conteúdo textual preservado exatamente como produzido.

Não sofre modificações após sua criação.

---

# Identificador

Representa um identificador único utilizado pelas entidades.

Seu conteúdo nunca muda.

---

# Posição

Representa a localização de um evento discursivo dentro do discurso.

Permite preservar a ordem original.

---

# IntervaloTemporal

Representa um período entre dois instantes.

Utilizado para comparações cronológicas.

---

# Evidência

Representa a informação utilizada para justificar uma recorrência.

Não contém interpretações.

Contém apenas referências observáveis.

---

# Frequência

Representa a quantidade de ocorrências de um mesmo evento discursivo ao longo da memória longitudinal.

---

# Princípios

- São imutáveis.
- Não possuem identidade própria.
- São comparados pelos seus valores.
- Não possuem comportamento clínico.
- Podem ser reutilizados por diferentes entidades.