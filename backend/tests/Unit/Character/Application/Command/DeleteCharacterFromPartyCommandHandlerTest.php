<?php

namespace XpTracker\Tests\Unit\Character\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Command\DeleteCharacterFromPartyCommand;
use XpTracker\Character\Application\Command\DeleteCharacterFromPartyCommandHandler;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterNotInPartyException;
use XpTracker\Character\Domain\Party\PartyCharacterWasRemoved;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyWriteModelException;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;
use XpTracker\Tests\Unit\Character\Domain\AddCharacterToPartyWriteModelSpy;
use XpTracker\Tests\Unit\Character\Domain\CharacterOM;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingAddCharacterToPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyOM;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class DeleteCharacterFromPartyCommandHandlerTest extends TestCase
{
    private const PARTY_ULID = '01HX43CHZ0KC5YSA78RVVGM0KY';
    private const CHARACTER_ULID = '01HX43CF18KPQAZPY4CJ27J80Q';
    private DeleteCharacterFromPartyCommand $command;

    protected function setUp(): void
    {
        $this->command = new DeleteCharacterFromPartyCommand(self::PARTY_ULID, self::CHARACTER_ULID);
    }

    /** @test */
    public function itShouldThrowExceptionWhenPartyUlidHasWrongFormat(): void
    {
        $this->expectException(WrongUlidValueException::class);
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        $command = new DeleteCharacterFromPartyCommand('asd', '01HX43CF18KPQAZPY4CJ27J80Q');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterUlidHasWrongFormat(): void
    {
        $this->expectException(WrongUlidValueException::class);
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        $command = new DeleteCharacterFromPartyCommand('01HX43CF18KPQAZPY4CJ27J80Q', 'asd');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoPartyFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoCharacterFound(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new PartyRepositoryStub($party),
            new NotFoundCharacterRepositoryStub(),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterNoElegibleToRemove(): void
    {
        $this->expectException(CharacterNotInPartyException::class);
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $character = CharacterOM::aBuilder()->build();
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenPartyCannotBeStored(): void
    {
        $this->expectException(PartyWriteModelException::class);
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->build();
        $party = PartyOM::aBuilder()->withCharacter($character)->withUlid(self::PARTY_ULID)->build();
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new FailingAddCharacterToPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->command);
    }

    /** @test */
    public function itShouldSendProperEvents(): void
    {
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->build();
        $party = PartyOM::aBuilder()->withCharacter($character)->withUlid(self::PARTY_ULID)->build();
        $spy = new EventBusSpy();
        $sut = new DeleteCharacterFromPartyCommandHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new AddCharacterToPartyWriteModelSpy(),
            $spy
        );
        ($sut)($this->command);
        $this->assertCount(1, $spy->publishedEvents);
        $this->assertInstanceOf(PartyCharacterWasRemoved::class, $spy->publishedEvents[0]);
    }
}
