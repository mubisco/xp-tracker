<?php

namespace XpTracker\Character\Domain\Party;

use XpTracker\Shared\Domain\Identity\SharedUlid;

interface PartyRepository
{
    public function byUlid(SharedUlid $ulid): Party;
}
