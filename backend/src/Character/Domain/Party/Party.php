<?php

namespace XpTracker\Character\Domain\Party;

use XpTracker\Character\Domain\Character;
use XpTracker\Shared\Domain\Event\Eventable;

interface Party extends Eventable
{
    public function id(): string;
    public function toJson(): string;
    public function add(Character $character): void;
}
