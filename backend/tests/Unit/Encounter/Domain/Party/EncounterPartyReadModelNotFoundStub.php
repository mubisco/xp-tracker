<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain\Party;

use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\EncounterPartyNotFoundException;
use XpTracker\Encounter\Domain\Party\EncounterPartyReadModel;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EncounterPartyReadModelNotFoundStub implements EncounterPartyReadModel
{
    public function byPartyId(SharedUlid $partyUlid): EncounterParty
    {
        throw new EncounterPartyNotFoundException();
    }
}
