<?php

declare(strict_types=1);

namespace XpTracker\Shared\Infrastructure\Symfony;

use Symfony\Component\Messenger\MessageBusInterface;
use XpTracker\Shared\Domain\Event\DomainEvent;
use XpTracker\Shared\Domain\Event\EventBus;

final class SymfonyEventBus implements EventBus
{
    public function __construct(private readonly MessageBusInterface $eventAsyncBus)
    {
    }

    public function publish(array $events): void
    {
        foreach ($events as $event) {
            $this->validateEvent($event);
            $this->eventAsyncBus->dispatch($event);
        }
    }

    private function validateEvent(mixed $event): void
    {
        if (!is_a($event, DomainEvent::class)) {
            throw new \InvalidArgumentException(
                'Only DomainEvents allowed for this EventBus!!!'
            );
        }
    }
}
