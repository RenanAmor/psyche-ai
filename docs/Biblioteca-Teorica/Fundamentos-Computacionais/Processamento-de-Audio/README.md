# Processamento de Áudio

> Categoria 2 de [Fundamentos-Computacionais/](../README.md), Sprint 33. Cataloga os fundamentos científicos que permitem ao PsycheAI extrair e qualificar o discurso falado do Sujeito — captura, transcrição e segmentação do sinal de voz em texto e metadados processáveis pelas demais camadas da plataforma.

## Itens catalogados (9)

| Tópico | Definição em uma linha |
|---|---|
| [Processamento Digital de Sinais](processamento-digital-de-sinais.md) | Campo da Engenharia Elétrica/Computação dedicado à manipulação matemática de sinais discretizados, base de todo processamento de áudio. |
| [Reconhecimento Automático de Fala](reconhecimento-automatico-de-fala.md) | Conversão automática de sinal de voz em texto (ASR). |
| [Whisper](whisper.md) | Sistema de ASR robusto multilíngue publicado pela OpenAI (Radford et al., 2022), provedor de transcrição em produção no PsycheAI. |
| [Voice Activity Detection](voice-activity-detection.md) | Detecção de presença/ausência de fala em um sinal de áudio contínuo. |
| [Diarização](diarizacao.md) | Tarefa de responder "quem falou quando" em uma gravação com múltiplos falantes. |
| [Prosódia](prosodia.md) | Estudo dos elementos sonoros da fala além dos fonemas — ritmo, acento, melodia. |
| [Entonação](entonacao.md) | Variação da altura (pitch) da voz ao longo do enunciado, componente da prosódia. |
| [Pausas](pausas.md) | Intervalos de silêncio no fluxo da fala, com valor estrutural na análise do discurso. |
| [Segmentação da Fala](segmentacao-da-fala.md) | Divisão de um áudio contínuo em unidades menores (frases, turnos, sentenças). |

## Estado de implementação nesta data

Auditado contra `app/Infrastructure/AI/` e `app/Infrastructure/Contracts/`: apenas **Reconhecimento Automático de Fala** e **Whisper** têm componente real em produção (`OpenAIWhisperTranscriptionService`, desde a Sprint 22), incluindo segmentação por pausa de silêncio já produzida pelo próprio provedor (`TranscriptionResultDTO::$segments`). Os sete tópicos restantes desta categoria (Processamento Digital de Sinais como fundamentação de base; VAD, Diarização, Prosódia, Entonação, Pausas como conceito autônomo, Segmentação da Fala como componente próprio) não têm implementação dedicada nesta versão — cada documento individual registra isso explicitamente.

## Referências cruzadas do projeto

- [../README.md](../README.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
- [../Indice-Topicos.md](../Indice-Topicos.md)
- [../../../Arquitetura.md](../../../Arquitetura.md)
