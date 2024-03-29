<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use InvalidArgumentException;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyProjection;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class ProjectPartyWhenCreatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly PartyRepository $repository,
        private readonly PartyProjection $projection
    ) {
    }

    public function __invoke(PartyWasCreated $event): void
    {
        $partyId = $this->parsePartyId($event->id());
        $party = $this->repository->byUlid($partyId);
        ($this->projection)($party);
    }

    private function parsePartyId(string $rawId): SharedUlid
    {
        try {
            return SharedUlid::fromString($rawId);
        } catch (InvalidArgumentException $e) {
            throw new InvalidPartyUlidValueException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
