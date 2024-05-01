<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Event;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Event\ProjectEncounterWhenCreatedEventHandler;
use XpTracker\Encounter\Domain\EncounterNotFoundException;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Projection\EncounterProjectionException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryNotFoundStub;
use XpTracker\Tests\Unit\Encounter\Domain\EncounterRepositoryStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\Projection\EncounterProjectionSpy;

class ProjectEncounterWhenCreatedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $event = new EncounterWasCreated(id: 'wrongUlid', name: 'Darling');
        $sut = new ProjectEncounterWhenCreatedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(EncounterNotFoundException::class);
        $event = new EncounterWasCreated(id: '01HWV4DNDR4Y9VTZPXF73QSSWB', name: 'Darling');
        $sut = new ProjectEncounterWhenCreatedEventHandler(
            new EncounterRepositoryNotFoundStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(EncounterProjectionException::class);
        $event = new EncounterWasCreated(id: '01HWV4DNDR4Y9VTZPXF73QSSWB', name: 'Darling');
        $sut = new ProjectEncounterWhenCreatedEventHandler(
            new EncounterRepositoryStub(),
            new EncounterProjectionFailingStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new EncounterProjectionSpy();
        $event = new EncounterWasCreated(id: '01HWV4DNDR4Y9VTZPXF73QSSWB', name: 'Darling');
        $sut = new ProjectEncounterWhenCreatedEventHandler(
            new EncounterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertNotNull($spy->itemToProject);
    }
}
