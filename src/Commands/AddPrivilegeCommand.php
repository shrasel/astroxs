<?php

namespace Shrasel\Astroxs\Commands;

use Shrasel\Astroxs\Models\Privilege;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddPrivilegeCommand extends Command
{
    protected $signature = 'astroxs:add-privilege 
                            {slug : Privilege slug}
                            {--name= : Privilege name}
                            {--description= : Privilege description}';
    protected $description = 'Create a new privilege';

    public function handle(): int
    {
        $slug = $this->argument('slug');
        $name = $this->option('name') ?: $this->ask('Enter privilege name', Str::title(str_replace(['.', '-', '_'], ' ', $slug)));
        $description = $this->option('description') ?: $this->ask('Enter privilege description (optional)', null);

        if (Privilege::where('slug', $slug)->exists()) {
            $this->error("Privilege with slug '{$slug}' already exists!");
            return self::FAILURE;
        }

        $privilege = Privilege::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        $this->info("✓ Privilege created successfully!");
        $this->table(
            ['ID', 'Name', 'Slug'],
            [[$privilege->id, $privilege->name, $privilege->slug]]
        );

        return self::SUCCESS;
    }
}
