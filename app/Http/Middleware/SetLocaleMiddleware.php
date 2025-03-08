<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = config('app.locale');
        if (! is_string($defaultLocale)) {
            $defaultLocale = 'en';
        }

        $locale = Setting::get('locale', $defaultLocale);
        app()->setLocale($locale);
        session()->put('locale', $locale);

        return $next($request);
    }
}
