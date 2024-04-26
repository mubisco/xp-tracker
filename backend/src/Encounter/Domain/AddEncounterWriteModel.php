<?php

namespace XpTracker\Encounter\Domain;

use XpTracker\Encounter\Domain\Encounter;

interface AddEncounterWriteModel
{
    public function store(Encounter $encounter): void;
}
