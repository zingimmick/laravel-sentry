# Laravel Sentry

<p align="center">
<a href="https://github.com/zingimmick/laravel-sentry/actions"><img src="https://github.com/zingimmick/laravel-sentry/actions/workflows/tests.yml/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-sentry"><img src="https://codecov.io/gh/zingimmick/laravel-sentry/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/license" alt="License"></a>
<a href="https://scrutinizer-ci.com/g/zingimmick/laravel-sentry"><img src="https://scrutinizer-ci.com/g/zingimmick/laravel-sentry/badges/quality-score.png" alt="Scrutinizer Code Quality"></a>
<a href="https://codeclimate.com/github/zingimmick/laravel-sentry/maintainability"><img src="https://api.codeclimate.com/v1/badges/5a95a074bcd38fd38da0/maintainability" /></a>
</p>

> **Requires [PHP 7.3.0+](https://php.net/releases/)**

Require Laravel Sentry using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-sentry
```

## Usage

### Add user context

```php
use Zing\LaravelSentry\Middleware\SentryContext;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        SentryContext::class,
    ];

    // ...
}
```

### Custom user context

```php
use Zing\LaravelSentry\Middleware\SentryContext;

class CustomSentryContext extends SentryContext
{
    /**
     * @param \Zing\LaravelSentry\Tests\User $user
     *
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
```

## License

Laravel Sentry is an open-sourced software licensed under the [MIT license](LICENSE).
