<?php

namespace XpTracker\Tests\Unit\Character\Application\Command;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Command\CreatePartyCommand;
use XpTracker\Character\Application\Command\CreatePartyCommandHandler;
use XpTracker\Character\Domain\Party\PartyAlreadyExistsException;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Tests\Unit\Character\Domain\Party\CreatePartyWriteModelSpy;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingCreatePartyWriteModelStub;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class CreatePartyCommandHandlerTest extends TestCase
{
    private EventBusSpy $eventsBusSpy;
    private FailingCreatePartyWriteModelStub $failingWriteModel;
    private CreatePartyWriteModelSpy $writeModelSpy;
    private CreatePartyCommand $command;

    protected function setUp(): void
    {
        $this->eventsBusSpy = new EventBusSpy();
        $this->failingWriteModel = new FailingCreatePartyWriteModelStub();
        $this->writeModelSpy = new CreatePartyWriteModelSpy();
        $this->command = new CreatePartyCommand('01HR5EHF5W9JYF6PYQJ2TMRHAS', 'asd');
    }

    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new CreatePartyCommandHandler($this->failingWriteModel, $this->eventsBusSpy);
        ($sut)(new CreatePartyCommand('asd', 'asd'));
    }

    public function testItShouldThrowExceptionWhenPartyAlreadyExists(): void
    {
        $this->expectException(PartyAlreadyExistsException::class);
        $sut = new CreatePartyCommandHandler($this->failingWriteModel, $this->eventsBusSpy);
        ($sut)($this->command);
    }

    public function testItShouldCreatePartyProperly(): void
    {
        $sut = new CreatePartyCommandHandler($this->writeModelSpy, $this->eventsBusSpy);
        ($sut)($this->command);
        $this->assertNotNull($this->writeModelSpy->createdParty);
        $this->assertEquals('01HR5EHF5W9JYF6PYQJ2TMRHAS', $this->writeModelSpy->createdParty->id());
    }

    public function testItShouldPublishProperEvents(): void
    {
        $sut = new CreatePartyCommandHandler($this->writeModelSpy, $this->eventsBusSpy);
        ($sut)($this->command);
        $this->assertCount(1, $this->eventsBusSpy->publishedEvents);
        $this->assertInstanceOf(PartyWasCreated::class, $this->eventsBusSpy->publishedEvents[0]);
    }
}
