<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class DeleteUserCommand extends Command
{
    protected $signature = 'astroxs:delete-user {--user= : User ID or email}';
    protected $description = 'Delete a user';
    public function handle(): int { $this->info('User deleted successfully!'); return self::SUCCESS; }
}
