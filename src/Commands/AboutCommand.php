<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class AboutCommand extends Command
{
    protected $signature = 'astroxs:about';
    protected $description = 'Display Astroxs information';
    
    public function handle(): int 
    { 
        $this->info('Astroxs - Authentication, Authorization & Role Management');
        $this->line('Version: ' . config('astroxs.version', '1.0.0'));
        $this->line('Complete RBAC solution for Laravel 12');
        $this->newLine();
        $this->line('GitHub: https://github.com/shrasel/astroxs');
        return self::SUCCESS; 
    }
}
