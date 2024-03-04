<?php

namespace XpTracker\Tests\Integration\Character\Infrastructure\Persistence;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\CharacterNotFoundException;
use XpTracker\Character\Infrastructure\Persistence\EventStoreCharacterRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class EventStoreCharacterRepositoryIntegrationTest extends KernelTestCase
{
    private EventStoreCharacterRepository $sut;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->sut = $container->get(EventStoreCharacterRepository::class);
    }

    public function testItShouldThrowExceptionWhenNoCharacterFound(): void
    {
        $this->expectException(CharacterNotFoundException::class);
        $ulid = SharedUlid::fromEmpty();
        $this->sut->byId($ulid);
    }

    public function testItShouldAddProperCharacter(): void
    {
        $ulid = SharedUlid::fromEmpty();
        $character = BasicCharacter::create(
            ulid: $ulid->ulid(),
            name: 'Darling',
            experiencePoints: 300
        );
        $this->sut->add($character);
        $retrievedCharacter = $this->sut->byId($ulid);
        $this->assertEquals($ulid->ulid(), $retrievedCharacter->id());
    }
}
