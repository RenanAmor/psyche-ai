# Repetição — Modelo Observacional

> Camada de Modelo Observacional deste conceito, entre a Biblioteca Teórica e a Representação Computacional na [cadeia de rastreabilidade](../../Arquitetura-Cientifica.md#1-cadeia-de-rastreabilidade-obrigatória). Fundamentação teórica, Aplicação Computacional e Representação Computacional completas em [Biblioteca-Teorica/Conceitos/repeticao.md](../../Biblioteca-Teorica/Conceitos/repeticao.md). Conceito com o maior grau de implementação computacional real de toda a Biblioteca Teórica — ver também [Metonímia](metonimia.md), reclassificação lacaniana da mesma observação.

## Fenômeno observado

Dois ou mais `EventoDiscursivo` com o mesmo conteúdo normalizado, registrados em qualquer Sessão do mesmo Sujeito.

## Evidências observáveis

- recorrência textual (mesmo conteúdo normalizado reaparecendo);
- frequência de ocorrência de um mesmo conteúdo;
- trajeto cronológico de um tema entre Sessões distintas (circuito);
- mudanças discursivas ao redor de um conteúdo que retorna.

## Dados necessários

`EventoDiscursivo.conteudo` normalizado (trim + minúsculas); `Sessao.data` / `EventoDiscursivo.criadoEm` (para o circuito/trajeto).

## Dados opcionais

Nenhum registrado nesta versão.

## Eventos relacionados

Dois ou mais `EventoDiscursivo` com o mesmo conteúdo normalizado, em qualquer Sessão do mesmo Sujeito.

## Limites da observação

O sistema **não pode afirmar**:
- significado;
- intenção;
- desejo;
- significante;
- diagnóstico;
- hipótese clínica.

O sistema nunca afirma por que algo se repete — apenas que se repete (Regra 7, [Regras-Dominio.md](../../Regras-Dominio.md)). Toda leitura de causa da repetição é do analista (Regra 10).

## Observação automática

Sim.

## Organização automática

Sim — `GeradorObservacoes`/`CicloDeObservacaoService` organizam recorrências em `Observacao`; o circuito organiza a ordem cronológica das ocorrências entre Sessões.

## Classificação automática

Não — a recorrência é contada e organizada, nunca classificada quanto a causa ou sentido.

## Confirmação do sujeito

Não diretamente na tela do Sujeito (Regra 11: `/conversa*` nunca expõe isso); `RespostaEcoRecorrenciaService` devolve pergunta-eco ao Sujeito, nunca afirmação.

## Validação do analista

Sim — toda leitura de causa da repetição é do analista.

## Evidências produzidas

`Recorrencia` (conteúdo normalizado + contagem); `Observacao`; `CircuitoRecorrenciaDTO` (trajeto cronológico entre Sessões).

## Componentes envolvidos

- **Motor Freud**: `DetectorRecorrencias` (`detectar()`, `normalizar()`, `detectarCircuito()`), `RecorrenciaMinimaSpecification`.
- **Motor Lacan**: `ReclassificadorLacaniano` (reclassifica a mesma recorrência — ver [Metonímia](metonimia.md)).
- **Memória Discursiva**: `Recorrencia`, `OcorrenciaRecorrencia`, `ObservacaoApplicationService`.
- **Interface do Sujeito**: `RespostaEcoRecorrenciaService` — pergunta-eco ("Você voltou a falar em '%s'. O que vem à mente sobre isso?").
- **Interface do Analista**: `ObservacoesSujeitoController`, `GrafoCircuitoViewModel`, `CircuitoTrajetoComponent`.
- **Timeline**: exibição cronológica na tela de Observações.
- **Circuito Pulsional**: grafo do circuito (D3), rota `/sujeitos/{id}/observacoes/grafo-circuito`.
- **Demais motores**: Discourse Engine; ECO — Estrutura Computacional de Observação.

## Referências cruzadas do projeto

- [Biblioteca-Teorica/Conceitos/repeticao.md](../../Biblioteca-Teorica/Conceitos/repeticao.md)
- [Ontologia-Freud.md](../../Ontologia-Freud.md)
- [Documento-Mestre.md](../../Documento-Mestre.md)
- [Arquitetura-Cientifica.md](../../Arquitetura-Cientifica.md)
- [../README.md](../README.md)
- [../Freud/README.md](../Freud/README.md)
- [../Lacan/README.md](../Lacan/README.md)
