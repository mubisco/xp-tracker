<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

use InvalidArgumentException;
use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\InvalidCharacterUlidValueException;
use XpTracker\Character\Domain\Party\AddCharacterToPartyWriteModel;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class AddCharacterToPartyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PartyRepository $partyRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly AddCharacterToPartyWriteModel $writeModel
    ) {
    }

    public function __invoke(AddCharacterToPartyCommand $command): void
    {
        $partyUlid = $this->parsePartyUlid($command->partyUlid);
        $characterUlid = $this->parseCharacterUlid($command->characterUlid);
        $party = $this->partyRepository->byUlid($partyUlid);
        $this->characterRepository->byId($characterUlid);
        // throw new CharacterInAnotherPartyException();
        $this->writeModel->updateCharacters($party);
    }

    private function parsePartyUlid(string $rawUlid): SharedUlid
    {
        try {
            return SharedUlid::fromString($rawUlid);
        } catch (InvalidArgumentException) {
            throw new InvalidPartyUlidValueException("PartyUlid provided of {$rawUlid} is not valid!!!");
        }

    }

    private function parseCharacterUlid(string $rawUlid): SharedUlid
    {
        try {
            return SharedUlid::fromString($rawUlid);
        } catch (InvalidArgumentException) {
            throw new InvalidCharacterUlidValueException("CharacterUlid provided of {$rawUlid} is not valid!!!");
        }
    }
}
