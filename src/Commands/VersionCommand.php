<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class VersionCommand extends Command
{
    protected $signature = 'astroxs:version';
    protected $description = 'Display the Astroxs version';

    public function handle(): int
    {
        $version = config('astroxs.version', '1.0.0');
        
        $this->info("Astroxs v{$version}");
        $this->line('Authentication, Authorization & Role Management for Laravel 12');
        
        return self::SUCCESS;
    }
}
