# Governança de IA

## Metadados

- **Categoria**: Ética Computacional
- **Tópico**: Governança de Inteligência Artificial (AI Governance)
- **Definição**: Conjunto de estruturas institucionais, políticas, processos de decisão e mecanismos de responsabilização que regulam o desenvolvimento, a implantação e o uso responsável de sistemas de Inteligência Artificial, dentro de uma organização ou em nível regulatório mais amplo.
- **Área científica de origem**: Políticas Públicas / Direito Digital / Ciência da Computação.
- **Referências principais**: OECD (2019). *Recommendation of the Council on Artificial Intelligence*. OECD/LEGAL/0449; European Union (2024). *Regulation (EU) 2024/1689 (Artificial Intelligence Act)*. Official Journal of the European Union.
- **Tópicos relacionados**: [Ética em Inteligência Artificial](etica-em-inteligencia-artificial.md); [Segurança de Dados](seguranca-de-dados.md)
- **Status**: Catalogado
- **Observações**: O EU AI Act (2024) é citado como referência internacional emergente, sem aplicabilidade direta ao PsycheAI nesta data (mesma ressalva de [gdpr.md](gdpr.md)).

## Aplicação no PsycheAI

Fundamenta a estrutura já registrada de política de acesso aos dois Modos de Operação da plataforma ([Arquitetura-Cientifica.md §8.6](../../../Arquitetura-Cientifica.md#86-política-de-acesso)) — uma instância concreta de governança institucional sobre quem pode acessar e operar cada camada do sistema.

## Componentes da Plataforma relacionados

`app/Presentation/Web/Security/PortaoDeAnalista.php` — mecanismo técnico que já implementa parte da governança de acesso descrita em §8.6.

## Relação com a Base Científica

Governança de IA rege quem pode acionar os componentes de extração/qualificação (LLM, ASR) e sob quais condições institucionais — não decide, por si, nenhum critério de relevância clínica.

## Relação com os Motores

Nenhum diretamente — regula o acesso institucional à plataforma como um todo, não o comportamento interno de nenhum Motor.

## Relação com a Representação Computacional

Não alcança diretamente a Representação Computacional.

## Referências cruzadas do projeto

- [README.md](README.md)
- [etica-em-inteligencia-artificial.md](etica-em-inteligencia-artificial.md)
- [../../../Arquitetura-Cientifica.md](../../../Arquitetura-Cientifica.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
