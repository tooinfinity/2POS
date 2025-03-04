<?php

declare(strict_types=1);

use App\Http\Middleware\CanRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    app()->forgetInstance('has_users');
});

it('passes the request to next middleware when conditions are not met', function (): void {
    $middleware = new CanRegisterRequest();
    $request = Request::create('/register', 'GET');
    $called = false;

    $next = function ($req) use (&$called): Response {
        $called = true;

        return new Response();
    };

    $middleware->handle($request, $next);

    expect($called)->toBeTrue();
});

it('allows access to register when no users exist', function (): void {
    $middleware = new CanRegisterRequest();
    $request = Request::create('/register', 'GET');

    $response = $middleware->handle($request, fn (): Response => new Response());

    expect($response->getStatusCode())->toBe(200);
});

it('redirects to login when users exist', function (): void {
    User::factory()->create();
    $middleware = new CanRegisterRequest();
    $request = Request::create('/register', 'GET');
    $request->setRouteResolver(fn () => app('router')->getRoutes()->match($request));

    $response = $middleware->handle($request, fn (): Response => new Response());

    expect($response->getStatusCode())->toBe(302)
        ->and($response->getTargetUrl())->toContain('/login');
});

it('allows access to non-register routes when users exist', function (): void {
    User::factory()->create();
    $middleware = new CanRegisterRequest();
    $request = Request::create('/other-route', 'GET');

    $response = $middleware->handle($request, fn (): Response => new Response());

    expect($response->getStatusCode())->toBe(200);
});

it('passes request to next middleware when accessing login route', function (): void {
    $middleware = new CanRegisterRequest();
    $request = Request::create('/login', 'GET');
    $request->setRouteResolver(fn () => app('router')
        ->getRoutes()
        ->match($request));

    $nextCalled = false;
    $response = $middleware->handle($request, function ($req) use (&$nextCalled): Response {
        $nextCalled = true;

        return new Response('next called');
    });

    expect($nextCalled)->toBeTrue()
        ->and($response->getContent())->toBe('next called');
});

it('passes request to next middleware when no users exist', function (): void {
    $middleware = new CanRegisterRequest();
    $request = Request::create('/register', 'GET');
    $request->setRouteResolver(fn () => app('router')
        ->getRoutes()
        ->match($request));

    $nextCalled = false;
    $response = $middleware->handle($request, function ($req) use (&$nextCalled): Response {
        $nextCalled = true;

        return new Response('next called');
    });

    expect($nextCalled)->toBeTrue()
        ->and($response->getContent())->toBe('next called');
});
