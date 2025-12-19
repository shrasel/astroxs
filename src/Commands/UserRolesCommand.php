<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UserRolesCommand extends Command
{
    protected $signature = 'astroxs:user-roles {user}';
    protected $description = 'Display a user\'s roles and privileges';
    public function handle(): int { $this->info('User roles displayed!'); return self::SUCCESS; }
}
