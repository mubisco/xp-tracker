<?php

namespace XpTracker\Tests\Unit\Encounter\Domain\Monster;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\Monster\ChallengeRating;
use XpTracker\Encounter\Domain\Monster\InvalidChallengeRatingValueException;

class ChallengeRatingTest extends TestCase
{
    /**
     * @test
     * @dataProvider wrongChallengerRatingValues
     * */
    public function itShouldThrowExceptionWhenNoValidValueProvided(string $wrongChallengeRating): void
    {
        $this->expectException(InvalidChallengeRatingValueException::class, 'Value: ' . $wrongChallengeRating);
        ChallengeRating::fromString($wrongChallengeRating);
    }
    /**
     * @return array<int,mixed>
     */
    public function wrongChallengerRatingValues(): array
    {
        return [['1/3'], ['-1'], ['27'], ['31']];
    }

    /**
     * @test
     * @dataProvider challengeRatingValues
     * it_should_return_proper_value
     */
    public function itShouldReturnProperValue(string $challengerRating, int $experiencePoints): void
    {
        $sut = ChallengeRating::fromString($challengerRating);
        $this->assertEquals($challengerRating, $sut->rating());
        $this->assertEquals($experiencePoints, $sut->xp());
    }
    /**
     * @return array<int,array<int,mixed>>
     */
    public function challengeRatingValues(): array
    {
        return [
            ['1/2', 100],
            ['0', 10],
            ['1', 200],
            ['9', 5000],
        ];
    }
}
