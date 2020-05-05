<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    protected function resolveUserContext($guard, $user): array
    {
        if ($guard === 'api') {
            return [
                'id' => $user->getAuthIdentifier(),
                'username' => $user->username,
            ];
        }

        return parent::resolveUserContext($guard, $user);
    }
}
