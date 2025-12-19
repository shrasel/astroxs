<?php

namespace Shrasel\Astroxs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Privilege extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'astroxs_privileges';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the roles that have this privilege.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'astroxs_privilege_role',
            'privilege_id',
            'role_id'
        )->withTimestamps();
    }
}
