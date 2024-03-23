<?php

namespace XpTracker\Tests\Unit\Character\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Command\AddCharacterToPartyCommand;
use XpTracker\Character\Application\Command\AddCharacterToPartyCommandHandler;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\InvalidCharacterUlidValueException;
use XpTracker\Character\Domain\Party\CharacterInAnotherPartyException;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyWriteModelException;
use XpTracker\Tests\Unit\Character\Domain\AddCharacterToPartyWriteModelSpy;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingAddCharacterToPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class AddCharacterToPartyCommandHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongPartyUlid(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $sut = $this->withAllFailingSut();
        $command = new AddCharacterToPartyCommand('1HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
    }

    public function testItShouldThrowExceptionWhenWrongCharacterUlid(): void
    {
        $this->expectException(InvalidCharacterUlidValueException::class);
        $sut = $this->withAllFailingSut();
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '1HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
    }

    public function testItShouldThrowExceptionWhenPartyDoesNotExists(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $sut = $this->withAllFailingSut();
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
    }

    public function testItShouldThrowExceptionWhenCharacterDoesNotExists(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $sut = new AddCharacterToPartyCommandHandler(
            new PartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
    }

    public function testItShouldThrowExceptionWhenPartyCannotbeUpdated(): void
    {
        $this->expectException(PartyWriteModelException::class);
        $sut = new AddCharacterToPartyCommandHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
    }

    public function testItShouldUpdatePartyProperly(): void
    {
        $spy = new AddCharacterToPartyWriteModelSpy();
        $sut = new AddCharacterToPartyCommandHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub(),
            $spy,
            new EventBusSpy()
        );
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
        $this->assertInstanceOf(Party::class, $spy->party);
    }

    public function testItShouldPublishProperEvents(): void
    {
        $spy = new EventBusSpy();
        $sut = new AddCharacterToPartyCommandHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub(),
            new AddCharacterToPartyWriteModelSpy(),
            $spy
        );
        $command = new AddCharacterToPartyCommand('01HSNSXBB8XX6565KHKGFF9J9D', '01HSNSXD9RCRWQ21NTP74DXE8R');
        ($sut)($command);
        $events = $spy->publishedEvents;
        $this->assertCount(2, $events);
    }

    private function withAllFailingSut(): AddCharacterToPartyCommandHandler
    {
        return new AddCharacterToPartyCommandHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
    }
}
