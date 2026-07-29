# Entidades — PsycheAI

> Versão 1.0

Este documento define as entidades principais do domínio do PsycheAI.

As entidades representam objetos que possuem identidade própria e persistem ao longo do tempo.

---

# 1. Sujeito

Representa a pessoa cujo discurso será observado.

### Responsabilidades

- possuir identidade própria;
- manter o histórico de sessões;
- preservar a memória longitudinal.

---

# 2. Sessão

Representa um encontro entre o sujeito e o analista.

### Responsabilidades

- registrar data e horário;
- agrupar discursos;
- organizar eventos discursivos.

---

# 3. Discurso

Representa todo o conteúdo produzido pelo sujeito durante uma sessão.

### Responsabilidades

- preservar integralmente o conteúdo;
- manter a ordem cronológica;
- servir como origem dos eventos discursivos.

---

# 4. Evento Discursivo

Representa uma ocorrência registrada durante o discurso.

### Responsabilidades

- manter sua posição dentro do discurso;
- preservar seu conteúdo;
- permitir comparação futura.

---

# 5. Memória Longitudinal

Representa o histórico acumulado de um sujeito.

### Responsabilidades

- organizar sessões;
- manter continuidade temporal;
- disponibilizar histórico para comparação.

---

# 6. Recorrência

Representa um evento discursivo identificado em diferentes momentos da memória longitudinal.

### Responsabilidades

- registrar repetições;
- relacionar eventos;
- preservar evidências.

---

# 7. Observação

Representa um registro produzido automaticamente pelo sistema.

### Responsabilidades

- descrever recorrências encontradas;
- armazenar evidências;
- nunca produzir interpretações.

---

# Relação entre Entidades

```text
Sujeito
   │
   └── Sessões
          │
          └── Discursos
                  │
                  └── Eventos Discursivos

Sessões
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

# Princípios

- Toda entidade possui identidade.
- Toda entidade possui responsabilidade definida.
- Nenhuma entidade realiza interpretação clínica.
- As entidades representam apenas o domínio do sistema.