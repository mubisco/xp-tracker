<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectPartyWhenCharacterWasRemovedEventHandler;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyCharacterWasRemoved;
use XpTracker\Character\Domain\Party\PartyProjectionException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyProjectionSpy;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;

class ProjectPartyWhenCharacterWasRemovedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $event = new PartyCharacterWasRemoved('wrongUlid', '01HX46R0E845E467Y8FNW94571');
        $sut = new ProjectPartyWhenCharacterWasRemovedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new PartyCharacterWasRemoved('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterWasRemovedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(PartyProjectionException::class);
        $event = new PartyCharacterWasRemoved('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterWasRemovedEventHandler(
            new PartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new PartyProjectionSpy();
        $event = new PartyCharacterWasRemoved('01HT4VM9NG59T2ED3RJ6YV2B6W', '01HT4W11W0TT617BM1MZ87EC91');
        $sut = new ProjectPartyWhenCharacterWasRemovedEventHandler(
            new PartyRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->party);
    }
}
