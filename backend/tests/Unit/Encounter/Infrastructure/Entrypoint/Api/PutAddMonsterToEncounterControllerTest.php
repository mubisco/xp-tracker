<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XpTracker\Encounter\Application\Command\AddMonsterToEncounterCommand;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\PutAddMonsterToEncounterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PutAddMonsterToEncounterControllerTest extends TestCase
{
    /** @test */
    public function itShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PutAddMonsterToEncounterController(new FailingJsonCommandBus());
        $content = 'asd';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function itShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PutAddMonsterToEncounterController($spy);
        $content = '{"encounterUlid":"ulid","monsterName":"Chindasvinto","challengeRating":"1/2"}';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(AddMonsterToEncounterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('ulid', $command->encounterUlid);
        $this->assertEquals('Chindasvinto', $command->monsterName);
        $this->assertEquals('1/2', $command->challengeRating);
    }
}
