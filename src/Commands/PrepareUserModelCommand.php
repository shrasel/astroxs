<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PrepareUserModelCommand extends Command
{
    protected $signature = 'astroxs:prepare-user-model';
    protected $description = 'Add HasApiTokens and HasAstroxsRoles traits to User model';

    public function handle(): int
    {
        $userModelPath = app_path('Models/User.php');

        if (!File::exists($userModelPath)) {
            $this->error('User model not found at ' . $userModelPath);
            return self::FAILURE;
        }

        $content = File::get($userModelPath);

        // Check if traits are already added
        if (Str::contains($content, 'HasAstroxsRoles')) {
            $this->info('HasAstroxsRoles trait already added to User model.');
            return self::SUCCESS;
        }

        // Add use statements
        if (!Str::contains($content, 'use Laravel\\Sanctum\\HasApiTokens;')) {
            $content = Str::replaceFirst(
                'use Illuminate\Foundation\Auth\User as Authenticatable;',
                "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Laravel\\Sanctum\\HasApiTokens;",
                $content
            );
        }

        if (!Str::contains($content, 'use Shrasel\\Astroxs\\Traits\\HasAstroxsRoles;')) {
            $content = Str::replaceFirst(
                'use Laravel\\Sanctum\\HasApiTokens;',
                "use Laravel\\Sanctum\\HasApiTokens;\nuse Shrasel\\Astroxs\\Traits\\HasAstroxsRoles;",
                $content
            );
        }

        // Add traits to class
        if (Str::contains($content, 'use HasFactory, Notifiable;')) {
            $content = Str::replaceFirst(
                'use HasFactory, Notifiable;',
                'use HasFactory, Notifiable, HasApiTokens, HasAstroxsRoles;',
                $content
            );
        } elseif (Str::contains($content, 'use Notifiable;')) {
            $content = Str::replaceFirst(
                'use Notifiable;',
                'use Notifiable, HasApiTokens, HasAstroxsRoles;',
                $content
            );
        }

        File::put($userModelPath, $content);

        $this->info('✓ User model prepared successfully!');
        $this->line('  Added: HasApiTokens trait');
        $this->line('  Added: HasAstroxsRoles trait');

        return self::SUCCESS;
    }
}
