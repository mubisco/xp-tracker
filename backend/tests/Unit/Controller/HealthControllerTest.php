<?php

namespace XpTracker\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use XpTracker\Controller\HealthController;

class HealthControllerTest extends TestCase
{
    public function testShouldReturnJsonResponse(): void
    {
        $sut = new HealthController();
        $response = ($sut)();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
