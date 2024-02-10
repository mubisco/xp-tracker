<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

final class AddCharacterCommand
{
    public function __construct(
        public readonly string $ulid,
        public readonly string $characterName,
        public readonly int $experiencePoints,
    ) {
    }
}
