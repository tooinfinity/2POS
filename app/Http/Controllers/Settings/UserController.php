<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\StoreUserAction;
use App\Actions\Settings\UpdateUserAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class UserController
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): Response
    {
        return Inertia::render('Settings/Users', [
            'users' => User::with(['roles', 'permissions'])->paginate(10),
            'can' => [
                'create' => auth()->user()->can('create', User::class),
            ],
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Settings/User', [
            'user' => $user,
            'role' => $user->roles->first(),
            'permissions' => $user->permissions,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/CreateUser', [
            'roles' => Role::pluck('name', 'id'),
            'permissions' => Permission::pluck('name', 'id'),
        ]);
    }

    public function store(StoreUserRequest $request, StoreUserAction $action): RedirectResponse
    {
        $action->handle($request->validated());

        return redirect()->route('users')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Settings/EditUser', [
            'user' => $user,
            'userRole' => $user->roles->first(),
            'userPermissions' => $user->permissions,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function update(UpdateUserRequest $request, UpdateUserAction $action, User $user): RedirectResponse
    {
        $action->handle($user, $request->validated());

        return redirect()->route('users')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users')
            ->with('success', 'User deleted successfully');
    }
}
