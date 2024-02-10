<?php

namespace XpTracker\Tests\Unit\Shared\Domain\Identity;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class SharedUlidTest extends TestCase
{
    public function testItShouldBeOfProperClass(): void
    {
        $sut = new SharedUlid('01HP9BFM98404KRE15AKWG6YBB');
        $this->assertInstanceOf(SharedUlid::class, $sut);
    }

    public function testItShouldThrowErrorWhenNoValidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SharedUlid('01HP9BFM98404KRE15AKWG6YB');
    }

    public function testItShouldReturnProperUlid(): void
    {
        $sut = new SharedUlid('01HP9BFM98404KRE15AKWG6YBB');
        $this->assertEquals('01HP9BFM98404KRE15AKWG6YBB', $sut->ulid());
    }

    public function testItShouldReturnProperUlidWhenEmpty(): void
    {
        $sut = SharedUlid::fromEmpty();
        $this->assertTrue(Ulid::isValid($sut->ulid()));
    }
}
