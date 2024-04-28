<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EncounterRepositoryStub implements EncounterRepository
{
    public function byEncounterId(SharedUlid $encounterId): Encounter
    {
        return EncounterOM::random();
    }
}
