<?php

namespace Shrasel\Astroxs\Database\Seeders;

use Shrasel\Astroxs\Models\Role;
use Shrasel\Astroxs\Models\Privilege;
use Illuminate\Database\Seeder;

class AstroxsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedPrivileges();
        $this->attachPrivilegesToRoles();
        $this->createBootstrapAdmin();
    }

    /**
     * Seed default roles.
     */
    protected function seedRoles(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all privileges',
                'is_protected' => true,
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrative access to manage users and roles',
                'is_protected' => true,
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Content management privileges',
                'is_protected' => false,
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Default role for new registrations',
                'is_protected' => false,
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Customer-specific access',
                'is_protected' => false,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }

    /**
     * Seed default privileges.
     */
    protected function seedPrivileges(): void
    {
        $privileges = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'View user list'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Edit user details'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Delete users'],
            ['name' => 'Suspend Users', 'slug' => 'users.suspend', 'description' => 'Suspend user accounts'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'View role list'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Edit role details'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Delete roles'],
            ['name' => 'Assign Roles', 'slug' => 'roles.assign', 'description' => 'Assign roles to users'],
            
            // Privilege Management
            ['name' => 'View Privileges', 'slug' => 'privileges.view', 'description' => 'View privilege list'],
            ['name' => 'Create Privileges', 'slug' => 'privileges.create', 'description' => 'Create new privileges'],
            ['name' => 'Edit Privileges', 'slug' => 'privileges.edit', 'description' => 'Edit privilege details'],
            ['name' => 'Delete Privileges', 'slug' => 'privileges.delete', 'description' => 'Delete privileges'],
            ['name' => 'Attach Privileges', 'slug' => 'privileges.attach', 'description' => 'Attach privileges to roles'],
            
            // Reports
            ['name' => 'Run Reports', 'slug' => 'reports.run', 'description' => 'Execute reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'description' => 'Export report data'],
            
            // Billing
            ['name' => 'View Billing', 'slug' => 'billing.view', 'description' => 'View billing information'],
            ['name' => 'Manage Billing', 'slug' => 'billing.manage', 'description' => 'Manage billing settings'],
            
            // Content
            ['name' => 'Create Posts', 'slug' => 'posts.create', 'description' => 'Create new posts'],
            ['name' => 'Edit Posts', 'slug' => 'posts.edit', 'description' => 'Edit posts'],
            ['name' => 'Delete Posts', 'slug' => 'posts.delete', 'description' => 'Delete posts'],
            ['name' => 'Publish Posts', 'slug' => 'posts.publish', 'description' => 'Publish posts'],
        ];

        foreach ($privileges as $privilegeData) {
            Privilege::updateOrCreate(
                ['slug' => $privilegeData['slug']],
                $privilegeData
            );
        }
    }

    /**
     * Attach privileges to roles.
     */
    protected function attachPrivilegesToRoles(): void
    {
        // Super Admin gets all privileges
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $allPrivileges = Privilege::all();
        $superAdmin->privileges()->sync($allPrivileges->pluck('id')->toArray());

        // Admin gets user and role management
        $admin = Role::where('slug', 'admin')->first();
        $adminPrivileges = Privilege::whereIn('slug', [
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.suspend',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign',
            'privileges.view', 'privileges.attach',
        ])->get();
        $admin->privileges()->sync($adminPrivileges->pluck('id')->toArray());

        // Editor gets content management
        $editor = Role::where('slug', 'editor')->first();
        $editorPrivileges = Privilege::whereIn('slug', [
            'posts.create', 'posts.edit', 'posts.delete', 'posts.publish',
            'reports.run',
        ])->get();
        $editor->privileges()->sync($editorPrivileges->pluck('id')->toArray());

        // User gets basic privileges
        $user = Role::where('slug', 'user')->first();
        $userPrivileges = Privilege::whereIn('slug', [
            'posts.create', 'posts.edit',
        ])->get();
        $user->privileges()->sync($userPrivileges->pluck('id')->toArray());

        // Customer gets billing access
        $customer = Role::where('slug', 'customer')->first();
        $customerPrivileges = Privilege::whereIn('slug', [
            'billing.view',
        ])->get();
        $customer->privileges()->sync($customerPrivileges->pluck('id')->toArray());
    }

    /**
     * Create bootstrap admin user.
     */
    protected function createBootstrapAdmin(): void
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        
        $admin = $userModel::firstOrCreate(
            ['email' => 'admin@astroxs.project'],
            [
                'name' => 'Astroxs Admin',
                'password' => bcrypt('astroxs'),
            ]
        );

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        
        if ($admin && $superAdminRole && method_exists($admin, 'roles')) {
            if (!$admin->roles()->where('role_id', $superAdminRole->id)->exists()) {
                $admin->roles()->attach($superAdminRole->id);
            }
        }
    }
}
