<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use InvalidArgumentException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\InvalidCharacterUlidValueException;
use XpTracker\Character\Domain\Party\CharacterWasAdded;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class UpdateCharacterWhenAddToPartyEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly PartyRepository $partyRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly UpdateCharacterPartyWriteModel $writeModel,
        private readonly EventBus $domainEventBus
    ) {
    }
    public function __invoke(CharacterWasAdded $event): void
    {
        $partyId = $this->extractPartyId($event->id);
        $characterId = $this->extractCharacterId($event->addedCharacterId);
        $party = $this->partyRepository->byUlid($partyId);
        $character = $this->characterRepository->byId($characterId);
        $character->join($party);
        $this->writeModel->updateCharacterParty($character);
        $this->domainEventBus->publish($character->pullEvents());
    }

    private function extractPartyId(string $rawId): SharedUlid
    {
        try {
            return SharedUlid::fromString($rawId);
        } catch (InvalidArgumentException $e) {
            throw new InvalidPartyUlidValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function extractCharacterId(string $rawId): SharedUlid
    {
        try {
            return SharedUlid::fromString($rawId);
        } catch (InvalidArgumentException $e) {
            throw new InvalidCharacterUlidValueException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
