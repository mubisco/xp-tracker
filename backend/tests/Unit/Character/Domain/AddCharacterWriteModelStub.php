<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Application\Domain\AddCharacterWriteModel;
use XpTracker\Character\Application\Domain\Character;

final class AddCharacterWriteModelStub implements AddCharacterWriteModel
{
    public function add(Character $character): void
    {
    }
}