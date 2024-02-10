<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Character\Domain\AddCharacterWriteModel;

final class FailingAddCharacterWriteModelStub implements AddCharacterWriteModel
{
    public function add(Character $character): void
    {
        throw new AddCharacterWriteModelException('Ops');
    }
}
