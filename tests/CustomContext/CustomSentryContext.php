<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\CustomContext;

use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    /**
     * @return array<string, mixed>|mixed[]
     */
    protected function resolveUserContext(string $guard, \Illuminate\Contracts\Auth\Authenticatable $user): array
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
