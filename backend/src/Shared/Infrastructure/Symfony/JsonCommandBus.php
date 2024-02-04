<?php

namespace XpTracker\Shared\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonCommandBus
{
    /**
     * @param array<string,int> $expectedExceptions
     */
    public function process(object $message, array $expectedExceptions): JsonResponse;
}
