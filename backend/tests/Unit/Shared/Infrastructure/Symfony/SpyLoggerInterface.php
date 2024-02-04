<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Shared\Infrastructure\Symfony;

use Psr\Log\LoggerInterface;
use Stringable;

final class SpyLoggerInterface implements LoggerInterface
{
    public string $methodCalled = '';

    public function emergency(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->methodCalled = 'critical';
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        throw new \RuntimeException(sprintf('Implement %s', __METHOD__));
    }
}
