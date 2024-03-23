<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

final class AddCharacterToPartyCommand
{
    public function __construct(
        public readonly string $partyUlid,
        public readonly string $characterUlid
    ) {
    }
}
