<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class StarCommand extends Command
{
    protected $signature = 'astroxs:star';
    protected $description = 'Open GitHub repo to star';
    
    public function handle(): int 
    { 
        $this->info('⭐ Star Astroxs on GitHub: https://github.com/shrasel/astroxs');
        return self::SUCCESS; 
    }
}
