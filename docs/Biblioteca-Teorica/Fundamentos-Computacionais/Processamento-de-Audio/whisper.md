# Whisper

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Whisper
- **Definição**: Sistema de reconhecimento automático de fala multilíngue e multitarefa (transcrição e tradução), treinado por supervisão fraca em larga escala (680.000 horas de áudio), publicado pela OpenAI.
- **Área científica de origem**: Aprendizado de Máquina / Processamento de Fala.
- **Referências principais**: Radford, A.; Kim, J. W.; Xu, T.; Brockman, G.; McLeavey, C.; Sutskever, I. (2022). "Robust Speech Recognition via Large-Scale Weak Supervision". arXiv:2212.04356.
- **Tópicos relacionados**: [Reconhecimento Automático de Fala](reconhecimento-automatico-de-fala.md); [Segmentação da Fala](segmentacao-da-fala.md); [Pausas](pausas.md)
- **Status**: Catalogado
- **Observações**: O PsycheAI consome o Whisper através da API da OpenAI (`OpenAIWhisperTranscriptionService`) — não hospeda nem treina o modelo.

## Aplicação no PsycheAI

Provedor real de transcrição verbatim do áudio de sessão desde a Sprint 22 — recebe a gravação, devolve texto e, quando aplicável, segmentos com marcação de início/fim (`inicio`, `fim` em `TranscriptionResultDTO::$segments`), usados para dividir uma gravação de sessão inteira em múltiplos `EventoDiscursivo`.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/OpenAIWhisperTranscriptionService.php`; `app/Infrastructure/Contracts/DTOs/TranscriptionResultDTO.php`.

## Relação com a Base Científica

O Whisper extrai e qualifica o texto e sua segmentação temporal a partir do áudio bruto — não realiza nenhuma leitura clínica do conteúdo transcrito; essa leitura permanece exclusiva da Fundamentação Psicanalítica, aplicada apenas depois que o texto já chegou ao Domínio como `EventoDiscursivo`.

## Relação com os Motores

Discourse Engine depende diretamente — é a fonte de todo `EventoDiscursivo` originado de voz no Modo 1. Freud Engine e Lacan Engine dependem indiretamente.

## Relação com a Representação Computacional

Os segmentos com marcação temporal produzidos pelo Whisper alimentam indiretamente a ordenação cronológica usada pela Timeline ([../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)).

## Referências cruzadas do projeto

- [README.md](README.md)
- [reconhecimento-automatico-de-fala.md](reconhecimento-automatico-de-fala.md)
- [segmentacao-da-fala.md](segmentacao-da-fala.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
