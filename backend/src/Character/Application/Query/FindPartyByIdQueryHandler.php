<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Query;

use InvalidArgumentException;
use XpTracker\Character\Domain\Party\FullPartyReadModel;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class FindPartyByIdQueryHandler
{
    public function __construct(private readonly FullPartyReadModel $readModel)
    {
    }

    /** @return array<string,mixed> */
    public function __invoke(FindPartyByIdQuery $query): array
    {
        try {
            $sharedUlid = SharedUlid::fromString($query->partyId);
            return $this->readModel->byUlid($sharedUlid);
        } catch (InvalidArgumentException $e) {
            throw new InvalidPartyUlidValueException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
