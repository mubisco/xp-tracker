<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectCharacterWhenCreatedEventHandler;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Domain\CharacterProjectionException;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Tests\Unit\Character\Domain\CharacterProjectionSpy;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\FailingCharacterProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;

class ProjectCharacterWhenCreatedEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $event = new CharacterWasCreated('wrongUlid', 'Darling', 0);
        $sut = new ProjectCharacterWhenCreatedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $event = new CharacterWasCreated('01HPBR1SNRBSNBNKVPHST9ST6N', 'Darling', 0);
        $sut = new ProjectCharacterWhenCreatedEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(CharacterProjectionException::class);
        $event = new CharacterWasCreated('01HPBR1SNRBSNBNKVPHST9ST6N', 'Darling', 0);
        $sut = new ProjectCharacterWhenCreatedEventHandler(
            new CharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new CharacterProjectionSpy();
        $event = new CharacterWasCreated('01HPBR1SNRBSNBNKVPHST9ST6N', 'Darling', 0);
        $sut = new ProjectCharacterWhenCreatedEventHandler(
            new CharacterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertTrue($spy->projected);
    }
}
