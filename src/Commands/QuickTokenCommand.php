<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class QuickTokenCommand extends Command
{
    protected $signature = 'astroxs:quick-token {--user= : User ID or email}';
    protected $description = 'Generate a token without password (development only)';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('This command is not available in production!');
            return self::FAILURE;
        }

        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $userIdentifier = $this->option('user') ?: $this->ask('Enter user ID or email');

        $user = is_numeric($userIdentifier)
            ? $userModel::find($userIdentifier)
            : $userModel::where('email', $userIdentifier)->first();

        if (!$user) {
            $this->error('User not found!');
            return self::FAILURE;
        }

        // Check if user is suspended
        if (method_exists($user, 'isSuspended') && $user->isSuspended()) {
            $reason = $user->getSuspensionReason();
            $this->error("User is suspended. Reason: " . ($reason ?: 'No reason provided'));
            return self::FAILURE;
        }

        // Get abilities (roles + privileges)
        $abilities = ['*'];
        if (method_exists($user, 'astroxsRoleSlugs')) {
            $abilities = array_merge(
                $user->astroxsRoleSlugs(),
                $user->astroxsPrivilegeSlugs()
            );
        }

        $token = $user->createToken('astroxs-quick-token', $abilities)->plainTextToken;

        $this->info('✓ Quick token generated!');
        $this->newLine();
        $this->line($token);
        $this->newLine();

        return self::SUCCESS;
    }
}
