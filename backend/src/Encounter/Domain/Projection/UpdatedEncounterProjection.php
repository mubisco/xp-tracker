<?php

namespace XpTracker\Encounter\Domain\Projection;

use XpTracker\Encounter\Domain\Encounter;

interface UpdatedEncounterProjection
{
    public function projectUpdate(Encounter $encounter): void;
}
