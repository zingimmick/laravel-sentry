<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomContext;

use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    /**
     * @param string $guard
     * @param \Zing\LaravelSentry\Tests\User $user
     */
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
