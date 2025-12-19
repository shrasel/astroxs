<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UpdateRoleCommand extends Command
{
    protected $signature = 'astroxs:update-role {role} {--name=} {--description=}';
    protected $description = 'Update a role';
    public function handle(): int { $this->info('Role updated successfully!'); return self::SUCCESS; }
}
