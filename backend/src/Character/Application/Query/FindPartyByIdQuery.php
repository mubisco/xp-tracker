<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Query;

final class FindPartyByIdQuery
{
    public function __construct(public readonly string $partyId)
    {
    }
}
