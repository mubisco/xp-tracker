<?php

namespace XpTracker\Encounter\Domain;

interface UpdateEncounterWriteModel
{
    public function updateMonsters(Encounter $update): void;
}
