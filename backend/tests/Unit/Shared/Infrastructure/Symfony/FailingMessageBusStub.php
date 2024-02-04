<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class FailingMessageBusStub implements MessageBusInterface
{
    public function __construct(private readonly Throwable $throwable)
    {
    }

    public function dispatch($message, array $stamps = []): Envelope
    {
        $envelope = new Envelope($message);
        throw new HandlerFailedException($envelope, [$this->throwable]);
    }
}
