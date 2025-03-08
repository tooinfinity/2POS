<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 *
 *
 * @property string $id
 * @property string $name
 * @property string $guard_name
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission permission($permissions, $without = false)
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereGuardName($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission whereName($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @method static Builder<static>|Permission withoutPermission($permissions)
 * @method static Builder<static>|Permission withoutRole($roles, $guard = null)
 * @mixin Eloquent
 */
final class Permission extends SpatiePermission
{
    use HasUlids;
}
