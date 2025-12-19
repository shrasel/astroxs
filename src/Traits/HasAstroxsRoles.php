<?php

namespace Shrasel\Astroxs\Traits;

use Shrasel\Astroxs\Models\Role;
use Shrasel\Astroxs\Models\Privilege;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HasAstroxsRoles
{
    /**
     * Get the roles relationship.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'astroxs_role_user',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(Role $role): void
    {
        if (!$this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role->id);
            $this->clearAstroxsCache();
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
        $this->clearAstroxsCache();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        if ($role === '*') {
            return true;
        }

        return in_array($role, $this->astroxsRoleSlugs());
    }

    /**
     * Check if user has all specified roles.
     */
    public function hasRoles(array $roles): bool
    {
        $userRoles = $this->astroxsRoleSlugs();
        
        foreach ($roles as $role) {
            if (!in_array($role, $userRoles)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get all role slugs for the user (cached).
     */
    public function astroxsRoleSlugs(): array
    {
        if (!config('astroxs.cache.enabled', true)) {
            return $this->roles()->pluck('slug')->toArray();
        }

        $cacheKey = "astroxs_user_{$this->id}_roles";
        $cacheTtl = config('astroxs.cache.ttl', 3600);

        return Cache::remember($cacheKey, $cacheTtl, function () {
            return $this->roles()->pluck('slug')->toArray();
        });
    }

    /**
     * Get all privileges inherited through roles.
     */
    public function privileges(): Collection
    {
        $roles = $this->roles()->with('privileges')->get();
        $privileges = collect();

        foreach ($roles as $role) {
            $privileges = $privileges->merge($role->privileges);
        }

        return $privileges->unique('id');
    }

    /**
     * Check if user has a specific privilege.
     */
    public function hasPrivilege(string $privilege): bool
    {
        return in_array($privilege, $this->astroxsPrivilegeSlugs());
    }

    /**
     * Check if user has all specified privileges.
     */
    public function hasPrivileges(array $privileges): bool
    {
        $userPrivileges = $this->astroxsPrivilegeSlugs();
        
        foreach ($privileges as $privilege) {
            if (!in_array($privilege, $userPrivileges)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get all privilege slugs for the user (cached).
     */
    public function astroxsPrivilegeSlugs(): array
    {
        if (!config('astroxs.cache.enabled', true)) {
            return $this->privileges()->pluck('slug')->toArray();
        }

        $cacheKey = "astroxs_user_{$this->id}_privileges";
        $cacheTtl = config('astroxs.cache.ttl', 3600);

        return Cache::remember($cacheKey, $cacheTtl, function () {
            return $this->privileges()->pluck('slug')->toArray();
        });
    }

    /**
     * Override the can method to check privileges, then roles, then Laravel Gate.
     */
    public function can($ability, $arguments = []): bool
    {
        // Check privilege first
        if ($this->hasPrivilege($ability)) {
            return true;
        }

        // Check role
        if ($this->hasRole($ability)) {
            return true;
        }

        // Fall back to Laravel's Gate
        return parent::can($ability, $arguments);
    }

    /**
     * Suspend the user.
     */
    public function suspend(?string $reason = null): void
    {
        $this->update([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);

        // Revoke all Sanctum tokens
        if (method_exists($this, 'tokens')) {
            $this->tokens()->delete();
        }
    }

    /**
     * Unsuspend the user.
     */
    public function unsuspend(): void
    {
        $this->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);
    }

    /**
     * Check if user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /**
     * Get the suspension reason.
     */
    public function getSuspensionReason(): ?string
    {
        return $this->suspension_reason;
    }

    /**
     * Clear the Astroxs cache for this user.
     */
    protected function clearAstroxsCache(): void
    {
        if (config('astroxs.cache.enabled', true)) {
            Cache::forget("astroxs_user_{$this->id}_roles");
            Cache::forget("astroxs_user_{$this->id}_privileges");
        }
    }

    /**
     * Boot the trait.
     */
    public static function bootHasAstroxsRoles(): void
    {
        static::deleting(function ($user) {
            $user->roles()->detach();
        });
    }
}
