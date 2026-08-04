<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

enum StatusChamada: string
{
    case Criada = 'criada';
    case EmAndamento = 'em_andamento';
    case Encerrada = 'encerrada';
}
