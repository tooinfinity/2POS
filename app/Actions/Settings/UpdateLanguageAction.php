<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\Setting;
use Illuminate\Support\Carbon;

final class UpdateLanguageAction
{
    public function handle(Setting $setting, string $value): void
    {
        $setting->set('locale', $value);
        session()->put('locale', $value);
        app()->setLocale($value);
        Carbon::setLocale($value);
    }
}
