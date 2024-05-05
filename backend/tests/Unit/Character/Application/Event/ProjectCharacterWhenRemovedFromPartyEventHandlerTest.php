<?php

namespace XpTracker\Tests\Unit\Character\Application\Event;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Event\ProjectCharacterWhenRemovedFromPartyEventHandler;
use XpTracker\Character\Domain\CharacterProjectionException;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Tests\Unit\Character\Domain\CharacterRepositoryStub;
use XpTracker\Tests\Unit\Character\Domain\CharacterProjectionSpy;
use XpTracker\Character\Domain\CharacterWasRemoved;
use XpTracker\Tests\Unit\Character\Domain\FailingCharacterProjectionStub;
use XpTracker\Tests\Unit\Character\Domain\NotFoundCharacterRepositoryStub;

class ProjectCharacterWhenRemovedFromPartyEventHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $event = new CharacterWasRemoved('1HPBR1SNRBSNBNKVPHST9ST6N', '01HT4TF65GTNDR3FGX4KXJ85EB');
        $sut = new ProjectCharacterWhenRemovedFromPartyEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoCharacterFound(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $event = new CharacterWasRemoved('01HPBR1SNRBSNBNKVPHST9ST6N', '01HT4TF65GTNDR3FGX4KXJ85EB');
        $sut = new ProjectCharacterWhenRemovedFromPartyEventHandler(
            new NotFoundCharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldThrowExceptionIfNoProjectionMade(): void
    {
        $this->expectException(CharacterProjectionException::class);
        $event = new CharacterWasRemoved('01HPBR1SNRBSNBNKVPHST9ST6N', '01HT4TF65GTNDR3FGX4KXJ85EB');
        $sut = new ProjectCharacterWhenRemovedFromPartyEventHandler(
            new CharacterRepositoryStub(),
            new FailingCharacterProjectionStub()
        );
        ($sut)($event);
    }

    public function testItShouldMakeProjectionProperly(): void
    {
        $spy = new CharacterProjectionSpy();
        $event = new CharacterWasRemoved('01HPBR1SNRBSNBNKVPHST9ST6N', '01HT4TF65GTNDR3FGX4KXJ85EB');
        $sut = new ProjectCharacterWhenRemovedFromPartyEventHandler(
            new CharacterRepositoryStub(),
            $spy
        );
        ($sut)($event);
        $this->assertTrue($spy->projected);
    }
}
