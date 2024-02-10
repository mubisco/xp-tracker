<?php

namespace XpTracker\Character\Domain;

interface AddCharacterWriteModel
{
    public function add(Character $character): void;
}
