<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\UpdateCharacterWhenAddToPartyEventHandler;
use XpTracker\Character\Application\Event\UpdateCharacterWhenRemovedFromPartyEventHandler;
use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Character\Domain\CharacterAlreadyInPartyException;
use XpTracker\Character\Domain\CharacterJoined;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterNotInPartyException;
use XpTracker\Character\Domain\CharacterWasRemoved;
use XpTracker\Character\Domain\InvalidCharacterUlidValueException;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyCharacterWasRemoved;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Tests\Unit\Character\Domain\CharacterOM;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingUpdateCharacterPartyWriteModelStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyOM;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\UpdateCharacterPartyWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class UpdateCharacterWhenRemovedFromPartyEventHandlerTest extends TestCase
{
    private PartyCharacterWasRemoved $event;
    private const PARTY_ULID = '01HSR45MBRZTW3CMR6AY3KNXMP';
    private const CHARACTER_ULID = '01HSR45PA8P0CNC1E929ZQWPXG';

    protected function setUp(): void
    {
        $this->event = new PartyCharacterWasRemoved(
            partyId: self::PARTY_ULID,
            characterId: self::CHARACTER_ULID
        );
    }

    public function testItShouldThrowExceptionWhenWrongPartyId(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $wrongEvent = new PartyCharacterWasRemoved(
            partyId: '1HSR45MBRZTW3CMR6AY3KNXMP',
            characterId: self::CHARACTER_ULID
        );
        $sut = $this->withAllFailingSut();
        ($sut)($wrongEvent);
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongCharacterUlid(): void
    {
        $this->expectException(InvalidCharacterUlidValueException::class);
        $wrongEvent = new PartyCharacterWasRemoved(
            partyId: self::PARTY_ULID,
            characterId: '1HSR45PA8P0CNC1E929ZQWPXG'
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
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new PartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterHasNoParty(): void
    {
        $this->expectException(CharacterNotInPartyException::class);
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->build();
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldThrowExceptionWhenCharacterCannotBeUpdated(): void
    {
        $this->expectException(AddCharacterWriteModelException::class);
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->withParty($party)->build();
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        ($sut)($this->event);
    }

    /** @test */
    public function itShouldUpdateCharacterProperly(): void
    {
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->withParty($party)->build();
        $spy = new UpdateCharacterPartyWriteModelSpy();
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            $spy,
            new EventBusSpy()
        );
        ($sut)($this->event);
        $this->assertNotNull($spy->updatedCharacter);
        $events = $spy->updatedCharacter->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterWasRemoved::class, $event);
    }

    /** @test */
    public function itShouldPublishProperEvents(): void
    {
        $party = PartyOM::aBuilder()->withUlid(self::PARTY_ULID)->build();
        $character = CharacterOM::aBuilder()->withUlid(self::CHARACTER_ULID)->withParty($party)->build();
        $spy = new EventBusSpy();
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new PartyRepositoryStub($party),
            new CharacterRepositoryStub($character),
            new UpdateCharacterPartyWriteModelSpy(),
            $spy
        );
        ($sut)($this->event);
        $events = $spy->publishedEvents;
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterWasRemoved::class, $event);
    }

    private function withAllFailingSut(): UpdateCharacterWhenRemovedFromPartyEventHandler
    {
        $sut = new UpdateCharacterWhenRemovedFromPartyEventHandler(
            new FailingPartyRepositoryStub(),
            new NotFoundCharacterRepositoryStub(),
            new FailingUpdateCharacterPartyWriteModelStub(),
            new EventBusSpy()
        );
        return $sut;
    }
}
