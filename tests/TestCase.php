<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sentry\Laravel\ServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class, ServiceProvider::class];
    }

    protected function createNext(&$nextParam): callable
    {
        return static function ($param) use (&$nextParam): void {
            $nextParam = $param;
        };
    }

    protected function defineEnvironment($app)
    {
        Config::set('auth.guards.api', [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ]);
    }
}
