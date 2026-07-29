<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Contracts;

/**
 * Marcador comum aos Repositórios de Domínio. Cada Repositório declara,
 * em sua própria interface, as assinaturas de `save`, `remove` e
 * `findById` tipadas para a Entidade que gerencia — parâmetros `object`
 * genéricos aqui violariam a variância de tipos do PHP ao serem
 * restringidos para um tipo concreto nas interfaces filhas.
 */
interface RepositoryInterface
{
}