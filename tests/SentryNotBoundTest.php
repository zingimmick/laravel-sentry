<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Sentry\Event;
use Sentry\SentrySdk;
use Zing\LaravelSentry\Middleware\SentryContext;

class SentryNotBoundTest extends TestCase
{
    /**
     * @param mixed $app
     *
     * @return array<class-string<\Illuminate\Auth\AuthServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class];
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
        /** @var \Illuminate\Contracts\Auth\Factory $auth */
        $auth=Auth::getFacadeRoot();
        (new SentryContext($auth))->handle($request, $this->createNext($nextParam));
        /** @var \Sentry\Event $event */
        $event = SentrySdk::getCurrentHub()->pushScope()->applyToEvent(Event::createEvent());
        /** @var \Sentry\UserDataBag $userContext */
        $userContext = $event->getUser();
        $this->assertNull($userContext);
        $this->assertSame($request, $nextParam);
    }
}
