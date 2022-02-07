<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Sentry\Event;
use Zing\LaravelSentry\Tests\Concerns\SentryTests;
use Zing\LaravelSentry\Tests\CustomContext\CustomSentryContext;

/**
 * @internal
 */
final class SentryContextTest extends TestCase
{
    use SentryTests;

    public function testCustom(): void
    {
        $user = new User(
            [
                'id' => 1,
                'email' => 'example@example.com',
                'username' => 'example',
            ]
        );
        Auth::shouldUse('api');
        Auth::setUser($user);
        $request = Mockery::mock(Request::class);

        /** @var \Illuminate\Contracts\Auth\Factory $auth */
        $auth = Auth::getFacadeRoot();
        (new CustomSentryContext($auth))->handle($request, $this->createNext($nextParam));

        /** @var \Sentry\Event $event */
        $event = $this->getHubFromContainer()
            ->pushScope()
            ->applyToEvent(Event::createEvent());

        /** @var \Sentry\UserDataBag $userContext */
        $userContext = $event->getUser();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->username, $userContext->getUsername());
        $this->assertSame($request, $nextParam);
    }
}
