# LGPD

## Metadados

- **Categoria**: Ética Computacional
- **Tópico**: LGPD — Lei Geral de Proteção de Dados Pessoais
- **Definição**: Marco legal brasileiro (Lei nº 13.709, de 14 de agosto de 2018) que regula o tratamento de dados pessoais por pessoas físicas e jurídicas, públicas e privadas, estabelecendo bases legais para o tratamento, direitos do titular e obrigações do controlador/operador de dados.
- **Área científica de origem**: Direito Digital / Governança de Dados.
- **Referências principais**: Brasil. *Lei nº 13.709, de 14 de agosto de 2018* (Lei Geral de Proteção de Dados Pessoais). Presidência da República, Casa Civil, 2018.
- **Tópicos relacionados**: [GDPR](gdpr.md); [Consentimento](consentimento.md); [Anonimização](anonimizacao.md); [Segurança de Dados](seguranca-de-dados.md)
- **Status**: Catalogado
- **Observações**: Norma aplicável diretamente ao PsycheAI por operar no Brasil e tratar dados de discurso potencialmente sensíveis (art. 5º, II, LGPD — "dado pessoal sensível" inclui dado referente à saúde). Nenhuma auditoria de conformidade LGPD foi conduzida nesta Sprint — catalogação exclusivamente da fundamentação legal, sem certificação de conformidade.

## Aplicação no PsycheAI

Fundamenta legalmente toda a exigência de consentimento, finalidade específica, minimização e segurança já registrada como princípio ético em [Base-Cientifica-v1.0.md, "Limites éticos"](../../../Base-Cientifica-v1.0.md#limites-éticos) ("privacidade e confidencialidade como padrão máximo de proteção de dados sensíveis").

## Componentes da Plataforma relacionados

Nenhum implementado nesta versão como conformidade LGPD certificada — controle de acesso já existente (`PortaoDeAnalista`) é medida de segurança correlata, não uma implementação de conformidade LGPD completa.

## Relação com a Base Científica

A LGPD estabelece a moldura legal mínima de tratamento do dado de discurso do Sujeito — não decide nenhum critério de relevância clínica; rege exclusivamente a legalidade do tratamento do dado, camada anterior e distinta da Fundamentação Psicanalítica.

## Relação com os Motores

Nenhum diretamente — aplica-se ao tratamento de dado de forma transversal a toda a plataforma, não a um Motor específico.

## Relação com a Representação Computacional

Não alcança a Representação Computacional.

## Referências cruzadas do projeto

- [README.md](README.md)
- [gdpr.md](gdpr.md)
- [consentimento.md](consentimento.md)
- [../../../Base-Cientifica-v1.0.md](../../../Base-Cientifica-v1.0.md)
- [../Modelo-de-Documento.md](../Modelo-de-Documento.md)
