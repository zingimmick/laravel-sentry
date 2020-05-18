<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomAlias;

use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    protected function sentryIsBound(): bool
    {
        return CustomSentryIntegration::bound();
    }
}
