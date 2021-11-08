<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\Concerns;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Sentry\Event;
use Sentry\State\HubInterface;
use Zing\LaravelSentry\Middleware\SentryContext;
use Zing\LaravelSentry\Support\SentryIntegration;
use Zing\LaravelSentry\Tests\User;

trait SentryTests
{
    public function testBound(): void
    {
        self::assertTrue(forward_static_call([$this->resolveSentryIntegration(), 'bound']));
    }

    public function testHandle(): void
    {
        $user = new User(
            [
                'id' => 1,
                'email' => 'example@example.com',
                'username' => 'example',
            ]
        );
        Auth::setUser($user);
        $request = Mockery::mock(Request::class);
        $nextParam = null;
        /** @var \Illuminate\Contracts\Auth\Factory $auth */
        $auth=Auth::getFacadeRoot();
        $this->resolveSentryContext($auth)->handle($request, $this->createNext($nextParam));
        /** @var \Sentry\Event $event */
        $event = $this->getHubFromContainer()
            ->pushScope()
            ->applyToEvent(Event::createEvent());
        /** @var \Sentry\UserDataBag $userContext */
        $userContext = $event->getUser();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        $this->assertSame($request, $nextParam);
    }

    public function testGuest(): void
    {
        $nextParam = null;
        $request = Mockery::mock(Request::class);
        /** @var \Illuminate\Contracts\Auth\Factory $auth */
        $auth=Auth::getFacadeRoot();
        $this->resolveSentryContext($auth)->handle($request, $this->createNext($nextParam));
        /** @var \Sentry\Event $event */
        $event = $this->getHubFromContainer()
            ->pushScope()
            ->applyToEvent(Event::createEvent());
        /** @var \Sentry\UserDataBag $userContext */
        $userContext = $event->getUser();
        $this->assertNull($userContext);
        $this->assertSame($request, $nextParam);
    }

    protected function resolveSentryContext(Factory $auth): SentryContext
    {
        return new SentryContext($auth);
    }

    /**
     * @return class-string<\Zing\LaravelSentry\Support\SentryIntegration>
     */
    protected function resolveSentryIntegration()
    {
        return SentryIntegration::class;
    }

    protected function getHubFromContainer(): HubInterface
    {
        /** @var HubInterface $hub */
        $hub= forward_static_call([$this->resolveSentryIntegration(), 'getInstance']);
        return $hub;
    }

    public function testCaptureException(): void
    {
        forward_static_call([$this->resolveSentryIntegration(), 'captureException'], new \Exception());
        self::assertNotNull($this->getHubFromContainer()->getLastEventId());
    }
}
