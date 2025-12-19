<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UsersWithRolesCommand extends Command
{
    protected $signature = 'astroxs:users-with-roles';
    protected $description = 'Display users with their role slugs';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::with('roles')->get();

        if ($users->isEmpty()) {
            $this->warn('No users found.');
            return self::SUCCESS;
        }

        $data = $users->map(function ($user) {
            $roles = method_exists($user, 'astroxsRoleSlugs') 
                ? implode(', ', $user->astroxsRoleSlugs()) 
                : 'N/A';

            return [
                $user->id,
                $user->name,
                $user->email,
                $roles ?: 'none',
            ];
        });

        $this->table(['ID', 'Name', 'Email', 'Roles'], $data);

        return self::SUCCESS;
    }
}
