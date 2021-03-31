<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Container\Container;
use Zing\LaravelSentry\Support\SentryIntegration;

class SentryIntegrationTest extends TestCase
{
    public function testNotBound(): void
    {
        $container = \Mockery::mock(Container::class);
        $container->shouldReceive('bound')
            ->withArgs(['sentry'])->andReturn(false);
        Container::setInstance($container);
        self::assertFalse(SentryIntegration::bound());
    }
}
