# Voice Activity Detection

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Voice Activity Detection (VAD)
- **Definição**: Tarefa de detectar automaticamente, em um sinal de áudio contínuo, os intervalos em que há fala presente, distinguindo-os de silêncio ou ruído de fundo.
- **Área científica de origem**: Processamento de Sinais / Processamento de Fala.
- **Referências principais**: Sohn, J.; Kim, N. S.; Sung, W. (1999). "A Statistical Model-Based Voice Activity Detection". *IEEE Signal Processing Letters*, 6(1), 1–3. DOI: 10.1109/97.736233.
- **Tópicos relacionados**: [Pausas](pausas.md); [Segmentação da Fala](segmentacao-da-fala.md); [Processamento Digital de Sinais](processamento-digital-de-sinais.md)
- **Status**: Catalogado
- **Observações**: A segmentação por silêncio hoje devolvida pelo Whisper (`TranscriptionResultDTO::$segments`) é funcionalmente próxima ao resultado de um VAD, mas é produzida internamente pelo provedor de ASR, não por um componente de VAD dedicado do PsycheAI.

## Aplicação no PsycheAI

Fundamentação teórica de fundo para qualquer detecção futura de presença/ausência de fala independente da transcrição — capacidade não implementada nesta versão, hoje resolvida indiretamente pela própria segmentação do Whisper.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão.

## Relação com a Base Científica

VAD, se implementado, qualificaria o sinal bruto (onde há fala) antes mesmo da transcrição — etapa estritamente técnica, sem nenhuma decisão de relevância clínica.

## Relação com os Motores

Nenhum diretamente nesta versão.

## Relação com a Representação Computacional

Não alcança a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [whisper.md](whisper.md)
- [pausas.md](pausas.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
