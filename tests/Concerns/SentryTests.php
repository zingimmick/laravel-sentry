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
        self::assertTrue($this->resolveSentryIntegration()::bound());
    }

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
        $this->resolveSentryContext(Auth::getFacadeRoot())->handle($request, $this->createNext($nextParam));
        $userContext = $this->getHubFromContainer()->pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertSame($user->getAuthIdentifier(), $userContext->getId());
        self::assertSame($user->email, $userContext->getEmail());
        $this->assertSame($request, $nextParam);
    }

    public function testGuest(): void
    {
        $request = Mockery::mock(Request::class);

        $this->resolveSentryContext(Auth::getFacadeRoot())->handle($request, $this->createNext($nextParam));
        $userContext = $this->getHubFromContainer()->pushScope()->applyToEvent(new Event(), [])->getUserContext();
        $this->assertNull($userContext->getId());
        self::assertNull($userContext->getEmail());
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
        return $this->resolveSentryIntegration()::getInstance();
    }

    public function testCaptureException(): void
    {
        $this->resolveSentryIntegration()::captureException(new \Exception());
        self::assertNotNull($this->getHubFromContainer()->getLastEventId());
    }
}
