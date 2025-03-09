<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    public function before(?User $user): ?bool
    {
        if (! $user instanceof User) {
            return false;
        }
        if ($user->hasRole(RoleEnum::SuperAdministrator->value)) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleEnum::SuperAdministrator->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasRole(RoleEnum::SuperAdministrator->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(RoleEnum::SuperAdministrator->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole(RoleEnum::SuperAdministrator->value)
            && ! $model->hasRole(RoleEnum::SuperAdministrator->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole(RoleEnum::SuperAdministrator->value)
            && ! $model->hasRole(RoleEnum::SuperAdministrator->value);
    }
}
