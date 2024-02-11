<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\CharacterProjection;

final class CharacterProjectionSpy implements CharacterProjection
{
    public bool $projected = false;

    public function __invoke(Character $character): void
    {
        $this->projected = true;
    }
}
