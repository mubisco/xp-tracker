<?php

namespace XpTracker\Encounter\Domain;

use XpTracker\Shared\Domain\Identity\SharedUlid;

interface EncounterRepository
{
    public function byEncounterId(SharedUlid $encounterId): Encounter;
}
