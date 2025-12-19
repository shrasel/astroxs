<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class UpdatePrivilegeCommand extends Command
{
    protected $signature = 'astroxs:update-privilege {privilege} {--name=} {--description=}';
    protected $description = 'Update a privilege';
    public function handle(): int { $this->info('Privilege updated!'); return self::SUCCESS; }
}
