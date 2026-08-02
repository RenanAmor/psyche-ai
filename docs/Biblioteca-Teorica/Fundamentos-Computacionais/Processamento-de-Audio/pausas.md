# Pausas

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Pausas (Silence/Pauses in Speech)
- **Definição**: Intervalos de silêncio no fluxo da fala espontânea, estudados na literatura psicolinguística como portadores de informação sobre planejamento cognitivo, hesitação e estrutura do discurso, distintos de ruído ou ausência de sinal por falha técnica.
- **Área científica de origem**: Psicolinguística / Processamento de Fala.
- **Referências principais**: Goldman-Eisler, F. (1968). *Psycholinguistics: Experiments in Spontaneous Speech*. Academic Press. ISBN 978-0-12-289450-3.
- **Tópicos relacionados**: [Voice Activity Detection](voice-activity-detection.md); [Segmentação da Fala](segmentacao-da-fala.md); [Prosódia](prosodia.md)
- **Status**: Catalogado
- **Observações**: A segmentação por silêncio já produzida pelo Whisper (`TranscriptionResultDTO::$segments`, cada segmento com `inicio`/`fim`) usa pausas como critério de corte — mas o PsycheAI não extrai nem armazena a duração da pausa como dado próprio; consome apenas o resultado já segmentado.

## Aplicação no PsycheAI

Fundamenta cientificamente a divisão de uma gravação de sessão inteira em múltiplos `EventoDiscursivo`, um por segmento delimitado por pausa/silêncio — já em produção desde a Sprint 22, através da segmentação nativa do Whisper, sem detector de pausa próprio do PsycheAI.

## Componentes da Plataforma relacionados

`app/Infrastructure/Contracts/DTOs/TranscriptionResultDTO.php` (`$segments`, delimitados por pausa/silêncio pelo provedor Whisper) — a duração da pausa em si não é campo exposto ou persistido.

## Relação com a Base Científica

A pausa, quando qualificada, é dado bruto de segmentação temporal — nunca uma inferência automática de hesitação, resistência ou qualquer leitura clínica, que permanece exclusiva do analista ou do próprio sujeito.

## Relação com os Motores

Discourse Engine depende indiretamente — a fronteira entre `EventoDiscursivo` distintos de uma mesma sessão de voz é definida pela segmentação por pausa do provedor.

## Relação com a Representação Computacional

Alimenta indiretamente a ordenação temporal usada pela Timeline ([../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)), através da fronteira de `EventoDiscursivo` que a segmentação por pausa já define.

## Referências cruzadas do projeto

- [README.md](README.md)
- [voice-activity-detection.md](voice-activity-detection.md)
- [segmentacao-da-fala.md](segmentacao-da-fala.md)
- [../../../Representacao-Computacional/Timeline.md](../../../Representacao-Computacional/Timeline.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
