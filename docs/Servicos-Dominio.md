# Serviços de Domínio — PsycheAI

> Versão 1.0

Este documento define os serviços de domínio do PsycheAI.

Serviços de domínio representam comportamentos que não pertencem exclusivamente a uma única entidade.

---

# RegistroDeDiscursoService

Responsável por registrar um novo discurso.

### Responsabilidades

- validar a entrada;
- criar o discurso;
- associar o discurso à sessão.

---

# OrganizacaoDeSessaoService

Responsável por manter a estrutura das sessões.

### Responsabilidades

- iniciar sessão;
- encerrar sessão;
- organizar discursos cronologicamente.

---

# MemoriaLongitudinalService

Responsável por manter o histórico completo do sujeito.

### Responsabilidades

- atualizar memória;
- recuperar histórico;
- preservar ordem cronológica.

---

# IdentificacaoDeRecorrenciasService

Responsável por localizar recorrências na memória longitudinal.

### Responsabilidades

- comparar eventos discursivos;
- identificar repetições;
- registrar recorrências.

---

# GeracaoDeObservacoesService

Responsável por produzir observações a partir das recorrências.

### Responsabilidades

- registrar fatos observáveis;
- associar evidências;
- impedir interpretações clínicas.

---

# PersistenciaService

Responsável por garantir o armazenamento das informações produzidas pelo ciclo do sistema.

### Responsabilidades

- salvar entidades;
- preservar consistência;
- registrar eventos de domínio.

---

# Princípios

- Serviços representam comportamento do domínio.
- Serviços não armazenam estado.
- Serviços utilizam entidades e value objects.
- Serviços nunca produzem diagnósticos.
- Serviços nunca realizam interpretação clínica.