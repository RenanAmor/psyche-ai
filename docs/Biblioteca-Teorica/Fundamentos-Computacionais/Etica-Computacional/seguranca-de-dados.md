# Segurança de Dados

## Metadados

- **Categoria**: Ética Computacional
- **Tópico**: Segurança de Dados (Data Security)
- **Definição**: Conjunto de práticas técnicas e organizacionais (controle de acesso, criptografia, gestão de risco) destinadas a proteger dados contra acesso não autorizado, alteração indevida, perda ou vazamento.
- **Área científica de origem**: Segurança da Informação / Ciência da Computação.
- **Referências principais**: ISO/IEC 27001:2022. *Information Security, Cybersecurity and Privacy Protection — Information Security Management Systems — Requirements*. International Organization for Standardization; National Institute of Standards and Technology (2020). *NIST Special Publication 800-53, Rev. 5 — Security and Privacy Controls for Information Systems and Organizations*.
- **Tópicos relacionados**: [LGPD](lgpd.md); [GDPR](gdpr.md); [Anonimização](anonimizacao.md)
- **Status**: Catalogado
- **Observações**: Já mencionada como "padrão máximo de proteção de dados sensíveis" em [Base-Cientifica-v1.0.md, "Limites éticos"](../../../Base-Cientifica-v1.0.md#limites-éticos) — este documento cataloga a fundamentação técnica/normativa geral desse limite já registrado.

## Aplicação no PsycheAI

Fundamenta tecnicamente o controle de acesso já em produção (`PortaoDeAnalista`, desde a Sprint 18) que restringe o acesso a dados de sessão, observações e histórico do Sujeito exclusivamente a Analistas autenticados.

## Componentes da Plataforma relacionados

`app/Presentation/Web/Security/PortaoDeAnalista.php`.

## Relação com a Base Científica

Segurança de dados protege o dado bruto e derivado do Sujeito contra acesso indevido — camada de proteção técnica, anterior e neutra a qualquer decisão de relevância clínica sobre o conteúdo protegido.

## Relação com os Motores

Nenhum diretamente — controle transversal de acesso a toda rota de coleta/análise de dados, não comportamento de um Motor específico.

## Relação com a Representação Computacional

Fundamenta indiretamente a separação de interface Sujeito/Analista ([Arquitetura-Cientifica.md §2](../../../Arquitetura-Cientifica.md#2-separação-de-interface-entre-sujeito-e-analista)), na medida em que o controle de acesso é o mecanismo técnico que garante essa separação na prática.

## Referências cruzadas do projeto

- [README.md](README.md)
- [lgpd.md](lgpd.md)
- [../../../Arquitetura-Cientifica.md](../../../Arquitetura-Cientifica.md)
- [../../../Base-Cientifica-v1.0.md](../../../Base-Cientifica-v1.0.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
