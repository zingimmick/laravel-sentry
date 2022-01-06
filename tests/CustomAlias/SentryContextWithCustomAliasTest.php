<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Contracts\Auth\Factory;
use Zing\LaravelSentry\Tests\Concerns\SentryTests;
use Zing\LaravelSentry\Tests\TestCase;

/**
 * @internal
 */
final class SentryContextWithCustomAliasTest extends TestCase
{
    use SentryTests;

    /**
     * @param mixed $app
     *
     * @return class-string[]
     */
    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class, CustomSentryServiceProvider::class];
    }

    protected function resolveSentryContext(Factory $auth): CustomSentryContext
    {
        return new CustomSentryContext($auth);
    }

    /**
     * @return class-string<\Zing\LaravelSentry\Tests\CustomAlias\CustomSentryIntegration>
     */
    protected function resolveSentryIntegration()
    {
        return CustomSentryIntegration::class;
    }
}
