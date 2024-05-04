<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

final class UpdateAssignedPartyCommand
{
    public function __construct(
        public readonly string $encounterUlid,
        public readonly string $partyUlid
    ) {
    }
}
