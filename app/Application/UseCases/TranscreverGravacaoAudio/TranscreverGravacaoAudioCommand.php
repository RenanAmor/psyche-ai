<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\TranscreverGravacaoAudio;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\GravacaoAudio;

final class TranscreverGravacaoAudioCommand implements CommandInterface
{
    /**
     * @param array<int, array{id: string, texto: string}> $segmentos Um
     *        EventoDiscursivo por segmento, na ordem em que o provedor de
     *        transcrição os detectou (ex.: por pausa/silêncio) — id já
     *        gerado pelo chamador (mesmo padrão de RegistrarEventoDiscursivoCommand).
     */
    public function __construct(
        public readonly Discurso $discurso,
        public readonly GravacaoAudio $gravacao,
        public readonly array $segmentos
    ) {
    }
}
