# Reconhecimento Automático de Fala

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Reconhecimento Automático de Fala (Automatic Speech Recognition, ASR)
- **Definição**: Tarefa computacional de converter um sinal de áudio contendo fala humana em texto correspondente, tradicionalmente decomposta em modelagem acústica, modelagem de linguagem e decodificação, hoje frequentemente unificada em modelos neurais fim-a-fim.
- **Área científica de origem**: Processamento de Sinais / Aprendizado de Máquina.
- **Referências principais**: Rabiner, L. R.; Juang, B.-H. (1993). *Fundamentals of Speech Recognition*. Prentice Hall. ISBN 978-0-13-015157-9; Jurafsky, D.; Martin, J. H. (2023). *Speech and Language Processing* (3rd ed. draft), cap. sobre ASR.
- **Tópicos relacionados**: [Whisper](whisper.md); [Processamento Digital de Sinais](processamento-digital-de-sinais.md); [Segmentação da Fala](segmentacao-da-fala.md)
- **Status**: Catalogado
- **Observações**: Único tópico desta categoria, junto com Whisper, com implementação real e auditada em produção nesta data.

## Aplicação no PsycheAI

Fundamenta cientificamente a etapa de transcrição verbatim do áudio da sessão em texto — porta de entrada obrigatória de todo discurso capturado por voz para as demais camadas de extração/qualificação (NLP) e, em seguida, para a Fundamentação Psicanalítica.

## Componentes da Plataforma relacionados

`app/Infrastructure/AI/OpenAIWhisperTranscriptionService.php`; `app/Infrastructure/Contracts/TranscriptionInterface.php`; `app/Infrastructure/Contracts/DTOs/TranscriptionResultDTO.php` — em produção desde a Sprint 22.

## Relação com a Base Científica

ASR extrai e qualifica o dado textual a partir do áudio — a decisão sobre o que, nesse texto, é clinicamente relevante permanece exclusiva da Fundamentação Psicanalítica, que só recebe o texto já transcrito, nunca o áudio bruto.

## Relação com os Motores

Discourse Engine depende diretamente — todo `EventoDiscursivo` originado de sessão por voz (Modo 1) nasce de uma transcrição ASR. Freud Engine e Lacan Engine dependem indiretamente, por consumirem o texto já transcrito.

## Relação com a Representação Computacional

Alimenta indiretamente a Timeline ([../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)), cujo dado de origem é o `EventoDiscursivo` produzido a partir da transcrição.

## Referências cruzadas do projeto

- [README.md](README.md)
- [whisper.md](whisper.md)
- [../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
