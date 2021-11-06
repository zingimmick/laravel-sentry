<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Sentry\State\Scope;
use Zing\LaravelSentry\Support\SentryIntegration;
use function Sentry\configureScope;

/**
 * @deprecated Use \Illuminate\Foundation\Exceptions\Handler::context() instead
 */
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
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
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
