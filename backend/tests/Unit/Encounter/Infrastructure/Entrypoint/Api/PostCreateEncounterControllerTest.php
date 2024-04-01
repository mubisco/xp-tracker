<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XpTracker\Encounter\Application\Command\CreateEncounterCommand;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\PostCreateEncounterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PostCreateEncounterControllerTest extends TestCase
{
    /** @test */
    public function itShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PostCreateEncounterController(new FailingJsonCommandBus());
        $content = 'asd';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function itShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PostCreateEncounterController($spy);
        $content = '{"ulid":"ulid","name":"Chindasvinto","experiencePoints":0}';
        $request = Request::create('/some/url', 'POST', [], [], [], [], $content);
        $response = ($sut)($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(CreateEncounterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('ulid', $command->ulid);
        $this->assertEquals('Chindasvinto', $command->name);
        // $this->assertEquals(0, $command->experiencePoints);
    }
}
