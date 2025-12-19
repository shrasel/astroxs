<?php

namespace Shrasel\Astroxs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Shrasel\Astroxs\Models\Privilege;

class PrivilegeController
{
    /**
     * List all privileges.
     */
    public function index(): JsonResponse
    {
        $privileges = Privilege::with('roles')->get();
        return response()->json($privileges);
    }

    /**
     * Show privilege.
     */
    public function show(Privilege $privilege): JsonResponse
    {
        $privilege->load('roles');
        return response()->json($privilege);
    }

    /**
     * Create privilege.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:astroxs_privileges,slug',
            'description' => 'nullable|string',
        ]);

        $privilege = Privilege::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return response()->json($privilege, 201);
    }

    /**
     * Update privilege.
     */
    public function update(Request $request, Privilege $privilege): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:astroxs_privileges,slug,' . $privilege->id,
            'description' => 'nullable|string',
        ]);

        $privilege->update($request->only(['name', 'slug', 'description']));

        return response()->json($privilege);
    }

    /**
     * Delete privilege.
     */
    public function destroy(Privilege $privilege): JsonResponse
    {
        $privilege->delete();

        return response()->json(['message' => 'Privilege deleted successfully']);
    }
}
