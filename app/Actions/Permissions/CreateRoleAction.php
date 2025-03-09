<?php

declare(strict_types=1);

namespace App\Actions\Permissions;

use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

final class CreateRoleAction
{
    public function handle(Role $role, Permission $permission, User $user): void
    {
        $superAdminRole = $role->firstOrCreate(['name' => RoleEnum::SuperAdmin->value]);
        $superAdminRole->syncPermissions($permission->all());

        $user->assignRole($superAdminRole);

        $role->Create(['name' => RoleEnum::Manager->value]);
        $role->Create(['name' => RoleEnum::Cashier->value]);
    }
}
