<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Custom;

use DateTimeImmutable;
use PHPUnit\Framework\Constraint\Constraint;

class IsImmediateDate extends Constraint
{
    public function toString(): string
    {
        return 'is not the same DateTime';
    }

    protected function matches($other): bool
    {
        $now = new DateTimeImmutable();
        $delta = abs($other->getTimestamp() - $now->getTimestamp());
        return $delta < 5;
    }
}
