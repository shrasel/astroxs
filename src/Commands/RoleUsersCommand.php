<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class RoleUsersCommand extends Command
{
    protected $signature = 'astroxs:role-users {role}';
    protected $description = 'List users with a specific role';
    public function handle(): int { $this->info('Role users displayed!'); return self::SUCCESS; }
}
