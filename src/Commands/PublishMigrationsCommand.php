<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class PublishMigrationsCommand extends Command
{
    protected $signature = 'astroxs:publish-migrations {--force : Overwrite existing migrations}';
    protected $description = 'Publish the Astroxs migration files';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'astroxs-migrations',
            '--force' => $this->option('force'),
        ]);
        
        $this->info('✓ Migrations published to database/migrations/');
        
        return self::SUCCESS;
    }
}
