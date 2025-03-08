<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\UpdateLanguageAction;
use App\Http\Requests\Settings\UpdateLanguageRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

final class LanguageController
{
    /**
     * Show the user's language settings page.
     */
    public function index(): Response|ResponseFactory
    {
        $defaultLocale = config('app.locale');
        if (! is_string($defaultLocale)) {
            $defaultLocale = 'en';
        }

        $locale = Setting::get('locale', $defaultLocale);

        return inertia('settings/language', [
            'locale' => $locale,
            'language' => trans('*', [], $locale),
        ]);
    }

    public function update(UpdateLanguageRequest $request, Setting $setting, UpdateLanguageAction $action): RedirectResponse
    {
        $action->handle($setting, (string) $request->string('language'));

        return back();
    }
}
