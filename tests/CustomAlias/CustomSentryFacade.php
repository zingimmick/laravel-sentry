<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

use Sentry\Laravel\Facade;

class CustomSentryFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'custom-sentry';
    }
}
