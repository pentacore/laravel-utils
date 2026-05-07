<?php

declare(strict_types=1);

namespace Pentacore\LaravelUtils\Tests\Fixtures;

use Pentacore\LaravelUtils\Concerns\EnumUtils;

enum Status: string
{
    use EnumUtils;

    case Active = 'active';
    case Pending_Review = 'pending_review';
    case Archived = 'archived';
}
