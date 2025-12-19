<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UsersCommand extends Command
{
    protected $signature = 'astroxs:users';
    protected $description = 'List all users with their roles and suspension status';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::with('roles')->get();

        if ($users->isEmpty()) {
            $this->warn('No users found.');
            return self::SUCCESS;
        }

        $data = $users->map(function ($user) {
            $roles = method_exists($user, 'roles') 
                ? $user->roles->pluck('slug')->implode(', ') 
                : 'N/A';
            
            $suspended = method_exists($user, 'isSuspended') && $user->isSuspended() ? 'Yes' : 'No';

            return [
                $user->id,
                $user->name,
                $user->email,
                $roles ?: 'none',
                $suspended,
            ];
        });

        $this->table(['ID', 'Name', 'Email', 'Roles', 'Suspended'], $data);

        return self::SUCCESS;
    }
}
