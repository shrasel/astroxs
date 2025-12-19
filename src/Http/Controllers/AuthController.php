<?php

namespace Shrasel\Astroxs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController
{
    /**
     * Login and generate token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $user = $userModel::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is suspended
        if (method_exists($user, 'isSuspended') && $user->isSuspended()) {
            return response()->json([
                'message' => 'Account is suspended.',
                'reason' => $user->getSuspensionReason(),
            ], 423);
        }

        // Get abilities
        $abilities = ['*'];
        if (method_exists($user, 'astroxsRoleSlugs')) {
            $abilities = array_merge(
                $user->astroxsRoleSlugs(),
                $user->astroxsPrivilegeSlugs()
            );
        }

        $token = $user->createToken('astroxs-api-token', $abilities)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $data = [
            'user' => $user,
        ];

        if (method_exists($user, 'astroxsRoleSlugs')) {
            $data['roles'] = $user->astroxsRoleSlugs();
            $data['privileges'] = $user->astroxsPrivilegeSlugs();
        }

        return response()->json($data);
    }

    /**
     * Get Astroxs info.
     */
    public function info(): JsonResponse
    {
        return response()->json([
            'name' => 'Astroxs',
            'version' => config('astroxs.version', '1.0.0'),
            'description' => 'Authentication, Authorization & Role Management for Laravel 12',
        ]);
    }

    /**
     * Get version.
     */
    public function version(): JsonResponse
    {
        return response()->json([
            'version' => config('astroxs.version', '1.0.0'),
        ]);
    }
}
