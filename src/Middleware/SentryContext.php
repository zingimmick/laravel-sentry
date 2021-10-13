<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Sentry\State\Scope;
use Zing\LaravelSentry\Support\SentryIntegration;
use function Sentry\configureScope;

class SentryContext
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     */
    public function __construct(Factory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guard()->guest()) {
            return $next($request);
        }

        if (! $this->sentryIsBound()) {
            return $next($request);
        }

        configureScope(
            function (Scope $scope): void {
                $scope->setUser(
                    $this->resolveUserContext($this->auth->getDefaultDriver(), $this->auth->guard()->user())
                );
            }
        );

        return $next($request);
    }

    protected function sentryIsBound(): bool
    {
        return SentryIntegration::bound();
    }

    /**
     * @param string $guard
     */
    protected function resolveUserContext($guard, Authenticatable $user): array
    {
        return [
            'id' => $user->getAuthIdentifier(),
        ];
    }
}
