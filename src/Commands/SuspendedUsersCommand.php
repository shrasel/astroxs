<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class SuspendedUsersCommand extends Command
{
    protected $signature = 'astroxs:suspended-users';
    protected $description = 'Show all suspended users';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::whereNotNull('suspended_at')->get();

        if ($users->isEmpty()) {
            $this->info('No suspended users found.');
            return self::SUCCESS;
        }

        $data = $users->map(function ($user) {
            return [
                $user->id,
                $user->name,
                $user->email,
                $user->suspended_at?->format('Y-m-d H:i:s'),
                $user->suspension_reason ?: 'N/A',
            ];
        });

        $this->table(['ID', 'Name', 'Email', 'Suspended At', 'Reason'], $data);

        return self::SUCCESS;
    }
}
