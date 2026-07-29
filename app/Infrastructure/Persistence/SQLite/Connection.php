<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite;

use PDO;

/**
 * Conexão PDO com um banco SQLite. Cria o diretório e o arquivo do banco
 * automaticamente quando ainda não existem.
 */
final class Connection
{
    private PDO $pdo;

    public function __construct(private readonly string $databasePath)
    {
        if ($this->databasePath !== ':memory:') {
            $directory = dirname($this->databasePath);

            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
        }

        $this->pdo = new PDO('sqlite:' . $this->databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->exec('PRAGMA foreign_keys = ON');
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
