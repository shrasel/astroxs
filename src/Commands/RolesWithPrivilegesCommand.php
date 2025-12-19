<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;

class RolesWithPrivilegesCommand extends Command
{
    protected $signature = 'astroxs:roles-with-privileges';
    protected $description = 'Display roles with their attached privileges';

    public function handle(): int
    {
        $roles = Role::with('privileges')->withCount('users')->get();

        if ($roles->isEmpty()) {
            $this->warn('No roles found.');
            return self::SUCCESS;
        }

        $data = $roles->map(function ($role) {
            $privileges = $role->privileges->pluck('slug')->implode(', ');
            
            return [
                $role->id,
                $role->name,
                $role->slug,
                $role->users_count,
                $privileges ?: 'none',
            ];
        });

        $this->table(['ID', 'Name', 'Slug', 'Users', 'Privileges'], $data);

        return self::SUCCESS;
    }
}
