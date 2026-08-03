<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;
use PsycheAI\Domain\Repositories\ListaDeEsperaRepository;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class SQLiteListaDeEsperaRepository implements ListaDeEsperaRepository
{
    private const COLUNAS = 'id, email, nome, profissao, instituicao, pais_estado, motivo_interesse,
                aceitou_politica_privacidade, aceitou_termo_consentimento, criado_em';

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?InscricaoListaDeEspera
    {
        $statement = $this->pdo->prepare(
            'SELECT ' . self::COLUNAS . ' FROM lista_espera WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        $linha = $statement->fetch();

        return $linha === false ? null : $this->hidratar($linha);
    }

    /**
     * @return InscricaoListaDeEspera[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT ' . self::COLUNAS . ' FROM lista_espera ORDER BY criado_em DESC'
        );

        return array_map(
            fn (array $linha): InscricaoListaDeEspera => $this->hidratar($linha),
            $statement->fetchAll()
        );
    }

    public function save(InscricaoListaDeEspera $inscricao): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO lista_espera (
                id, email, nome, profissao, instituicao, pais_estado, motivo_interesse,
                aceitou_politica_privacidade, aceitou_termo_consentimento, criado_em
             )
             VALUES (
                :id, :email, :nome, :profissao, :instituicao, :pais_estado, :motivo_interesse,
                :aceitou_politica_privacidade, :aceitou_termo_consentimento, :criado_em
             )
             ON CONFLICT(id) DO UPDATE SET
                email = excluded.email,
                nome = excluded.nome,
                profissao = excluded.profissao,
                instituicao = excluded.instituicao,
                pais_estado = excluded.pais_estado,
                motivo_interesse = excluded.motivo_interesse,
                aceitou_politica_privacidade = excluded.aceitou_politica_privacidade,
                aceitou_termo_consentimento = excluded.aceitou_termo_consentimento'
        );
        $statement->execute([
            'id' => $inscricao->id()->valor(),
            'email' => $inscricao->email()->valor(),
            'nome' => $inscricao->nome(),
            'profissao' => $inscricao->profissao(),
            'instituicao' => $inscricao->instituicao(),
            'pais_estado' => $inscricao->paisEstado(),
            'motivo_interesse' => $inscricao->motivoInteresse(),
            'aceitou_politica_privacidade' => $inscricao->aceitouPoliticaPrivacidade() ? 1 : 0,
            'aceitou_termo_consentimento' => $inscricao->aceitouTermoConsentimento() ? 1 : 0,
            'criado_em' => $inscricao->criadoEm()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array<string, mixed> $linha
     */
    private function hidratar(array $linha): InscricaoListaDeEspera
    {
        return new InscricaoListaDeEspera(
            new Identificador((string) $linha['id']),
            new Email((string) $linha['email']),
            (string) $linha['nome'],
            $linha['profissao'] !== null ? (string) $linha['profissao'] : null,
            $linha['instituicao'] !== null ? (string) $linha['instituicao'] : null,
            (string) $linha['pais_estado'],
            (string) $linha['motivo_interesse'],
            ((int) $linha['aceitou_politica_privacidade']) === 1,
            ((int) $linha['aceitou_termo_consentimento']) === 1,
            new DateTimeImmutable((string) $linha['criado_em'])
        );
    }
}
