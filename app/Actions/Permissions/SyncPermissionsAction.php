<?php

declare(strict_types=1);

namespace App\Actions\Permissions;

use App\Models\Permission;
use App\Permissions\SettingPermissions;
use App\Permissions\UserPermissions;
use ReflectionClass;
use Spatie\Permission\PermissionRegistrar;

final class SyncPermissionsAction
{
    public function handle(): void
    {
        $permissions = collect([
            UserPermissions::class,
            SettingPermissions::class,
        ])->flatMap(fn ($permissionClass): array => new ReflectionClass($permissionClass)->getConstants());

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
