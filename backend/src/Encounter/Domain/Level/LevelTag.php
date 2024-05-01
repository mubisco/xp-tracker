<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Level;

enum LevelTag: string
{
    case EMPTY = 'EMPTY';
    case UNASSIGNED = 'UNASSIGNED';
    case EASY = 'EASY';
    case MEDIUM = 'MEDIUM';
    case HARD = 'HARD';
    case DEADLY = 'DEADLY';
}
