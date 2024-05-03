<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Query;

final class EncountersByPartyUlidQuery
{
    public function __construct(public readonly string $partyUlid)
    {
    }
}
