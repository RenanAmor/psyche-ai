<?php

declare(strict_types=1);

namespace PsycheAI\Application\Contracts;

/**
 * Interface base para todos os Handlers de Caso de Uso.
 *
 * Cada Handler implementa seu próprio método `handle()`, tipado com o
 * Command e o Result específicos do caso de uso (PHP não permite
 * covariância/contravariância estrita o suficiente para declarar essa
 * assinatura de forma genérica aqui). Esta interface serve para marcar e
 * localizar Handlers, seguindo a mesma convenção de DomainServiceInterface.
 */
interface UseCaseInterface
{
}
