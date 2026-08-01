# Chiste — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/chiste.md](../../Biblioteca-Teorica/Conceitos/chiste.md).

## Fenômeno observado

Um `EventoDiscursivo` cujo conteúdo apresenta uma construção verbal condensada — jogo de palavras, duplo sentido, condensação formal — produzida em relação a um interlocutor.

## Evidências observáveis

- mudanças de tom discursivo (registro mais leve ou lúdico em relação ao restante da conversa);
- jogos de palavras ou duplos sentidos no conteúdo registrado;
- construções que condensam dois sentidos numa mesma expressão;
- comentário do próprio Sujeito sobre o efeito cômico do que disse.

## Dados necessários

`EventoDiscursivo.conteudo`.

## Dados opcionais

Turnos anteriores da Sessão (contexto de enunciação) — o chiste é relacional, nunca isolado.

## Eventos relacionados

Mensagem do Sujeito classificada pelo Motor Freud; reclassificação subsequente pelo Motor Lacan.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema reconhece a forma, nunca o efeito de prazer nem o conteúdo recalcado que o chiste expressaria ([Ontologia-Freud.md §5](../../Ontologia-Freud.md#5-limites)).

## Observação automática

Sim.

## Organização automática

Não.

## Classificação automática

Sim (Motor Freud, via LLM) e reclassificado em seguida (Motor Lacan, tabela determinística, sem LLM).

## Confirmação do sujeito

Não diretamente.

## Validação do analista

Sim.

## Evidências produzidas

`TipoFormacaoFreudiana::Chiste` + rótulo lacaniano correspondente quando `comLeituraLacaniana=true`.

## Componentes envolvidos

- **Motor Freud**: `ClassificadorFreudianoLLM`, `TipoFormacaoFreudiana`.
- **Motor Lacan**: `ReclassificadorLacaniano::reclassificarPorTipoFreudiano()`.
- **Memória Discursiva**: `EventoDiscursivo`.
- **Interface do Sujeito**: nenhuma — a classificação nunca compõe a resposta ao Sujeito (Regra 11).
- **Interface do Analista**: `ObservacoesSujeitoController`, coluna "Leitura Lacaniana".
- **Timeline**: nenhum.
- **Circuito Pulsional**: nenhum.
- **Demais motores**: nenhum.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/chiste.md](../../Biblioteca-Teorica/Conceitos/chiste.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
