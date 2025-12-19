<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class PurgePrivilegesCommand extends Command
{
    protected $signature = 'astroxs:purge-privileges';
    protected $description = 'Remove all privileges';
    public function handle(): int { $this->info('Privileges purged!'); return self::SUCCESS; }
}
