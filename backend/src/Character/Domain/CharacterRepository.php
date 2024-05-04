<?php

namespace XpTracker\Character\Domain;

use XpTracker\Shared\Domain\Identity\SharedUlid;

interface CharacterRepository
{
    public function byId(SharedUlid $ulid): Character;
    /** @return array<int,Character> */
    public function ofPartyId(SharedUlid $ulid): array;
}
