<?php

namespace XpTracker\Encounter\Domain\Projection;

use XpTracker\Encounter\Domain\Encounter;

interface CreatedEncounterProjection
{
    public function projectCreated(Encounter $encounter): void;
}
