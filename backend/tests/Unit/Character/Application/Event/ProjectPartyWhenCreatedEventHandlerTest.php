<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectPartyWhenCreatedEventHandler;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyProjectionException;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\Party\FailingPartyRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyProjectionSpy;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyRepositoryStub;

class ProjectPartyWhenCreatedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $event = new PartyWasCreated('wrongUlid', 'Comando G');
        $sut = new ProjectPartyWhenCreatedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new PartyWasCreated('01HT4VM9NG59T2ED3RJ6YV2B6W', 'Comando G');
        $sut = new ProjectPartyWhenCreatedEventHandler(
            new FailingPartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(PartyProjectionException::class);
        $event = new PartyWasCreated('01HT4VM9NG59T2ED3RJ6YV2B6W', 'Comando G');
        $sut = new ProjectPartyWhenCreatedEventHandler(
            new PartyRepositoryStub(),
            new FailingPartyProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new PartyProjectionSpy();
        $event = new PartyWasCreated('01HT4VM9NG59T2ED3RJ6YV2B6W', 'Comando G');
        $sut = new ProjectPartyWhenCreatedEventHandler(
            new PartyRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->party);
    }
}
