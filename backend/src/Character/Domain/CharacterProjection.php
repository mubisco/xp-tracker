<?php

namespace XpTracker\Character\Domain;

interface CharacterProjection
{
    public function __invoke(Character $character): void;
}
