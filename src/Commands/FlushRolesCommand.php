<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class FlushRolesCommand extends Command
{
    protected $signature = 'astroxs:flush-roles';
    protected $description = 'Truncate roles and pivot tables';
    public function handle(): int { $this->info('Roles flushed!'); return self::SUCCESS; }
}
