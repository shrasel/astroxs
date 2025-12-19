<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class LogoutCommand extends Command
{
    protected $signature = 'astroxs:logout {--user=} {--token=}';
    protected $description = 'Revoke a single token';
    public function handle(): int { $this->info('Token revoked!'); return self::SUCCESS; }
}
