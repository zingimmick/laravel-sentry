<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Sentry\Event;
use Sentry\Laravel\Facade;
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
        $request = Mockery::mock(Request::class);
        $nextParam = null;
        (new SentryContext(Auth::getFacadeRoot()))->handle($request, $this->createNext($nextParam));
        $userContext = Facade::pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->email, $userContext->getEmail());
        $this->assertSame($request, $nextParam);
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
        $request = Mockery::mock(Request::class);

        (new CustomSentryContext(Auth::getFacadeRoot()))->handle($request, $this->createNext($nextParam));
        $userContext = Facade::pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->username, $userContext->getUsername());
        $this->assertSame($request, $nextParam);
    }

    public function testGuest(): void
    {
        $request = Mockery::mock(Request::class);

        (new SentryContext(Auth::getFacadeRoot()))->handle($request, $this->createNext($nextParam));
        $userContext = Facade::pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertNull($userContext->getId());
        self::assertNull($userContext->getEmail());
        $this->assertSame($request, $nextParam);
    }
}
