<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Support\Facades\Auth;
use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    protected function resolveUser($guard): array
    {
        if ($guard === 'api') {
            return [
                'id' => Auth::user()->getAuthIdentifier(),
                'username' => Auth::user()->username,
            ];
        }

        return parent::resolveUser($guard);
    }
}
