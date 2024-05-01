<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain\Projection;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\Projection\CreatedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\EncounterProjectionException;
use XpTracker\Encounter\Domain\Projection\ResolvedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\UpdatedEncounterProjection;

final class EncounterProjectionFailingStub implements
    CreatedEncounterProjection,
    UpdatedEncounterProjection,
    ResolvedEncounterProjection
{
    public function projectCreated(Encounter $encounter): void
    {
        throw new EncounterProjectionException();
    }

    public function projectUpdate(Encounter $encounter): void
    {
        throw new EncounterProjectionException();
    }

    public function projectResolved(Encounter $encounter): void
    {
        throw new EncounterProjectionException();
    }
}
