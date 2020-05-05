<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use function Sentry\configureScope;
use Sentry\State\Scope;

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
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     *
     * @return void
     */
    public function __construct(Factory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guard()->check() && Container::getInstance()->bound('sentry')) {
            configureScope(
                function (Scope $scope): void {
                    $scope->setUser($this->resolveUserContext($this->auth->getDefaultDriver(), $this->auth->guard()->user()));
                }
            );
        }

        return $next($request);
    }

    protected function resolveUserContext($guard, Authenticatable $user): array
    {
        return [
            'id' => $user->getAuthIdentifier(),
            'email' => $user->email,
        ];
    }
}
