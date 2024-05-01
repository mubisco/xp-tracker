<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\Party\EncounterPartyReadModel;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;
use XpTracker\Encounter\Domain\UpdateEncounterWriteModel;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class AssignPartyToEncounterCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $repository,
        private readonly EncounterPartyReadModel $readModel,
        private readonly UpdateEncounterWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(AssignPartyToEncounterCommand $command): void
    {
        $encounterUlid = $this->parseEncounterUlid($command->encounterUlid);
        $partyUlid = $this->parsePartyUlid($command->partyUlid);
        $encounter = $this->repository->byEncounterId($encounterUlid);
        $party = $this->readModel->byPartyId($partyUlid);
        $encounter->assignToParty($party);
        $this->writeModel->update($encounter);
        $this->eventBus->publish($encounter->pullEvents());
    }

    private function parseEncounterUlid(string $ulid): SharedUlid
    {
        try {
            return SharedUlid::fromString($ulid);
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }
    private function parsePartyUlid(string $ulid): SharedUlid
    {
        try {
            return SharedUlid::fromString($ulid);
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterPartyUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
