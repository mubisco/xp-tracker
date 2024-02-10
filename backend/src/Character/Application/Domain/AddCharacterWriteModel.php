<?php

namespace XpTracker\Character\Application\Domain;

interface AddCharacterWriteModel
{
    public function add(Character $character): void;
}
