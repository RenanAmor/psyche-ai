# Diarização

## Metadados

- **Categoria**: Processamento de Áudio
- **Tópico**: Diarização de Falantes (Speaker Diarization)
- **Definição**: Tarefa de particionar uma gravação de áudio contendo múltiplos falantes em segmentos atribuídos a cada falante, respondendo à pergunta "quem falou quando", sem necessariamente identificar a identidade nominal de cada voz.
- **Área científica de origem**: Processamento de Fala / Aprendizado de Máquina.
- **Referências principais**: Anguera, X.; Bozonnet, S.; Evans, N.; Fredouille, C.; Friedland, G.; Vinyals, O. (2012). "Speaker Diarization: A Review of Recent Research". *IEEE Transactions on Audio, Speech, and Language Processing*, 20(2), 356–370. DOI: 10.1109/TASL.2011.2125954.
- **Tópicos relacionados**: [Reconhecimento Automático de Fala](reconhecimento-automatico-de-fala.md); [Segmentação da Fala](segmentacao-da-fala.md)
- **Status**: Catalogado
- **Observações**: Relevante sobretudo para o Modo 2 — Laboratório (material importado, potencialmente multi-falante, ex.: sessão gravada com Sujeito e Analista) — o Modo 1 (ECO) já distingue estruturalmente enunciado do Sujeito e resposta da ECO por design do fluxo conversacional, sem necessidade de diarização acústica.

## Aplicação no PsycheAI

Fundamentação teórica de fundo para o Modo 2 — Laboratório ([Arquitetura-Cientifica.md §8.2](../../../Arquitetura-Cientifica.md#82-modo-2--laboratório-destinado-a-profissionais-e-pesquisadores)), especificado sem implementação: quando material discursivo importado (gravação de sessão clínica) contiver múltiplos falantes, a diarização seria necessária para separar corretamente a fala do Sujeito da fala do profissional antes de qualquer observação computacional.

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão — o Modo 2 em si é especificado sem implementação (ver [Arquitetura-Cientifica.md §8.2](../../../Arquitetura-Cientifica.md#82-modo-2--laboratório-destinado-a-profissionais-e-pesquisadores)).

## Relação com a Base Científica

Diarização qualificaria o dado de áudio (atribuição de segmento a falante) antes da transcrição — etapa técnica que precede qualquer decisão de relevância clínica, e que se torna pré-requisito de qualidade quando o discurso do Sujeito precisa ser isolado do de terceiros.

## Relação com os Motores

Nenhum diretamente nesta versão — pré-requisito potencial do Discourse Engine no Modo 2, quando este for implementado.

## Relação com a Representação Computacional

Não alcança a Representação Computacional nesta versão.

## Referências cruzadas do projeto

- [README.md](README.md)
- [reconhecimento-automatico-de-fala.md](reconhecimento-automatico-de-fala.md)
- [../../../Arquitetura-Cientifica.md](../../../Arquitetura-Cientifica.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
