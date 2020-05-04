<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sentry\Event;
use Sentry\SentrySdk;
use Zing\LaravelSentry\Middleware\SentryContext;

class SentryContextTest extends TestCase
{
    public function testHandle(): void
    {
        $user = new User(
            [
                'email' => 'example@example.com',
                'username' => 'example',
            ]
        );
        Auth::setUser($user);
        $request = \Mockery::mock(Request::class);

        $nextParam = null;

        $next = function ($param) use (&$nextParam): void {
            $nextParam = $param;
        };
        (new SentryContext())->handle($request, $next);
        $userContext = SentrySdk::getCurrentHub()->pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->email, $userContext->getEmail());
    }

    public function testCustom(): void
    {
        $user = new User(
            [
                'email' => 'example@example.com',
                'username' => 'example',
            ]
        );
        Auth::shouldUse('api');
        Auth::setUser($user);
        $request = \Mockery::mock(Request::class);

        $nextParam = null;

        $next = function ($param) use (&$nextParam): void {
            $nextParam = $param;
        };
        (new CustomSentryContext())->handle($request, $next);
        $userContext = SentrySdk::getCurrentHub()->pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->username, $userContext->getUsername());
    }
}
