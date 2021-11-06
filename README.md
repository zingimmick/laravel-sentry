# Laravel Sentry

<p align="center">
<a href="https://github.com/zingimmick/laravel-sentry/actions"><img src="https://github.com/zingimmick/laravel-sentry/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-sentry"><img src="https://codecov.io/gh/zingimmick/laravel-sentry/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sentry"><img src="https://poser.pugx.org/zing/laravel-sentry/license" alt="License"></a>
<a href="https://scrutinizer-ci.com/g/zingimmick/laravel-sentry"><img src="https://scrutinizer-ci.com/g/zingimmick/laravel-sentry/badges/quality-score.png" alt="Scrutinizer Code Quality"></a>
<a href="https://codeclimate.com/github/zingimmick/laravel-sentry/maintainability"><img src="https://api.codeclimate.com/v1/badges/5a95a074bcd38fd38da0/maintainability" /></a>
</p>

**Laravel Sentry is archived**. Because most functions can be replaced by official functions and have better fault tolerance.

> **Requires [PHP 7.2.0+](https://php.net/releases/)**

Require Laravel Sentry using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-sentry --dev
```

## Usage

### Add User context

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

### Send exception directly(Issue Grouping)

```php
use Zing\LaravelSentry\Support\SentryIntegration;

SentryIntegration::captureException(new Exception(""));
```

## License

Laravel Sentry is an open-sourced software licensed under the [MIT license](LICENSE).
