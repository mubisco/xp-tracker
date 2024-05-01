<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Event\ProjectEncounterWhenResolvedEventHandler;
use XpTracker\Encounter\Application\Event\ProjectEncounterWhenUpdatedEventHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\Projection\EncounterProjectionException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionSpy;

class ProjectEncounterWhenUpdatedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $event = new EncounterWasUpdated(encounterId: 'asd');
        $sut = new ProjectEncounterWhenUpdatedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $event = new EncounterWasUpdated(encounterId: '01HWV4ZFQRHHK9B069HKKR669H');
        $sut = new ProjectEncounterWhenUpdatedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(EncounterProjectionException::class);
        $event = new EncounterWasUpdated(encounterId: '01HWV4ZFQRHHK9B069HKKR669H');
        $sut = new ProjectEncounterWhenUpdatedEventHandler(
            new EncounterRepositoryStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new EncounterProjectionSpy();
        $event = new EncounterWasUpdated(encounterId: '01HWV4ZFQRHHK9B069HKKR669H');
        $sut = new ProjectEncounterWhenUpdatedEventHandler(
            new EncounterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->itemToProject);
    }
}

