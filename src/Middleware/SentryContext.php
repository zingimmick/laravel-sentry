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
     * Create a new middleware instance.
     */
    public function __construct(
        /**
         * The authentication factory instance.
         */
        protected Factory $authFactory
    ) {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->authFactory->guard()->guest()) {
            return $next($request);
        }

        if (! $this->sentryIsBound()) {
            return $next($request);
        }

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $this->authFactory->guard()
            ->user();
        configureScope(
            function (Scope $scope) use ($user): void {
                $scope->setUser($this->resolveUserContext($this->authFactory->getDefaultDriver(), $user));
            }
        );

        return $next($request);
    }

    protected function sentryIsBound(): bool
    {
        return SentryIntegration::bound();
    }

    /**
     * @return array{id: mixed}
     */
    protected function resolveUserContext(string $guard, Authenticatable $user): array
    {
        return [
            'id' => $user->getAuthIdentifier(),
        ];
    }
}
