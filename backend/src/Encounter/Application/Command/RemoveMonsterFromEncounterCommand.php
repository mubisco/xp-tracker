<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

final class RemoveMonsterFromEncounterCommand
{
    public function __construct(
        public readonly string $encounterUlid,
        public readonly string $monsterName,
        public readonly string $challengeRating,
    ) {
    }
}
