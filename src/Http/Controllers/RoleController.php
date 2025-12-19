<?php

namespace Shrasel\Astroxs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Shrasel\Astroxs\Models\Role;
use Shrasel\Astroxs\Models\Privilege;

class RoleController
{
    /**
     * List all roles.
     */
    public function index(): JsonResponse
    {
        $roles = Role::with('privileges')->withCount('users')->get();
        return response()->json($roles);
    }

    /**
     * Show role.
     */
    public function show(Role $role): JsonResponse
    {
        $role->load('privileges', 'users');
        return response()->json($role);
    }

    /**
     * Create role.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:astroxs_roles,slug',
            'description' => 'nullable|string',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'is_protected' => false,
        ]);

        return response()->json($role, 201);
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        if ($role->is_protected) {
            abort(403, 'Cannot modify protected role');
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:astroxs_roles,slug,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($request->only(['name', 'slug', 'description']));

        return response()->json($role);
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role): JsonResponse
    {
        if ($role->is_protected) {
            abort(403, 'Cannot delete protected role');
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * Get role privileges.
     */
    public function privileges(Role $role): JsonResponse
    {
        return response()->json($role->privileges);
    }

    /**
     * Attach privilege to role.
     */
    public function attachPrivilege(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'privilege_id' => 'required|exists:astroxs_privileges,id',
        ]);

        $privilege = Privilege::findOrFail($request->privilege_id);
        
        if (!$role->privileges()->where('privilege_id', $privilege->id)->exists()) {
            $role->privileges()->attach($privilege->id);
        }

        return response()->json(['message' => 'Privilege attached successfully']);
    }

    /**
     * Detach privilege from role.
     */
    public function detachPrivilege(Role $role, Privilege $privilege): JsonResponse
    {
        $role->privileges()->detach($privilege->id);

        return response()->json(['message' => 'Privilege detached successfully']);
    }
}
