<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class LoginCommand extends Command
{
    protected $signature = 'astroxs:login {--user= : User ID or email}';
    protected $description = 'Generate a Sanctum token with password verification';

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

        // Check if user is suspended
        if (method_exists($user, 'isSuspended') && $user->isSuspended()) {
            $reason = $user->getSuspensionReason();
            $this->error("User is suspended. Reason: " . ($reason ?: 'No reason provided'));
            return self::FAILURE;
        }

        $password = $this->secret('Enter password');

        if (!Hash::check($password, $user->password)) {
            $this->error('Invalid password!');
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

        $token = $user->createToken('astroxs-token', $abilities)->plainTextToken;

        $this->info('✓ Token generated successfully!');
        $this->newLine();
        $this->line($token);
        $this->newLine();

        return self::SUCCESS;
    }
}
