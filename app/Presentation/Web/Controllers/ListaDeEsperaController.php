<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Cadastro público na lista de espera da ECO — sem Portão nenhum,
 * embutido no próprio ponto de atrito de `entrar-participante.php`: quem
 * esbarra no login sem ter conta (sem autocadastro público) pode ao
 * menos registrar interesse. Como `AutenticacaoParticipanteController`,
 * nunca fala com o banco diretamente — só chama `POST /waitlist` na API.
 */
final class ListaDeEsperaController
{
    private const ROTA_ENTRAR_PARTICIPANTE = '/participante/entrar';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function inscrever(Request $request): Response
    {
        $resposta = $this->httpClient->post('waitlist', [
            'email' => (string) $request->input('email', ''),
            'nome' => (string) $request->input('nome', ''),
            'profissao' => (string) $request->input('profissao', ''),
            'instituicao' => (string) $request->input('instituicao', ''),
            'paisEstado' => (string) $request->input('paisEstado', ''),
            'motivoInteresse' => (string) $request->input('motivoInteresse', ''),
            'aceitouPoliticaPrivacidade' => (string) $request->input('aceitouPoliticaPrivacidade', ''),
            'aceitouTermoConsentimento' => (string) $request->input('aceitouTermoConsentimento', ''),
        ]);

        return Response::redirecionar(
            self::ROTA_ENTRAR_PARTICIPANTE . ($resposta->sucesso ? '?inscrito=1' : '?erro_lista_espera=1')
        );
    }
}
