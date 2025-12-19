<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class DocCommand extends Command
{
    protected $signature = 'astroxs:doc {--no-open : Print URL instead of opening}';
    protected $description = 'Open documentation site';
    
    public function handle(): int 
    { 
        $this->info('📖 Documentation: https://github.com/shrasel/astroxs#readme');
        return self::SUCCESS; 
    }
}
