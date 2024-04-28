<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EncounterRepositoryNotFoundStub implements EncounterRepository
{
    public function byEncounterId(SharedUlid $encounterId): Encounter
    {
        throw new EncounterNotFoundException();
    }
}
