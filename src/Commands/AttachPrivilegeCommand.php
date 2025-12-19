<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Privilege;
use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;

class AttachPrivilegeCommand extends Command
{
    protected $signature = 'astroxs:attach-privilege 
                            {privilege : Privilege slug or ID}
                            {role : Role slug or ID}';
    protected $description = 'Attach a privilege to a role';

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

        if ($role->privileges()->where('privilege_id', $privilege->id)->exists()) {
            $this->warn("Privilege '{$privilege->slug}' is already attached to role '{$role->slug}'");
            return self::SUCCESS;
        }

        $role->privileges()->attach($privilege->id);

        $this->info("✓ Privilege '{$privilege->slug}' attached to role '{$role->slug}'");

        return self::SUCCESS;
    }
}
