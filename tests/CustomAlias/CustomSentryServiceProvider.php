<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

class CustomSentryServiceProvider extends \Sentry\Laravel\ServiceProvider
{
    /**
     * @var string
     */
    public static $abstract = 'custom-sentry';
}
