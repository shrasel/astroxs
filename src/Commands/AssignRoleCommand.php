<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;

class AssignRoleCommand extends Command
{
    protected $signature = 'astroxs:assign-role 
                            {--user= : User ID or email}
                            {--role= : Role slug or ID}';
    protected $description = 'Assign a role to a user';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        
        $userIdentifier = $this->option('user') ?: $this->ask('Enter user ID or email');
        $roleIdentifier = $this->option('role') ?: $this->ask('Enter role slug or ID');

        $user = is_numeric($userIdentifier)
            ? $userModel::find($userIdentifier)
            : $userModel::where('email', $userIdentifier)->first();

        if (!$user) {
            $this->error('User not found!');
            return self::FAILURE;
        }

        $role = is_numeric($roleIdentifier)
            ? Role::find($roleIdentifier)
            : Role::where('slug', $roleIdentifier)->first();

        if (!$role) {
            $this->error('Role not found!');
            return self::FAILURE;
        }

        if (!method_exists($user, 'assignRole')) {
            $this->error('User model must use HasAstroxsRoles trait!');
            return self::FAILURE;
        }

        $user->assignRole($role);

        $this->info("✓ Role '{$role->name}' assigned to {$user->email}");

        return self::SUCCESS;
    }
}
