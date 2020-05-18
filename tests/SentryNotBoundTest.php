<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Sentry\Event;
use Sentry\State\Hub;
use Zing\LaravelSentry\Middleware\SentryContext;

class SentryNotBoundTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AuthServiceProvider::class,
        ];
    }

    public function testSentryNotBound(): void
    {
        $user = new User(
            [
                'email' => 'example@example.com',
                'username' => 'example',
            ]
        );
        Auth::setUser($user);
        $request = Mockery::mock(Request::class);

        (new SentryContext(Auth::getFacadeRoot()))->handle($request, $this->createNext($nextParam));
        $userContext = Hub::getCurrent()->pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertNull($userContext->getId());
        self::assertNull($userContext->getEmail());
        $this->assertSame($request, $nextParam);
    }
}