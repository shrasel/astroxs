<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Privilege;
use Illuminate\Console\Command;

class PrivilegesCommand extends Command
{
    protected $signature = 'astroxs:privileges';
    protected $description = 'List all privileges';
    
    public function handle(): int 
    { 
        $privileges = Privilege::with('roles')->get();
        $data = $privileges->map(fn($p) => [$p->id, $p->name, $p->slug, $p->roles->pluck('slug')->implode(', ')]);
        $this->table(['ID', 'Name', 'Slug', 'Roles'], $data);
        return self::SUCCESS; 
    }
}
