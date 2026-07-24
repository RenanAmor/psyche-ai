# Arquitetura — Psyche AI

> Versão 0.1 — Sprint 0 (Fundação)
> Este documento descreve apenas a arquitetura conceitual/estrutural inicial. Detalhamento de componentes de IA, integrações e regras de negócio serão adicionados em sprints futuras.

## 1. Stack tecnológica

- **Linguagem**: PHP 8.2+
- **Gestão de dependências**: Composer
- **Testes**: PHPUnit (a ser configurado quando houver código a testar)
- **Padrão de ecossistema**: alinhado às convenções dos demais projetos L369

## 2. Estrutura de diretórios

```
psyche-ai/
├── app/          # Código-fonte da aplicação
├── config/       # Arquivos de configuração
├── docs/         # Documentação do projeto
├── storage/      # Armazenamento local (logs, cache, arquivos gerados)
├── tests/        # Testes automatizados
├── README.md
├── composer.json
├── .env.example
└── .gitignore
```

## 3. Convenções

- Autoload de classes via **PSR-4**, com o namespace raiz mapeado para `app/`.
- Configurações sensíveis (chaves, credenciais, endpoints) nunca são versionadas — apenas documentadas em `.env.example`.
- `storage/` é destinado a artefatos gerados em tempo de execução e não deve conter código-fonte.

## 4. Visão arquitetural de longo prazo — motores conceituais

O modelo teórico fundamental do projeto (Freud–Lacan, ver [Documento-Mestre.md](Documento-Mestre.md#6-modelo-teórico-fundamental)) se traduz, em visão de longo prazo, em três motores conceituais organizados em pipeline:

```
Freud Engine
     │
     ▼
Lacan Engine
     │
     ▼
Discourse Engine
```

- **Freud Engine** — núcleo conceitual do sistema. Define *o que* é observado: inconsciente, recalque, pulsão, desejo, formação de compromisso, ato falho, chiste, sonhos, repetição, transferência.
- **Lacan Engine** — estrutura de leitura do sistema. Define *como* o discurso é organizado e relacionado: significante, cadeia significante, metáfora, metonímia, registros Simbólico/Imaginário/Real, desejo, falta, Outro, objeto a.
- **Discourse Engine** — componente central do pipeline. Responsável por estruturar o discurso em unidades analisáveis, identificar cadeias de significantes, acompanhar a evolução das sessões, preservar o contexto temporal e alimentar os dois motores teóricos com dados organizados.

Esta é uma visão conceitual preliminar. **Nenhum desses motores é especificado ou implementado na Sprint 0** — o detalhamento técnico (contratos, interfaces, modelo de dados) será tratado em documento de arquitetura técnica dedicado, em sprint futura.

## 5. Estado atual

Nesta fase, o projeto não possui camadas de aplicação, domínio ou infraestrutura definidas — apenas o esqueleto estrutural. A definição de camadas (domínio, aplicação, infraestrutura, IA) será tratada em documento de arquitetura técnica dedicado, em sprint futura.

## 6. Próximos passos

Ver [Roadmap.md](Roadmap.md) para o planejamento das próximas sprints.
