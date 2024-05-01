<?php

namespace XpTracker\Encounter\Domain\Party;

use XpTracker\Shared\Domain\Identity\SharedUlid;

interface EncounterPartyReadModel
{
    public function byPartyId(SharedUlid $partyUlid): EncounterParty;
}
