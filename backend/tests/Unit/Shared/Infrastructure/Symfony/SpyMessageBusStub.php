<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class SpyMessageBusStub implements MessageBusInterface
{
    public mixed $message = null;

    public function dispatch($message, array $stamps = []): Envelope
    {
        $this->message = $message;
        return new Envelope($message);
    }
}
