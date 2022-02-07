<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Sentry\State\Scope;
use Zing\LaravelSentry\Support\SentryIntegration;
use function Sentry\configureScope;

class SentryContext
{
    /**
     * The authentication factory instance.
     */
    protected \Illuminate\Contracts\Auth\Factory $auth;

    /**
     * Create a new middleware instance.
     */
    public function __construct(Factory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->auth->guard()->guest()) {
            return $next($request);
        }

        if (! $this->sentryIsBound()) {
            return $next($request);
        }

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $this->auth->guard()
            ->user();
        configureScope(
            function (Scope $scope) use ($user): void {
                $scope->setUser($this->resolveUserContext($this->auth->getDefaultDriver(), $user));
            }
        );

        return $next($request);
    }

    protected function sentryIsBound(): bool
    {
        return SentryIntegration::bound();
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolveUserContext(string $guard, Authenticatable $user): array
    {
        return [
            'id' => $user->getAuthIdentifier(),
        ];
    }
}
