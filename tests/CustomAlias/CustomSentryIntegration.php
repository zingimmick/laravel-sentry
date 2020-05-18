<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

use Zing\LaravelSentry\Support\SentryIntegration;

class CustomSentryIntegration extends SentryIntegration
{
    public static function getAbstract(): string
    {
        return 'custom-sentry';
    }
}
