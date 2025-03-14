<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\StoreUserAction;
use App\Actions\Settings\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreUserRequest;
use App\Http\Requests\Settings\UpdateUserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('settings/users/index', [
            'users' => User::with(['roles', 'permissions'])
                ->latest()
                ->paginate(10)
                ->through(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->permissions->pluck('name'),
                ]),
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render('settings/users/show', [
            'user' => $user,
            'role' => $user->roles->first(),
            'permissions' => $user->permissions,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('settings/users/create', [
            'roles' => Role::all()->map(fn ($role): array => [
                'id' => $role->id,
                'name' => $role->name,
            ]),
            'permissions' => Permission::all()->map(fn ($perm): array => [
                'id' => $perm->id,
                'name' => $perm->name,
            ]),
            'rolePermissions' => Role::with('permissions')->get()->mapWithKeys(fn ($role) => [
                $role->name => $role->permissions->pluck('name')->toArray(),
            ])->toArray(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(StoreUserRequest $request, StoreUserAction $action): RedirectResponse
    {
        /** @var array{name: string, email: string, password: string, role?: string, permissions?: array<int>} $validated */
        $validated = $request->validated();

        $action->handle($validated);

        return to_route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        $roles = Role::all()->map(fn ($role): array => [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        $permissions = Permission::all()->map(fn ($perm): array => [
            'id' => $perm->id,
            'name' => $perm->name,
        ]);

        $userRoles = $user->roles->pluck('name')->toArray();

        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        $rolePermissions = Role::with('permissions')->get()->mapWithKeys(fn ($role) => [
            $role->name => $role->permissions->pluck('name')->toArray(),
        ])->toArray();

        return Inertia::render('settings/users/edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $userRoles,
                'permissions' => $userPermissions,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateUserRequest $request, UpdateUserAction $action, User $user): RedirectResponse
    {
        /** @var array{name: string, email: string, password?: string, role?: string, permissions?: array<int>} $validated */
        $validated = $request->validated();

        $action->handle($user, $validated);

        return to_route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return to_route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
