<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\LanguageController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/language', [LanguageController::class, 'index'])->name('language');
    Route::put('settings/language', [LanguageController::class, 'update'])->name('language.update');

    Route::get('settings/appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');
});
