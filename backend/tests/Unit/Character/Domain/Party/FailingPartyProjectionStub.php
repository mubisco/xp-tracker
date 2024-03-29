<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyProjection;
use XpTracker\Character\Domain\Party\PartyProjectionException;

final class FailingPartyProjectionStub implements PartyProjection
{
    public function __invoke(Party $party): void
    {
        throw new PartyProjectionException();
    }
}
