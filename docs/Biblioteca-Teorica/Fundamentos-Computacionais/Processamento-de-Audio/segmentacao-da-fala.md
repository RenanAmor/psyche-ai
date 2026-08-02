# Segmentação da Fala

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Segmentação da Fala (Speech Segmentation)
- **Definição**: Divisão de um sinal de áudio contínuo, ou de sua transcrição, em unidades menores (turnos, sentenças, tópicos) para viabilizar processamento e análise em granularidade adequada.
- **Área científica de origem**: Processamento de Fala / Linguística Computacional.
- **Referências principais**: Shriberg, E.; Stolcke, A.; Hakkani-Tür, D.; Tür, G. (2000). "Prosody-Based Automatic Segmentation of Speech into Sentences and Topics". *Speech Communication*, 32(1–2), 127–154. DOI: 10.1016/S0167-6393(00)00028-5.
- **Tópicos relacionados**: [Pausas](pausas.md); [Voice Activity Detection](voice-activity-detection.md); [Whisper](whisper.md)
- **Status**: Catalogado
- **Observações**: Já operacionalizado na plataforma, através da segmentação nativa do provedor Whisper — não por um segmentador desenvolvido pelo PsycheAI.

## Aplicação no PsycheAI

Fundamenta cientificamente a divisão de uma `GravacaoAudio` de sessão inteira em múltiplos `EventoDiscursivo`, um por segmento — já em produção desde a Sprint 22, evitando a necessidade de um segmentador próprio ao reaproveitar a segmentação já devolvida pelo Whisper.

## Componentes da Plataforma relacionados

`app/Infrastructure/Contracts/DTOs/TranscriptionResultDTO.php` (`$segments`); consumido pelo fluxo de ingestão de áudio da Sprint 22 para criar múltiplos `EventoDiscursivo` a partir de uma única gravação.

## Relação com a Base Científica

A segmentação delimita a unidade mínima de dado (o `EventoDiscursivo`) sobre a qual a Fundamentação Psicanalítica opera — decide apenas fronteiras temporais, nunca relevância clínica de conteúdo.

## Relação com os Motores

Discourse Engine depende diretamente — a granularidade de `EventoDiscursivo` sobre a qual `DetectorRecorrencias` opera é definida por esta segmentação quando a origem é voz.

## Relação com a Representação Computacional

Alimenta diretamente a Timeline ([../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)), cuja unidade de exibição cronológica é o `EventoDiscursivo` já segmentado.

## Referências cruzadas do projeto

- [README.md](README.md)
- [whisper.md](whisper.md)
- [pausas.md](pausas.md)
- [../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
