<?php

namespace XpTracker\Character\Application\Domain;

use XpTracker\Shared\Domain\Event\Eventable;

interface Character extends Eventable
{
    public function id(): string;
}
