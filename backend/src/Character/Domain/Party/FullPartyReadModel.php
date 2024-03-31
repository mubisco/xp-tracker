<?php

namespace XpTracker\Character\Domain\Party;

use XpTracker\Shared\Domain\Identity\SharedUlid;

interface FullPartyReadModel
{
    /** @return array<string,mixed> */
    public function byUlid(SharedUlid $ulid): array;
}
