<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class PartyRepositoryStub implements PartyRepository
{
    public function __construct(private readonly ?Party $party = null)
    {
    }

    public function byUlid(SharedUlid $ulid): Party
    {
        if (null === $this->party) {
            return PartyOM::aBuilder()->build();
        }
        return $this->party;
    }
}
