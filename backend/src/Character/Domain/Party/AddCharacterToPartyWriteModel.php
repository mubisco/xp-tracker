<?php

namespace XpTracker\Character\Domain\Party;

interface AddCharacterToPartyWriteModel
{
    public function updateCharacters(Party $party): void;
}
