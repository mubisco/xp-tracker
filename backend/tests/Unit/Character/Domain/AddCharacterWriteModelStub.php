<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\AddCharacterWriteModel;
use XpTracker\Character\Domain\Character;

final class AddCharacterWriteModelStub implements AddCharacterWriteModel
{
    public function add(Character $character): void
    {
    }
}
