<?php

declare(strict_types=1);

namespace Pentacore\LaravelUtils\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pentacore\LaravelUtils\LaravelUtilsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelUtilsServiceProvider::class,
        ];
    }
}
