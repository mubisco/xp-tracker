<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Party\AddCharacterToPartyWriteModel;
use XpTracker\Character\Domain\Party\Party;

final class AddCharacterToPartyWriteModelSpy implements AddCharacterToPartyWriteModel
{
    public ?Party $party = null;

    public function updateCharacters(Party $party): void
    {
        $this->party = $party;
    }
}
