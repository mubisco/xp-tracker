<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectPartyWhenCharacterAddedEventHandler;
use XpTracker\Character\Domain\CharacterJoined;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyProjectionException;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyProjectionSpy;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;

class ProjectPartyWhenCharacterAddedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $event = new CharacterJoined('wrongUlid', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterAddedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new CharacterJoined('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterAddedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(PartyProjectionException::class);
        $event = new CharacterJoined('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterAddedEventHandler(
            new PartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new PartyProjectionSpy();
        $event = new CharacterJoined('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterAddedEventHandler(
            new PartyRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->party);
    }
}
