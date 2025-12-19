<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishConfigCommand extends Command
{
    protected $signature = 'astroxs:publish-config {--force : Overwrite existing config}';
    protected $description = 'Publish the Astroxs configuration file';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'astroxs-config',
            '--force' => $this->option('force'),
        ]);
        
        $this->info('✓ Configuration file published to config/astroxs.php');
        
        return self::SUCCESS;
    }
}
