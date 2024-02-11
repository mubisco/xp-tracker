<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterProjection;
use XpTracker\Character\Domain\CharacterProjectionException;

final class FailingCharacterProjectionStub implements CharacterProjection
{
    public function __invoke(Character $character): void
    {
        throw new CharacterProjectionException();
    }
}
