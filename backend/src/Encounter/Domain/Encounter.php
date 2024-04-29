<?php

namespace XpTracker\Encounter\Domain;

use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Shared\Domain\Event\Eventable;

interface Encounter extends Eventable
{
    public function id(): string;
    public function toJson(): string;
    public function addMonster(EncounterMonster $monster): void;
    public function removeMonster(EncounterMonster $monster): void;
    public function assignToParty(EncounterParty $party): void;
}
