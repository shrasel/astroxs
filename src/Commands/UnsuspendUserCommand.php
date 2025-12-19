<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UnsuspendUserCommand extends Command
{
    protected $signature = 'astroxs:unsuspend-user {--user= : User ID or email}';
    protected $description = 'Unsuspend a user account';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $userIdentifier = $this->option('user') ?: $this->ask('Enter user ID or email');

        $user = is_numeric($userIdentifier)
            ? $userModel::find($userIdentifier)
            : $userModel::where('email', $userIdentifier)->first();

        if (!$user) {
            $this->error('User not found!');
            return self::FAILURE;
        }

        if (!method_exists($user, 'unsuspend')) {
            $this->error('User model must use HasAstroxsRoles trait!');
            return self::FAILURE;
        }

        $user->unsuspend();

        $this->info("✓ User {$user->email} unsuspended successfully!");

        return self::SUCCESS;
    }
}
