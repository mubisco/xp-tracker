<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Query;

class FindPartyByIdQueryHandler
{
    public function __invoke(FindPartyByIdQuery $query): array
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }
}
