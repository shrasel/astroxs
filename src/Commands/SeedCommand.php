<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Database\Seeders\AstroxsSeeder;
use Illuminate\Console\Command;

class SeedCommand extends Command
{
    protected $signature = 'astroxs:seed {--force : Force the operation}';
    protected $description = 'Run the Astroxs seeder';

    public function handle(): int
    {
        $this->info('Seeding Astroxs data...');
        
        $seeder = new AstroxsSeeder();
        $seeder->run();
        
        $this->info('✓ Astroxs data seeded successfully!');
        
        return self::SUCCESS;
    }
}
