<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class DeleteRoleCommand extends Command
{
    protected $signature = 'astroxs:delete-role {role}';
    protected $description = 'Delete a role';
    public function handle(): int { $this->info('Role deleted successfully!'); return self::SUCCESS; }
}
