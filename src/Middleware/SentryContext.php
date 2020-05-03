<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Middleware;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Auth;
use function Sentry\configureScope;
use Sentry\State\Scope;

class SentryContext
{
    public function handle($request, \Closure $next)
    {
        if (Auth::check() && Container::getInstance()->bound('sentry')) {
            configureScope(
                function (Scope $scope): void {
                    $scope->setUser($this->resolveUser(Auth::getDefaultDriver()));
                }
            );
        }

        return $next($request);
    }

    protected function resolveUser($guard): array
    {
        return [
            'id' => Auth::user()->getAuthIdentifier(),
            'email' => Auth::user()->email,
        ];
    }
}
