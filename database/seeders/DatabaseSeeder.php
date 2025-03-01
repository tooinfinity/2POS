<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'TouwfiQ Meghlaoui',
            'email' => 'touwfiqdev@gmail.com',
            'password' => Hash::make('25031992'),
            'email_verified_at' => CarbonImmutable::now(),
        ]);
    }
}
