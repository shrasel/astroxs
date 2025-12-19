<?php

use Illuminate\Support\Facades\Route;
use Shrasel\Astroxs\Http\Controllers\AuthController;
use Shrasel\Astroxs\Http\Controllers\UserController;
use Shrasel\Astroxs\Http\Controllers\RoleController;
use Shrasel\Astroxs\Http\Controllers\PrivilegeController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/astroxs', [AuthController::class, 'info']);
Route::get('/astroxs/version', [AuthController::class, 'version']);

// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    
    // User routes
    Route::get('/users', [UserController::class, 'index'])->middleware('ability:admin,super-admin');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('ability:admin,super-admin');
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::patch('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('ability:admin,super-admin');
    
    // User suspension
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->middleware('ability:admin,super-admin');
    Route::delete('/users/{user}/suspend', [UserController::class, 'unsuspend'])->middleware('ability:admin,super-admin');
    
    // Role routes
    Route::middleware(['ability:admin,super-admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::patch('/roles/{role}', [RoleController::class, 'update']);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
        
        // User role assignments
        Route::get('/users/{user}/roles', [UserController::class, 'roles']);
        Route::post('/users/{user}/roles', [UserController::class, 'assignRole']);
        Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole']);
    });
    
    // Privilege routes
    Route::middleware(['ability:admin,super-admin'])->group(function () {
        Route::get('/privileges', [PrivilegeController::class, 'index']);
        Route::post('/privileges', [PrivilegeController::class, 'store']);
        Route::get('/privileges/{privilege}', [PrivilegeController::class, 'show']);
        Route::put('/privileges/{privilege}', [PrivilegeController::class, 'update']);
        Route::patch('/privileges/{privilege}', [PrivilegeController::class, 'update']);
        Route::delete('/privileges/{privilege}', [PrivilegeController::class, 'destroy']);
        
        // Role privilege assignments
        Route::get('/roles/{role}/privileges', [RoleController::class, 'privileges']);
        Route::post('/roles/{role}/privileges', [RoleController::class, 'attachPrivilege']);
        Route::delete('/roles/{role}/privileges/{privilege}', [RoleController::class, 'detachPrivilege']);
    });
});
