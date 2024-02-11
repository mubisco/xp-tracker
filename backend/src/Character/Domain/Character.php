<?php

namespace XpTracker\Character\Domain;

use XpTracker\Shared\Domain\Event\Eventable;

interface Character extends Eventable
{
    public function id(): string;
    public function toJson(): string;
    public function updateExperience(Experience $experience): void;
}
