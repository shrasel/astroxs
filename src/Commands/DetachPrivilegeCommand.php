<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Privilege;
use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;

class DetachPrivilegeCommand extends Command
{
    protected $signature = 'astroxs:detach-privilege 
                            {privilege : Privilege slug or ID}
                            {role : Role slug or ID}';
    protected $description = 'Detach a privilege from a role';

    public function handle(): int
    {
        $privilegeIdentifier = $this->argument('privilege');
        $roleIdentifier = $this->argument('role');

        $privilege = is_numeric($privilegeIdentifier)
            ? Privilege::find($privilegeIdentifier)
            : Privilege::where('slug', $privilegeIdentifier)->first();

        if (!$privilege) {
            $this->error('Privilege not found!');
            return self::FAILURE;
        }

        $role = is_numeric($roleIdentifier)
            ? Role::find($roleIdentifier)
            : Role::where('slug', $roleIdentifier)->first();

        if (!$role) {
            $this->error('Role not found!');
            return self::FAILURE;
        }

        $role->privileges()->detach($privilege->id);

        $this->info("✓ Privilege '{$privilege->slug}' detached from role '{$role->slug}'");

        return self::SUCCESS;
    }
}
