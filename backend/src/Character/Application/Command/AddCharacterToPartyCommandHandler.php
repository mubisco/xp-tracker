<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

use XpTracker\Shared\Application\CommandHandlerInterface;

final class AddCharacterToPartyCommandHandler implements CommandHandlerInterface
{
    public function __invoke(AddCharacterToPartyCommand $command): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }
}
