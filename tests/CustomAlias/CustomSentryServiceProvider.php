<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

class CustomSentryServiceProvider extends \Sentry\Laravel\ServiceProvider
{
    public static string $abstract = 'custom-sentry';
}
