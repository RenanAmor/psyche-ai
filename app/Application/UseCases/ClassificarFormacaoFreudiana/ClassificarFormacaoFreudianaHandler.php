<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Infrastructure\Contracts\ClassificadorEstruturalInterface;

/**
 * Sem valor default para $classificador: ao contrário de
 * DetectorRecorrenciasHandler (que instancia um Domain Service puro por
 * padrão), a implementação concreta aqui fala com uma API externa e
 * precisa de credencial — instanciá-la implicitamente esconderia essa
 * dependência. Quem monta este Handler (o composition root, quando a
 * Presentation for construída) decide explicitamente qual
 * ClassificadorEstruturalInterface injetar.
 */
final class ClassificarFormacaoFreudianaHandler implements UseCaseInterface
{
    public function __construct(
        private readonly ClassificadorEstruturalInterface $classificador
    ) {
    }

    public function handle(ClassificarFormacaoFreudianaCommand $command): ClassificarFormacaoFreudianaResult
    {
        $tipo = $this->classificador->classificar($command->conteudo);

        return new ClassificarFormacaoFreudianaResult($tipo);
    }
}
