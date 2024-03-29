<?php

namespace XpTracker\Character\Domain;

interface UpdateCharacterPartyWriteModel
{
    public function updateCharacterParty(Character $character): void;
}
