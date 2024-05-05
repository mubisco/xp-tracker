<?php

namespace XpTracker\Character\Domain;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Shared\Domain\Event\Eventable;

interface Character extends Eventable
{
    public function id(): string;
    public function toJson(): string;
    public function addExperience(Experience $experience): void;
    public function join(Party $party): void;
    public function removeFrom(Party $party): void;
    public function level(): int;
}
