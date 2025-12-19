<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class PostmanCollectionCommand extends Command
{
    protected $signature = 'astroxs:postman-collection {--no-open : Print URL instead of opening}';
    protected $description = 'Open Postman collection URL';
    
    public function handle(): int 
    { 
        $url = 'https://github.com/shrasel/astroxs';
        $this->info("Postman Collection: {$url}");
        return self::SUCCESS; 
    }
}
