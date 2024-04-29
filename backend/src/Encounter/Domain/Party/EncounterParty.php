<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Party;

final class EncounterParty
{
    /**
     * @param array<int,int> $charactersLevel
     */
    public function __construct(
        public readonly string $partyUlid,
        public readonly array $charactersLevel
    ) {
    }
}
