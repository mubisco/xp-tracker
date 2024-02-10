<?php

namespace XpTracker\Tests\Unit\Character\Application\Command;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Command\AddCharacterCommand;
use XpTracker\Character\Application\Command\AddCharacterCommandHandler;
use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Tests\Unit\Character\Domain\AddCharacterWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\FailingAddCharacterWriteModelStub;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class AddCharacterCommandHandlerTest extends TestCase
{
    private EventBusSpy $eventBus;

    protected function setUp(): void
    {
        $this->eventBus = new EventBusSpy();
    }

    public function testItShouldThrowExceptionWhenIdHasWrongFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new AddCharacterCommandHandler(
            new FailingAddCharacterWriteModelStub(),
            $this->eventBus
        );
        ($sut)(new AddCharacterCommand('ulid', 'Darling', 0));
    }

    public function testItShouldThrowExceptionWhenCharacterCannotBeStored(): void
    {
        $this->expectException(AddCharacterWriteModelException::class);
        $sut = new AddCharacterCommandHandler(
            new FailingAddCharacterWriteModelStub(),
            $this->eventBus
        );
        ($sut)(new AddCharacterCommand('01HPAM1ZGM97Y4GFXM3QRQFTDV', 'Darling', 0));
    }

    public function testItShouldPublishEventsWhenEverythingIsOk(): void
    {
        $sut = new AddCharacterCommandHandler(
            new AddCharacterWriteModelStub(),
            $this->eventBus
        );
        ($sut)(new AddCharacterCommand('01HPAM1ZGM97Y4GFXM3QRQFTDV', 'Darling', 0));
        $this->assertCount(1, $this->eventBus->publishedEvents);
    }
}
