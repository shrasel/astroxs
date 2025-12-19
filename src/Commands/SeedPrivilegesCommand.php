<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class SeedPrivilegesCommand extends Command
{
    protected $signature = 'astroxs:seed-privileges';
    protected $description = 'Seed default privileges';
    public function handle(): int { $this->info('Privileges seeded!'); return self::SUCCESS; }
}
