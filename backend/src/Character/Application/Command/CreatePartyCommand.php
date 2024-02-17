<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

final class CreatePartyCommand
{
    public function __construct(public readonly string $ulid, public readonly string $name)
    {
    }
}
