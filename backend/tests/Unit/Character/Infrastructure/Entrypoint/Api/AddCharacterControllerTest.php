<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XpTracker\Character\Application\Command\AddCharacterCommand;
use XpTracker\Character\Infrastructure\Entrypoint\Api\AddCharacterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class AddCharacterControllerTest extends TestCase
{
    public function testShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new AddCharacterController(new FailingJsonCommandBus());
        $content = 'asd';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnBadParamsOnBadContents(): void
    {
        $sut = new AddCharacterController(new FailingJsonCommandBus());
        $content = '{}';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new AddCharacterController($spy);
        $content = '{"ulid":"ulid","characterName":"Chindasvinto","playerName":"Pousa","experiencePoints":0,"maxHitpoints":25}';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(AddCharacterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('ulid', $command->ulid);
        $this->assertEquals('Chindasvinto', $command->characterName);
        $this->assertEquals('Pousa', $command->playerName);
        $this->assertEquals(0, $command->experiencePoints);
        $this->assertEquals(25, $command->maxHitpoints);
    }
}
