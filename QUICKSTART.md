# Astroxs Quick Start Guide

Get up and running with Astroxs in 5 minutes!

## Installation

```bash
# 1. Install package
composer require shrasel/astroxs

# 2. Run installer (sets up Sanctum, migrations, seeds, and prepares User model)
php artisan astroxs:install

# 3. Verify installation
php artisan astroxs:version
```

## Default Credentials

```
Email: admin@astroxs.project
Password: astroxs
```

⚠️ **Change immediately after first login!**

## Basic Usage

### 1. Generate API Token

```bash
# Via CLI
php artisan astroxs:login --user=admin@astroxs.project

# Or via API
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@astroxs.project","password":"astroxs"}'
```

### 2. Use Token

```bash
curl http://localhost/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Add Trait to User Model

The installer does this automatically, but here's what it adds:

```php
use Laravel\Sanctum\HasApiTokens;
use Shrasel\Astroxs\Traits\HasAstroxsRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasAstroxsRoles;
}
```

### 4. Check Permissions

```php
// In controllers
if ($user->hasRole('admin')) {
    // User is admin
}

if ($user->can('reports.run')) {
    // User can run reports
}

if ($user->hasPrivileges(['users.edit', 'users.delete'])) {
    // User has both privileges
}
```

### 5. Protect Routes

```php
// routes/api.php
Route::middleware(['auth:sanctum', 'role:admin'])
    ->get('/admin/dashboard', [DashboardController::class, 'index']);

Route::middleware(['auth:sanctum', 'privilege:reports.run'])
    ->get('/reports', [ReportController::class, 'index']);
```

### 6. Use in Blade

```blade
@hasrole('admin')
    <a href="/admin">Admin Panel</a>
@endhasrole

@hasprivilege('edit-posts')
    <button>Edit</button>
@endhasprivilege
```

## Common Commands

```bash
# User management
php artisan astroxs:create-user
php artisan astroxs:users
php artisan astroxs:suspend-user --user=user@example.com

# Role management
php artisan astroxs:create-role
php artisan astroxs:assign-role --user=1 --role=editor
php artisan astroxs:roles-with-privileges

# Privilege management
php artisan astroxs:add-privilege custom.action --name="Custom Action"
php artisan astroxs:attach-privilege custom.action editor

# Token management
php artisan astroxs:quick-token --user=1
php artisan astroxs:me
```

## Creating Custom Role & Privileges

```php
use Shrasel\Astroxs\Models\Role;
use Shrasel\Astroxs\Models\Privilege;

// Create role
$role = Role::create([
    'name' => 'Manager',
    'slug' => 'manager',
    'description' => 'Department manager',
]);

// Create privilege
$privilege = Privilege::create([
    'name' => 'Approve Requests',
    'slug' => 'requests.approve',
]);

// Attach privilege to role
$role->privileges()->attach($privilege->id);

// Assign role to user
$user->assignRole($role);
```

## User Suspension

```php
// Suspend (revokes all tokens automatically)
$user->suspend('Policy violation');

// Check if suspended
if ($user->isSuspended()) {
    $reason = $user->getSuspensionReason();
}

// Unsuspend
$user->unsuspend();
```

## Configuration

```bash
# Publish config
php artisan astroxs:publish-config
```

Edit `config/astroxs.php`:

```php
return [
    'disable_api' => false,              // Set true to disable API routes
    'disable_commands' => false,         // Set true in production
    'default_user_role_slug' => 'user',  // Default role for new users
    'cache' => [
        'enabled' => true,               // Enable caching
        'ttl' => 3600,                   // Cache TTL in seconds
    ],
];
```

## Default Roles & Privileges

**Roles:**
- `super-admin` - Full system access
- `admin` - User and role management
- `editor` - Content management
- `user` - Basic user access
- `customer` - Customer-specific access

**Privilege Categories:**
- User Management: `users.*`
- Role Management: `roles.*`
- Privilege Management: `privileges.*`
- Content: `posts.*`
- Reports: `reports.*`
- Billing: `billing.*`

## Troubleshooting

**Trait not found?**
```bash
php artisan astroxs:prepare-user-model
```

**Migrations not running?**
```bash
php artisan migrate
```

**Need to re-seed?**
```bash
php artisan astroxs:seed --force
```

**API routes not working?**
Check that `ASTROXS_DISABLE_API=false` in `.env`

## Next Steps

- Read the [full documentation](README.md)
- Explore the [API endpoints](README.md#-api-endpoints)
- Check out [all 40+ commands](README.md#-artisan-commands)
- Learn about [middleware](README.md#middleware)
- Master [Blade directives](README.md#blade-directives)

## Support

- GitHub Issues: https://github.com/shrasel/astroxs/issues
- Documentation: https://github.com/shrasel/astroxs#readme

---

Happy coding with Astroxs! 🚀
