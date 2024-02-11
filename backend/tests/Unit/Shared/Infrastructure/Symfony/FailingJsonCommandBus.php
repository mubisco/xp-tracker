<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

final class FailingJsonCommandBus implements JsonCommandBus
{
    public function process(object $message, array $expectedExceptions): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_BAD_REQUEST);
    }
}
