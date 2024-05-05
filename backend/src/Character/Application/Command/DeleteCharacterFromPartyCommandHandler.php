<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\Party\AddCharacterToPartyWriteModel;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class DeleteCharacterFromPartyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PartyRepository $partyRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly AddCharacterToPartyWriteModel $partyWriteModel,
        private readonly EventBus $eventBus
    ) {
    }
    public function __invoke(DeleteCharacterFromPartyCommand $command): void
    {
        $partyUlid = SharedUlid::fromString($command->partyUlid);
        $characterUlid = SharedUlid::fromString($command->characterUlid);
        $party = $this->partyRepository->byUlid($partyUlid);
        $character = $this->characterRepository->byId($characterUlid);
        $party->remove($character);
        $this->partyWriteModel->updateCharacters($party);
        $this->eventBus->publish($party->pullEvents());
    }
}
