<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use XpTracker\Character\Domain\CharacterProjection;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class ProjectCharacterWhenCreatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly CharacterRepository $repository,
        private readonly CharacterProjection $projection
    ) {
    }

    public function __invoke(CharacterWasCreated $event): void
    {
        $ulid = SharedUlid::fromString($event->id);
        $character = $this->repository->byId($ulid);
        ($this->projection)($character);
    }
}
