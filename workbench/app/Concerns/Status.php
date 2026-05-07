<?php

declare(strict_types=1);

namespace Workbench\App\Concerns;

use Pentacore\LaravelUtils\Concerns\EnumUtils;

enum Status: string
{
    use EnumUtils;

    case Active = 'active';
    case Pending_Review = 'pending_review';
    case Archived = 'archived';
}