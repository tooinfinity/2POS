<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Override;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function share(Request $request): array
    {
        $defaultLocale = config('app.locale');
        if (! is_string($defaultLocale)) {
            $defaultLocale = 'en';
        }
        /** @var array<string, mixed> $shared */
        $shared = array_merge(parent::share($request), [
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
                'canRegister' => ! User::hasUsers(),
            ],
            'locale' => Setting::get('locale', $defaultLocale),
            'language' => fn (): array => translations(base_path('lang/'.app()->getLocale().'.json')),
        ]);

        return $shared;
    }
}
