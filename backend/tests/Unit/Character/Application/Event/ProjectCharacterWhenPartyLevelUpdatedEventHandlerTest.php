<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectCharacterWhenPartyLevelUpdatedEventHandler;
use XpTracker\Character\Domain\CharacterProjectionException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Tests\Unit\Character\Domain\CharacterProjectionSpy;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingCharacterProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;

class ProjectCharacterWhenPartyLevelUpdatedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $event = new PartyLevelWasUpdated('01HX1KTDD0QMXN1J6PVQ1KRCTS', 'asd', [2, 2]);
        $sut = new ProjectCharacterWhenPartyLevelUpdatedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $event = new PartyLevelWasUpdated('01HX1KTDD0QMXN1J6PVQ1KRCTS', '01HX1KTEC8GBTG14T8BCQ5M012', [2, 2]);
        $sut = new ProjectCharacterWhenPartyLevelUpdatedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(CharacterProjectionException::class);
        $event = new PartyLevelWasUpdated('01HX1KTDD0QMXN1J6PVQ1KRCTS', '01HX1KTEC8GBTG14T8BCQ5M012', [2, 2]);
        $sut = new ProjectCharacterWhenPartyLevelUpdatedEventHandler(
            new CharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new CharacterProjectionSpy();
        $event = new PartyLevelWasUpdated('01HX1KTDD0QMXN1J6PVQ1KRCTS', '01HX1KTEC8GBTG14T8BCQ5M012', [2, 2]);
        $sut = new ProjectCharacterWhenPartyLevelUpdatedEventHandler(
            new CharacterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertTrue($spy->projected);
        $this->assertEquals($spy->timesCalled, 3);
    }
}
