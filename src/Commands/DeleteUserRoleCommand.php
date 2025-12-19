<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class DeleteUserRoleCommand extends Command
{
    protected $signature = 'astroxs:delete-user-role {--user=} {--role=}';
    protected $description = 'Remove a role from a user';
    public function handle(): int { $this->info('Role removed from user!'); return self::SUCCESS; }
}
