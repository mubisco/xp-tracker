<?php

namespace XpTracker\Encounter\Domain;

interface UpdateEncounterWriteModel
{
    public function update(Encounter $update): void;
}
