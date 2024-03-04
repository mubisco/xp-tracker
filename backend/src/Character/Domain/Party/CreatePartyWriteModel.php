<?php

namespace XpTracker\Character\Domain\Party;

interface CreatePartyWriteModel
{
    public function createParty(Party $party): void;
}
