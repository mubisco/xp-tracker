<?php

namespace XpTracker\Encounter\Domain\Projection;

use XpTracker\Encounter\Domain\Encounter;

interface ResolvedEncounterProjection
{
    public function projectResolved(Encounter $encounter): void;
}
