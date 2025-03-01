<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
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

        $user = User::factory()->create([
            'name' => 'TouwfiQ Meghlaoui',
            'email' => 'touwfiqdev@gmail.com',
            'password' => Hash::make('25031992'),
            'email_verified_at' => CarbonImmutable::now(),
        ]);

        // create a role super-admin
        $permission = Permission::create(['name' => 'register-user']);
        $role = Role::create(['name' => 'super-admin']);

        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $user->assignRole('super-admin');


    }
}
