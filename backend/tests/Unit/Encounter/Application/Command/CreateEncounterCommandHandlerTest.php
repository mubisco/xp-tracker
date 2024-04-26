<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Command;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Command\CreateEncounterCommand;
use XpTracker\Encounter\Application\Command\CreateEncounterCommandHandler;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\WrongEncounterNameException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Tests\Unit\Encounter\Domain\AddEncounterWriteModelFailingStub;
use XpTracker\Tests\Unit\Encounter\Domain\AddEncounterWriteModelSpy;
use XpTracker\Tests\Unit\Shared\Domain\Event\EventBusSpy;

class CreateEncounterCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        $sut = new CreateEncounterCommandHandler(
            new AddEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new CreateEncounterCommand('asd', 'Chindasvinto');
        ($sut)($command);
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongName(): void
    {
        $this->expectException(WrongEncounterNameException::class);
        $sut = new CreateEncounterCommandHandler(
            new AddEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new CreateEncounterCommand('01HTTRXEVGQW6BCCTZPKZRWQT7', ';Chindasvinto');
        ($sut)($command);
    }

    /**
     * @test
     * it_should_throw_exception_when_cannot_be_stored
     */
    public function itShouldThrowExceptionWhenCannotBeStored(): void
    {
        $this->expectException(EncounterWriteModelException::class);
        $sut = new CreateEncounterCommandHandler(
            new AddEncounterWriteModelFailingStub(),
            new EventBusSpy()
        );
        $command = new CreateEncounterCommand('01HTTRXEVGQW6BCCTZPKZRWQT7', 'Chindasvinto');
        ($sut)($command);
    }

    /**
     * @test
     * it_should_publish_proper_events
     */
    public function itShouldPublishProperEvents(): void
    {
        $eventBusSpy = new EventBusSpy();
        $writeModelSpy = new AddEncounterWriteModelSpy();
        $sut = new CreateEncounterCommandHandler($writeModelSpy, $eventBusSpy);
        $command = new CreateEncounterCommand('01HTTRXEVGQW6BCCTZPKZRWQT7', 'Chindasvinto');
        ($sut)($command);
        $this->assertCount(1, $eventBusSpy->publishedEvents);
    }
}
