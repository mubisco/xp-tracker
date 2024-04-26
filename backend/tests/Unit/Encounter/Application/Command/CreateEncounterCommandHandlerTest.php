<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;

class CreateEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldBeOfProperClass(): void
    {
        $sut = new CreateEncounterCommandHandler();
        $this->assertInstanceOf(CreateEncounterCommandHandler::class, $sut);
    }
}
