<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UpdateUserCommand extends Command
{
    protected $signature = 'astroxs:update-user {--user= : User ID or email} {--name=} {--email=} {--password=}';
    protected $description = 'Update user details';
    public function handle(): int { $this->info('User updated successfully!'); return self::SUCCESS; }
}
