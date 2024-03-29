<?php

namespace XpTracker\Character\Domain\Party;

interface PartyProjection
{
    public function __invoke(Party $party): void;
}
