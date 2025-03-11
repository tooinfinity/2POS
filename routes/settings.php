<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\LanguageController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/language', [LanguageController::class, 'index'])->name('language');
    Route::put('settings/language', [LanguageController::class, 'update'])->name('language.update');

    Route::middleware(['role:'.App\Enums\RoleEnum::Administrator->value])->group(function (): void {
        Route::get('settings/users', [UserController::class, 'index'])->name('users.index');
        Route::get('settings/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('settings/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('settings/users', [UserController::class, 'store'])->name('users.store');
        Route::get('settings/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('settings/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('settings/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('settings/appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');
});
