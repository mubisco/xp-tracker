<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\CreatePartyWriteModel;
use XpTracker\Character\Domain\Party\Party;

final class CreatePartyWriteModelSpy implements CreatePartyWriteModel
{
    public ?Party $createdParty = null;

    public function createParty(Party $party): void
    {
        $this->createdParty = $party;
        return;
    }
}
