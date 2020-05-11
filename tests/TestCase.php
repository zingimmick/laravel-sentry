<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sentry\Laravel\ServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AuthServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function createNext(): callable
    {
        $nextParam = null;

        return static function ($param) use (&$nextParam): void {
            $nextParam = $param;
        };
    }
}
