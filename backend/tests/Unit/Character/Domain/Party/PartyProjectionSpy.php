<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyProjection;

final class PartyProjectionSpy implements PartyProjection
{
    public ?Party $party;

    public function __invoke(Party $party): void
    {
        $this->party = $party;
    }
}
