<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\UpdateEncounterWriteModel;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class DeleteEncounterCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $repository,
        private readonly UpdateEncounterWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(DeleteEncounterCommand $command): void
    {
        $ulid = $this->parseUlid($command->encounterId);
        $encounter = $this->repository->byEncounterId($ulid);
        $encounter->delete();
        $this->writeModel->update($encounter);
        $this->eventBus->publish($encounter->pullEvents());
    }

    private function parseUlid(string $ulid): SharedUlid
    {
        try {
            return SharedUlid::fromString($ulid);
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
