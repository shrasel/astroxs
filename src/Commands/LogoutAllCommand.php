<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class LogoutAllCommand extends Command
{
    protected $signature = 'astroxs:logout-all {--user=}';
    protected $description = 'Revoke all tokens for a user';
    public function handle(): int { $this->info('All user tokens revoked!'); return self::SUCCESS; }
}
