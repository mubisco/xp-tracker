<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\UpdateCharacterWhenAddToPartyEventHandler;
use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Character\Domain\CharacterAlreadyInPartyException;
use XpTracker\Character\Domain\CharacterJoined;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\InvalidCharacterUlidValueException;
use XpTracker\Character\Domain\Party\CharacterWasAdded;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Tests\Unit\Character\Domain\CharacterOM;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingUpdateCharacterPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\UpdateCharacterPartyWriteModelSpy;

class UpdateCharacterWhenAddToPartyEventHandlerTest extends TestCase
{
    private CharacterWasAdded $event;

    protected function setUp(): void
    {
        $this->event = new CharacterWasAdded(
            id: '01HSR45MBRZTW3CMR6AY3KNXMP',
            addedCharacterId: '01HSR45PA8P0CNC1E929ZQWPXG'
        );
    }

    public function testItShouldThrowExceptionWhenWrongPartyId(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $wrongEvent = new CharacterWasAdded(
            id: '1HSR45MBRZTW3CMR6AY3KNXMP',
            addedCharacterId: '01HSR45PA8P0CNC1E929ZQWPXG'
        );
        $sut = $this->withAllFailingSut();
        ($sut)($wrongEvent);
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongCharacterUlid(): void
    {
        $this->expectException(InvalidCharacterUlidValueException::class);
        $wrongEvent = new CharacterWasAdded(
            id: '01HSR45MBRZTW3CMR6AY3KNXMP',
            addedCharacterId: '1HSR45PA8P0CNC1E929ZQWPXG'
        );
        $sut = $this->withAllFailingSut();
        ($sut)($wrongEvent);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoPartyFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $sut = $this->withAllFailingSut();
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoCharacterFound(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $sut = new UpdateCharacterWhenAddToPartyEventHandler(
            new PartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterAlreadyInParty(): void
    {
        $this->expectException(CharacterAlreadyInPartyException::class);
        $character = CharacterOM::aBuilder()->withParty()->build();
        $sut = new UpdateCharacterWhenAddToPartyEventHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub($character),
            new FailingUpdateCharacterPartyWriteModelStub()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterCannotBeUpdated(): void
    {
        $this->expectException(AddCharacterWriteModelException::class);
        $sut = new UpdateCharacterWhenAddToPartyEventHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldUpdateCharacterProperly(): void
    {
        $spy = new UpdateCharacterPartyWriteModelSpy();
        $sut = new UpdateCharacterWhenAddToPartyEventHandler(
            new PartyRepositoryStub(),
            new CharacterRepositoryStub(),
            $spy
        );
        ($sut)($this->event);
        $this->assertNotNull($spy->updatedCharacter);
        $events = $spy->updatedCharacter->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterJoined::class, $event);
    }

    private function withAllFailingSut(): UpdateCharacterWhenAddToPartyEventHandler
    {
        $sut = new UpdateCharacterWhenAddToPartyEventHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub()
        );
        return $sut;
    }
}
