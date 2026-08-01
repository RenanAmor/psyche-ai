# Interface do Sujeito — Psyche AI

> Versão 1.0 — Sprint 28. Especificação da interface do Sujeito na ECO. Cada item indica se já está em produção hoje ou se é especificação para sprint futura — nenhuma tela nova foi criada para produzir este documento.

## O que o sujeito visualiza

A interface do Sujeito é composta exclusivamente por:

- **Conversa** — a tela de diálogo com a ECO (`/conversa`). **Implementado** desde a Sprint 12, com identidade por cookie (Sprint 17), captura de áudio (Sprint 22) e voz de saída (Sprint 24).
- **Histórico de suas sessões** — a possibilidade de o próprio sujeito revisitar o que já foi dito em sessões anteriores, sem qualquer estrutura produzida pelos motores. **Não implementado** nesta versão: a única tela de histórico existente (`/sujeitos/{id}/historico`) fica atrás do Portão do Analista ([Interface-Analista.md](Interface-Analista.md)) e expõe recorrências e observações — inadequada, tal como está hoje, para ser reaproveitada como histórico do próprio sujeito sem antes remover essas estruturas. Especificação para sprint futura.
- **Configurações pessoais** — dados da conta do sujeito (e-mail, senha). **Parcialmente implementado**: cadastro e login existem (`/conversa/cadastro`, `/conversa/entrar`, Sprint 20), mas não há tela de edição de perfil ou preferências pessoais.
- **Consentimentos** — registro do que o sujeito autorizou sobre o tratamento de seus dados (captura de áudio, retenção de histórico, uso do discurso para observação computacional). **Não implementado** nesta versão — especificação para sprint futura, sujeita a decisão explícita do usuário sobre o modelo de consentimento antes de qualquer código.

## O que o sujeito nunca visualiza

Em nenhuma hipótese, em nenhuma versão futura sem decisão explícita revisando este documento e [Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos), o sujeito visualiza:

- **motores** — Discourse Engine, Motor Freud, Motor Lacan, ou qualquer nome/rótulo de componente interno;
- **recorrências** — a estrutura de dados `Recorrencia` ou qualquer contagem/frequência de repetição apresentada como tal;
- **significantes** — nenhuma identificação ou rótulo de significante, cadeia significante ou estatuto de significante confirmado;
- **classificações** — `TipoFormacaoFreudiana`, rótulo lacaniano ("deslize metonímico") ou qualquer categoria estrutural atribuída ao seu discurso;
- **grafos** — o grafo de circuito, o Grafo Integrado ou qualquer visualização topológica das relações entre conceitos ou entre suas próprias falas;
- **estruturas lacanianas** — nenhuma escrita lacaniana, matema ou vocabulário técnico da Ontologia Lacan ([Documento-Mestre.md §5](../Documento-Mestre.md#5-princípios-éticos), "A escrita lacaniana pertence ao analista, não ao sujeito");
- **hipóteses** — nenhuma hipótese clínica, mesmo formulada como possibilidade ou sugestão;
- **observações produzidas pelo sistema** — o objeto `Observacao`, ou qualquer texto derivado dele, nunca é exposto ao sujeito.

Esta lista implementa, do lado do sujeito, o mesmo princípio permanente já registrado em [Arquitetura-Cientifica.md §2](../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista) e na Regra 11 de [Regras-Dominio.md](../Regras-Dominio.md).

## Garantia técnica já existente

A separação não depende de disciplina de prompt isolada: `RespostaSocraticaService`/`GeradorDePerguntaSocraticaLLM` (Sprint 23) nunca recebem como entrada nenhuma leitura do Motor Freud/Lacan — o prompt do sujeito é construído apenas a partir dos turnos recentes da própria sessão, nunca da classificação estrutural. As rotas que expõem recorrências, observações e histórico estruturado (`/sujeitos/{id}/historico`, `/sujeitos/{id}/observacoes`) estão protegidas por `PortaoDeAnalista::proteger()` desde a Sprint 18 e nunca são alcançadas pelas rotas `/conversa*`.

## Referências cruzadas do projeto

- [README.md](README.md)
- [Manifesto.md](Manifesto.md)
- [Fluxo-Conversacional.md](Fluxo-Conversacional.md)
- [Interface-Analista.md](Interface-Analista.md)
- [Limites-da-ECO.md](Limites-da-ECO.md)
- [../Documento-Mestre.md](../Documento-Mestre.md#5-princípios-éticos)
- [../Arquitetura-Cientifica.md](../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)
- [../Regras-Dominio.md](../Regras-Dominio.md)
