<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Support;

use Illuminate\Container\Container;
use Sentry\Laravel\ServiceProvider;
use Sentry\State\Hub;
use Throwable;

/**
 * @deprecated Use report($exception) instead
 */
class SentryIntegration
{
    public static function getAbstract(): string
    {
        return class_exists(ServiceProvider::class) ? ServiceProvider::$abstract : 'sentry';
    }

    public static function bound(): bool
    {
        return Container::getInstance()->bound(static::getAbstract());
    }

    public static function captureException(Throwable $exception): void
    {
        if (static::bound()) {
            static::getInstance()->captureException($exception);
        }
    }

    public static function getInstance(): Hub
    {
        /** @var \Sentry\State\Hub $hub */
        $hub = Container::getInstance()->make(static::getAbstract());

        return $hub;
    }
}
