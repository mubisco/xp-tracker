<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain\Projection;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\Projection\CreatedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\ResolvedEncounterProjection;
use XpTracker\Encounter\Domain\Projection\UpdatedEncounterProjection;

final class EncounterProjectionSpy implements
    CreatedEncounterProjection,
    UpdatedEncounterProjection,
    ResolvedEncounterProjection
{
    public ?Encounter $itemToProject = null;

    public function projectCreated(Encounter $encounter): void
    {
        $this->itemToProject = $encounter;
    }

    public function projectUpdate(Encounter $encounter): void
    {
        $this->itemToProject = $encounter;
    }

    public function projectResolved(Encounter $encounter): void
    {
        $this->itemToProject = $encounter;
    }
}
