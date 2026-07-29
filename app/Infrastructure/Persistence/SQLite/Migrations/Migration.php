<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

interface Migration
{
    /**
     * Identificador único e ordenável da migration (ex.: '0001').
     */
    public function version(): string;

    public function description(): string;

    public function up(PDO $pdo): void;
}
