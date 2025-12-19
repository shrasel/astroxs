<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class SuspendUserCommand extends Command
{
    protected $signature = 'astroxs:suspend-user 
                            {--user= : User ID or email}
                            {--reason= : Suspension reason}';
    protected $description = 'Suspend a user account';

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

        if (!method_exists($user, 'suspend')) {
            $this->error('User model must use HasAstroxsRoles trait!');
            return self::FAILURE;
        }

        $reason = $this->option('reason') ?: $this->ask('Enter suspension reason (optional)', null);

        $user->suspend($reason);

        $this->info("✓ User {$user->email} suspended successfully!");
        if ($reason) {
            $this->line("  Reason: {$reason}");
        }

        return self::SUCCESS;
    }
}
