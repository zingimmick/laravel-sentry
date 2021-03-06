<?php

declare(strict_types=1);

namespace Zing\LaravelSentry\Tests\Concerns;

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
        $this->resolveSentryContext(Auth::getFacadeRoot())->handle($request, $this->createNext($nextParam));
        $userContext = $this->getHubFromContainer()
            ->pushScope()
            ->applyToEvent(Event::createEvent())->getUser();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        $this->assertSame($request, $nextParam);
    }

    public function testGuest(): void
    {
        $request = Mockery::mock(Request::class);

        $this->resolveSentryContext(Auth::getFacadeRoot())->handle($request, $this->createNext($nextParam));
        $userContext = $this->getHubFromContainer()
            ->pushScope()
            ->applyToEvent(Event::createEvent())->getUser();
        $this->assertNull($userContext);
        $this->assertSame($request, $nextParam);
    }

    protected function resolveSentryContext($auth)
    {
        return new SentryContext($auth);
    }

    protected function resolveSentryIntegration()
    {
        return SentryIntegration::class;
    }

    protected function getHubFromContainer(): HubInterface
    {
        return forward_static_call([$this->resolveSentryIntegration(), 'getInstance']);
    }

    public function testCaptureException(): void
    {
        forward_static_call([$this->resolveSentryIntegration(), 'captureException'], new \Exception());
        self::assertNotNull($this->getHubFromContainer()->getLastEventId());
    }
}
