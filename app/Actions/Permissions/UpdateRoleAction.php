<?php

declare(strict_types=1);

namespace App\Actions\Permissions;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

final class UpdateRoleAction
{
    /**
     * @param  array{role: string, permissions?: array<int>}  $data
     *
     * @throws Throwable
     */
    public function handle(Role $role, Permission $permission, User $user, array $data): void
    {
        DB::transaction(static function () use ($role, $permission, $user, $data): void {
            $role->update([
                'name' => $data['role'],
            ]);

            if (isset($data['permissions'])) {
                /** @var Collection<int, Permission> $permissions */
                $permissions = $permission->findMany($data['permissions']);

                if ($permissions->count() !== count($data['permissions'])) {
                    throw new ModelNotFoundException('One or more permissions not found');
                }

                $role->syncPermissions($permissions);
            }

            if ($user->exists) {
                $user->syncRoles([$data['role']]);
            }
        });
    }
}
