# Eventos de Domínio — PsycheAI

> Versão 1.0

Este documento define os eventos de domínio do PsycheAI.

Um evento de domínio representa um fato que ocorreu no sistema e que não pode ser desfeito.

Os eventos registram mudanças de estado do domínio, preservando a rastreabilidade das operações.

---

# DiscursoRegistrado

Ocorre quando um novo discurso é registrado no sistema.

---

# SessaoCriada

Ocorre quando uma nova sessão é iniciada.

---

# SessaoEncerrada

Ocorre quando uma sessão é finalizada.

---

# MemoriaAtualizada

Ocorre quando a memória longitudinal recebe uma nova sessão.

---

# EventoDiscursivoRegistrado

Ocorre quando um evento discursivo é identificado e armazenado.

---

# RecorrenciaIdentificada

Ocorre quando uma recorrência é encontrada durante a comparação da memória longitudinal.

---

# ObservacaoProduzida

Ocorre quando o sistema registra uma nova observação baseada em recorrências.

---

# PersistenciaRealizada

Ocorre quando todas as alterações do ciclo operacional são gravadas com sucesso.

---

# Princípios

- Eventos representam fatos consumados.
- Eventos são imutáveis.
- Eventos podem ser auditados.
- Eventos nunca contêm interpretações clínicas.
- Todo evento possui data e hora de ocorrência.
- Eventos preservam a história do domínio.