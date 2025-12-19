<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class LogoutAllUsersCommand extends Command
{
    protected $signature = 'astroxs:logout-all-users {--force : Skip confirmation}';
    protected $description = 'Revoke all tokens for all users';

    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will revoke ALL tokens for ALL users. Are you sure?', false)) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
        }

        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::all();
        $count = 0;

        foreach ($users as $user) {
            if (method_exists($user, 'tokens')) {
                $tokenCount = $user->tokens()->count();
                $user->tokens()->delete();
                $count += $tokenCount;
            }
        }

        $this->info("✓ Revoked {$count} tokens from {$users->count()} users");

        return self::SUCCESS;
    }
}
