<?php

declare(strict_types=1);

namespace XpTracker\Shared\Infrastructure\Symfony;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyJsonCommandBus implements JsonCommandBus
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly LoggerInterface $loggerInterface
    ) {
    }

    public function process(object $message, array $expectedExceptions): JsonResponse
    {
        try {
            $this->commandBus->dispatch($message);
            return new JsonResponse([], Response::HTTP_OK);
        } catch (HandlerFailedException $e) {
            $unwrappedException = $this->unwrapException($e);
            $this->loggerInterface->critical($unwrappedException);
            $errorCode = $this->errorCode($unwrappedException, $expectedExceptions);
            return new JsonResponse(['msg' => $unwrappedException->getMessage()], $errorCode);
        }
    }

    /**
     * @param array<string,int> $exceptionsCodeMap
     */
    private function errorCode(Throwable $exception, array $exceptionsCodeMap): int
    {
        $exceptionShortName = (new \ReflectionClass($exception))->getShortName();
        if (!isset($exceptionsCodeMap[$exceptionShortName])) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $exceptionsCodeMap[$exceptionShortName];
    }

    private function unwrapException(HandlerFailedException $e): Throwable
    {
        $originException = $e;
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
        }
        return $e ?? $originException;
    }
}
