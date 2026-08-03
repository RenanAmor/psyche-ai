<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use PsycheAI\Presentation\Http\HttpException;

final class InscreverListaDeEsperaRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $email,
        public readonly string $nome,
        public readonly ?string $profissao,
        public readonly ?string $instituicao,
        public readonly string $paisEstado,
        public readonly string $motivoInteresse,
        public readonly bool $aceitouPoliticaPrivacidade,
        public readonly bool $aceitouTermoConsentimento
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            email: self::exigirString($dados, 'email'),
            nome: self::exigirString($dados, 'nome'),
            profissao: self::opcionalString($dados, 'profissao'),
            instituicao: self::opcionalString($dados, 'instituicao'),
            paisEstado: self::exigirString($dados, 'paisEstado'),
            motivoInteresse: self::exigirString($dados, 'motivoInteresse'),
            aceitouPoliticaPrivacidade: self::exigirAceite($dados, 'aceitouPoliticaPrivacidade'),
            aceitouTermoConsentimento: self::exigirAceite($dados, 'aceitouTermoConsentimento')
        );
    }

    /**
     * Checkboxes HTML só enviam o campo quando marcados — por isso a
     * ausência do campo é tratada como "não aceitou" em vez de erro
     * estrutural, mas o aceite em si segue obrigatório para a inscrição
     * ser criada (ver InscreverNaListaDeEsperaHandler).
     *
     * @param array<string, mixed> $dados
     */
    private static function exigirAceite(array $dados, string $campo): bool
    {
        $valor = $dados[$campo] ?? null;
        $aceito = $valor === true || $valor === '1' || $valor === 1 || $valor === 'on' || $valor === 'true';

        if (!$aceito) {
            throw HttpException::badRequest(sprintf('É necessário aceitar "%s" para se inscrever.', $campo));
        }

        return true;
    }
}
