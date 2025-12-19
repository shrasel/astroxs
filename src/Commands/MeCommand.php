<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class MeCommand extends Command
{
    protected $signature = 'astroxs:me';
    protected $description = 'Inspect a token to see user and abilities';

    public function handle(): int
    {
        $token = $this->ask('Paste your Sanctum token');

        if (empty($token)) {
            $this->error('Token cannot be empty!');
            return self::FAILURE;
        }

        // Parse token
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            $this->error('Invalid or expired token!');
            return self::FAILURE;
        }

        $user = $accessToken->tokenable;

        $this->info('✓ Token is valid!');
        $this->newLine();

        $this->table(
            ['Field', 'Value'],
            [
                ['User ID', $user->id],
                ['Name', $user->name],
                ['Email', $user->email],
                ['Token Name', $accessToken->name],
                ['Created', $accessToken->created_at->format('Y-m-d H:i:s')],
                ['Last Used', $accessToken->last_used_at?->format('Y-m-d H:i:s') ?? 'Never'],
            ]
        );

        $this->newLine();
        $this->info('Abilities:');
        foreach ($accessToken->abilities as $ability) {
            $this->line("  - {$ability}");
        }

        return self::SUCCESS;
    }
}
