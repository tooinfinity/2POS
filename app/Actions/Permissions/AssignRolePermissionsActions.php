<?php

declare(strict_types=1);

namespace App\Actions\Permissions;

use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class AssignRolePermissionsActions
{
    public function handle(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
