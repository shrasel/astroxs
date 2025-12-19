<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateRoleCommand extends Command
{
    protected $signature = 'astroxs:create-role 
                            {--name= : Role name}
                            {--slug= : Role slug}
                            {--description= : Role description}';
    protected $description = 'Create a new role';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Enter role name');
        $slug = $this->option('slug') ?: $this->ask('Enter role slug', Str::slug($name));
        $description = $this->option('description') ?: $this->ask('Enter role description (optional)', null);

        if (Role::where('slug', $slug)->exists()) {
            $this->error("Role with slug '{$slug}' already exists!");
            return self::FAILURE;
        }

        $role = Role::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_protected' => false,
        ]);

        $this->info("✓ Role created successfully!");
        $this->table(
            ['ID', 'Name', 'Slug'],
            [[$role->id, $role->name, $role->slug]]
        );

        return self::SUCCESS;
    }
}
