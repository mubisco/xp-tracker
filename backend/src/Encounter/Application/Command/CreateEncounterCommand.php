<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

final class CreateEncounterCommand
{
    public function __construct(
        public readonly string $ulid,
        public readonly string $name
    ) {
    }
}
