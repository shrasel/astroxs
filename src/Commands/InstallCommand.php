<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'astroxs:install {--force : Force the operation}';
    protected $description = 'Install Astroxs package';

    public function handle(): int
    {
        $this->info('Installing Astroxs...');

        // Install Sanctum API
        $this->info('Setting up Laravel Sanctum...');
        Artisan::call('install:api', ['--quiet' => true]);
        $this->info('✓ Sanctum installed');

        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => $this->option('force')]);
        $this->info('✓ Migrations completed');

        // Seed database
        if ($this->confirm('Do you want to seed default roles and privileges?', true)) {
            Artisan::call('astroxs:seed', ['--force' => $this->option('force')]);
            $this->info('✓ Database seeded');
        }

        // Prepare User model
        if ($this->confirm('Do you want to automatically prepare your User model?', true)) {
            Artisan::call('astroxs:prepare-user-model');
        }

        $this->newLine();
        $this->info('🎉 Astroxs installed successfully!');
        $this->newLine();
        $this->info('Default admin credentials:');
        $this->line('  Email: admin@astroxs.project');
        $this->line('  Password: astroxs');
        $this->newLine();
        $this->warn('⚠️  Please change the default admin password immediately!');
        
        return self::SUCCESS;
    }
}
