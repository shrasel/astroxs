<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;

class RolesCommand extends Command
{
    protected $signature = 'astroxs:roles';
    protected $description = 'Display all roles with user counts';

    public function handle(): int
    {
        $roles = Role::withCount('users')->get();

        if ($roles->isEmpty()) {
            $this->warn('No roles found.');
            return self::SUCCESS;
        }

        $data = $roles->map(function ($role) {
            return [
                $role->id,
                $role->name,
                $role->slug,
                $role->users_count,
                $role->is_protected ? 'Yes' : 'No',
            ];
        });

        $this->table(['ID', 'Name', 'Slug', 'Users', 'Protected'], $data);

        return self::SUCCESS;
    }
}
