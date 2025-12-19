<?php

namespace Shrasel\Astroxs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Shrasel\Astroxs\Models\Role;

class UserController
{
    /**
     * List all users.
     */
    public function index(): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::with('roles')->get();

        return response()->json($users);
    }

    /**
     * Show user.
     */
    public function show($id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::with('roles')->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Create user.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign default role
        if (method_exists($user, 'assignRole')) {
            $defaultRole = Role::where('slug', config('astroxs.default_user_role_slug', 'user'))->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        }

        return response()->json($user, 201);
    }

    /**
     * Update user.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($id);

        // Only allow users to update themselves unless they're admin
        if ($request->user()->id !== $user->id && !$request->user()->hasRole('admin') && !$request->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Delete user.
     */
    public function destroy($id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($id);
        
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Suspend user.
     */
    public function suspend(Request $request, $id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($id);

        $request->validate([
            'reason' => 'nullable|string',
        ]);

        if (method_exists($user, 'suspend')) {
            $user->suspend($request->reason);
        }

        return response()->json(['message' => 'User suspended successfully']);
    }

    /**
     * Unsuspend user.
     */
    public function unsuspend($id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($id);

        if (method_exists($user, 'unsuspend')) {
            $user->unsuspend();
        }

        return response()->json(['message' => 'User unsuspended successfully']);
    }

    /**
     * Get user roles.
     */
    public function roles($id): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::with('roles')->findOrFail($id);

        return response()->json($user->roles);
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, $id): JsonResponse
    {
        $request->validate([
            'role_id' => 'required|exists:astroxs_roles,id',
        ]);

        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($id);
        $role = Role::findOrFail($request->role_id);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }

        return response()->json(['message' => 'Role assigned successfully']);
    }

    /**
     * Remove role from user.
     */
    public function removeRole($userId, $roleId): JsonResponse
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        if (method_exists($user, 'removeRole')) {
            $user->removeRole($role);
        }

        return response()->json(['message' => 'Role removed successfully']);
    }
}
