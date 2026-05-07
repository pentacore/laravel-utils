<?php

declare(strict_types=1);

namespace Pentacore\LaravelUtils\Tests\Fixtures;

use Pentacore\LaravelUtils\Concerns\EnumUtils;

enum Priority: int
{
    use EnumUtils;

    case Low = 1;
    case Medium = 2;
    case High = 3;

    public function label(): string
    {
        return match ($this) {
            self::Low => 'low_priority',
            self::Medium => 'medium_priority',
            self::High => 'high_priority',
        };
    }
}
