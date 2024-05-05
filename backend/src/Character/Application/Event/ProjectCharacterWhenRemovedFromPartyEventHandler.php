<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use XpTracker\Character\Domain\CharacterProjection;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\CharacterWasRemoved;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Application\EventHandlerInterface;

final class ProjectCharacterWhenRemovedFromPartyEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly CharacterRepository $repository,
        private readonly CharacterProjection $projection
    ) {
    }

    public function __invoke(CharacterWasRemoved $event): void
    {
        $ulid = SharedUlid::fromString($event->id());
        $character = $this->repository->byId($ulid);
        ($this->projection)($character);
    }
}
