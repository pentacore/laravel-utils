<?php

declare(strict_types=1);

namespace Workbench\App\Concerns;

use Pentacore\LaravelUtils\Concerns\EnumUtils;

enum Direction
{
    use EnumUtils;

    case North;
    case South;
    case East;
    case West;
    case North_East;
}