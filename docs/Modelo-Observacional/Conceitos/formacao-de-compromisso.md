# Formação de compromisso — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md](../../Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md). Categoria geral da qual [Ato falho](ato-falho.md), [Chiste](chiste.md) e [Sonhos](sonhos.md) são espécies.

## Fenômeno observado

Um `EventoDiscursivo` apresenta uma das marcas formais associadas às três espécies desta categoria — interrupção/substituição (ato falho), condensação lúdica (chiste) ou relato onírico (sonho) — tratadas, em conjunto, como uma mesma forma discursiva geral.

## Evidências observáveis

- interrupções, autocorreções ou substituições no discurso registrado;
- construções condensadas ou lúdicas;
- relatos de sonho;
- qualquer combinação das evidências específicas das três espécies.

## Dados necessários

`EventoDiscursivo.conteudo`.

## Dados opcionais

`ContextoConversaDTO` (turnos recentes da Sessão) — usado pela ECO (Estrutura Computacional de Observação), não pela classificação em si.

## Eventos relacionados

Mensagem do Sujeito, classificada pelo Motor Freud via LLM com guardrail de enum fechado.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

Reconhecer a forma de uma formação de compromisso não é interpretá-la — o sistema nunca atribui causa ou conteúdo recalcado (Regra 7, [Regras-Dominio.md](../../Regras-Dominio.md)).

## Observação automática

Sim — via classificação estrutural por LLM com guardrail de enum fechado.

## Organização automática

Não — a classificação rotula um `EventoDiscursivo` isolado, não organiza um conjunto.

## Classificação automática

Sim — `TipoFormacaoFreudiana::FormacaoDeCompromisso`, um dos seis valores do enum fechado (os demais: `AtoFalho`, `Chiste`, `Sonho`, `Repeticao`, `NaoClassificado`).

## Confirmação do sujeito

Não diretamente — o Sujeito nunca vê o rótulo (Regra 11).

## Validação do analista

Sim — só as telas do analista exibem o rótulo.

## Evidências produzidas

`TipoFormacaoFreudiana::FormacaoDeCompromisso` (ou `NaoClassificado` quando o guardrail rejeita a resposta do LLM).

## Componentes envolvidos

- **Motor Freud**: `TipoFormacaoFreudiana`, `ClassificadorFreudianoLLM`, `ClassificarFormacaoFreudiana`.
- **Motor Lacan**: rótulo correspondente via `ReclassificadorLacaniano`, quando aplicável.
- **Memória Discursiva**: `EventoDiscursivo`.
- **Interface do Sujeito**: nenhuma.
- **Interface do Analista**: `ObservacoesSujeitoController`, `ObservacaoApplicationService`.
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md](../../Biblioteca-Teorica/Conceitos/formacao-de-compromisso.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
