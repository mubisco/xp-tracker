<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\CreatePartyWriteModel;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;

final class CreatePartyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CreatePartyWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(CreatePartyCommand $command): void
    {
        $party = BasicParty::create($command->ulid, $command->name);
        $this->writeModel->createParty($party);
        $this->eventBus->publish($party->pullEvents());
    }
}
