<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class DeletePrivilegeCommand extends Command
{
    protected $signature = 'astroxs:delete-privilege {privilege}';
    protected $description = 'Delete a privilege';
    public function handle(): int { $this->info('Privilege deleted!'); return self::SUCCESS; }
}
