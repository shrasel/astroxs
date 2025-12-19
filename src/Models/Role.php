<?php

namespace Shrasel\Astroxs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'astroxs_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_protected',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_protected' => 'boolean',
    ];

    /**
     * Get the users that have this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.providers.users.model', 'App\Models\User'),
            'astroxs_role_user',
            'role_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Get the privileges attached to this role.
     */
    public function privileges(): BelongsToMany
    {
        return $this->belongsToMany(
            Privilege::class,
            'astroxs_privilege_role',
            'role_id',
            'privilege_id'
        )->withTimestamps();
    }

    /**
     * Check if role has a specific privilege.
     */
    public function hasPrivilege(string $privilege): bool
    {
        return $this->privileges()->where('slug', $privilege)->exists();
    }

    /**
     * Check if role has all specified privileges.
     */
    public function hasPrivileges(array $privileges): bool
    {
        $rolePrivileges = $this->privileges()->pluck('slug')->toArray();
        
        foreach ($privileges as $privilege) {
            if (!in_array($privilege, $rolePrivileges)) {
                return false;
            }
        }
        
        return true;
    }
}
