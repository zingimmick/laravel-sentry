<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

use Illuminate\Auth\AuthServiceProvider;
use Zing\LaravelSentry\Tests\Concerns\SentryTests;
use Zing\LaravelSentry\Tests\TestCase;

class SentryContextWithCustomAliasTest extends TestCase
{
    use SentryTests;

    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class, CustomSentryServiceProvider::class];
    }

    protected function resolveSentryContext($auth)
    {
        return new CustomSentryContext($auth);
    }

    protected function resolveSentryIntegration()
    {
        return CustomSentryIntegration::class;
    }
}
