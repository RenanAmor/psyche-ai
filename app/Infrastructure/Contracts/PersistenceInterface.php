<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta genérica de persistência de registros estruturados, em um nível de
 * abstração mais baixo que os Repositórios do Domínio
 * (`PsycheAI\Domain\Contracts\RepositoryInterface`). Futuras implementações
 * de Repositório na Infraestrutura poderão compor esta porta em vez de
 * acoplar-se diretamente a uma tecnologia de armazenamento.
 */
interface PersistenceInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function find(string $collection, string $id): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function save(string $collection, string $id, array $data): void;

    public function delete(string $collection, string $id): void;

    public function exists(string $collection, string $id): bool;
}
