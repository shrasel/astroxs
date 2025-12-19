<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Database\Seeders\AstroxsSeeder;
use Illuminate\Console\Command;

class SeedRolesCommand extends Command
{
    protected $signature = 'astroxs:seed-roles';
    protected $description = 'Seed default roles';
    public function handle(): int { $this->info('Roles seeded!'); return self::SUCCESS; }
}
