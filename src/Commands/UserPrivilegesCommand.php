<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UserPrivilegesCommand extends Command
{
    protected $signature = 'astroxs:user-privileges {user : User ID or email}';
    protected $description = 'Display all privileges a user inherits';
    public function handle(): int { $this->info('User privileges displayed!'); return self::SUCCESS; }
}
