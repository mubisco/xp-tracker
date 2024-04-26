<?php

namespace XpTracker\Encounter\Domain;

use XpTracker\Shared\Domain\Event\Eventable;

interface Encounter extends Eventable
{
    public function id(): string;
    public function toJson(): string;
}
