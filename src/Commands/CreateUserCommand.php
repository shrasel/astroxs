<?php

namespace Shrasel\Astroxs\Commands;

use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $signature = 'astroxs:create-user 
                            {--name= : User name}
                            {--email= : User email}
                            {--password= : User password}';
    protected $description = 'Create a new user';

    public function handle(): int
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        $name = $this->option('name') ?: $this->ask('Enter user name');
        $email = $this->option('email') ?: $this->ask('Enter user email');
        $password = $this->option('password') ?: $this->secret('Enter user password');

        if ($userModel::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return self::FAILURE;
        }

        $user = $userModel::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        // Assign default role
        if (method_exists($user, 'assignRole')) {
            $defaultRole = \Shrasel\Astroxs\Models\Role::where('slug', config('astroxs.default_user_role_slug', 'user'))->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        }

        $this->info("✓ User created successfully!");
        $this->table(
            ['ID', 'Name', 'Email'],
            [[$user->id, $user->name, $user->email]]
        );

        return self::SUCCESS;
    }
}
