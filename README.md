# Astroxs

![Astroxs Banner](https://via.placeholder.com/1200x300/6366f1/ffffff?text=Astroxs+-+Laravel+Authentication+%26+Authorization)

**Complete Authentication, Authorization, and Role & Privilege Management for Laravel 12**

Astroxs is the ultimate RBAC (Role-Based Access Control) solution for Laravel 12. It provides full authentication with Laravel Sanctum, role-based access control, fine-grained privileges, user suspension workflows, 40+ CLI commands, 7 Blade directives, middleware, and optional REST API endpoints.

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.0%2B-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## ✨ Features

- 🔐 **Complete Auth System** - Full authentication with Sanctum, RBAC, and Laravel Gate integration
- 🛡️ **Security Hardened** - Sanctum tokens mirror role + privilege slugs, automatic token revocation on suspension
- 👥 **Roles & Privileges** - Flexible role-privilege system with protected roles
- 🎯 **User Suspension** - Freeze accounts instantly with automatic token revocation
- 🖥️ **40+ CLI Commands** - Manage users, roles, privileges, and tokens from terminal
- 🎨 **7 Blade Directives** - `@usercan`, `@hasrole`, `@hasanyrole`, `@hasroles`, `@hasprivilege`, `@hasanyprivilege`, `@hasprivileges`
- 🚦 **Powerful Middleware** - Route protection with role, privilege, and ability checks
- 📡 **Optional REST API** - Complete API endpoints (can be disabled)
- ⚡ **Performance Optimized** - Built-in caching for roles and privileges
- 🔧 **Highly Configurable** - Customize everything via config file

## 📋 Requirements

- **PHP:** 8.2 or higher
- **Laravel:** 12.0 or higher
- **Laravel Sanctum:** 4.0 or higher
- **Database:** MySQL, PostgreSQL, SQLite, or SQL Server

## 🚀 Installation

### Step 1: Install the Package

```bash
composer require shrasel/astroxs
```

### Step 2: Run the Installer

```bash
php artisan astroxs:install
```

This command automatically:
- Calls Laravel's `install:api` to set up Sanctum
- Runs database migrations
- Seeds default roles and privileges
- Prepares your User model with required traits

### Step 3: Verify Installation

```bash
php artisan astroxs:version
```

**That's it!** You now have complete authentication, authorization, roles, privileges, 7 Blade directives, middleware, and 40+ CLI commands.

## 🎯 Quick Start

### Default Admin Credentials

```
Email: admin@astroxs.project
Password: astroxs
```

⚠️ **Important:** Change the default admin password immediately!

### 1. Login with API

```bash
curl -X POST http://localhost/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@astroxs.project","password":"astroxs"}'
```

### 2. Use the Token

```bash
curl http://localhost/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Check Permissions in Code

```php
// Check roles
if ($user->hasRole('admin')) {
    // User is admin
}

// Check privileges
if ($user->can('reports.run')) {
    // User can run reports
}

// Get all role slugs
$roles = $user->astroxsRoleSlugs(); // ['admin', 'editor']

// Get all privilege slugs
$privileges = $user->astroxsPrivilegeSlugs(); // ['users.view', 'reports.run']
```

### 4. Use Blade Directives

```blade
@hasrole('admin')
    <p>Welcome, Admin!</p>
@endhasrole

@hasprivilege('edit-posts')
    <button>Edit Post</button>
@endhasprivilege

@hasanyrole('admin', 'editor', 'moderator')
    <div class="management-tools">
        <h3>Management Tools</h3>
    </div>
@endhasanyrole
```

### 5. Protect Routes

```php
use Illuminate\Support\Facades\Route;

// Require admin role
Route::middleware(['auth:sanctum', 'role:admin'])
    ->get('/admin/dashboard', DashboardController::class);

// Allow any of multiple roles
Route::middleware(['auth:sanctum', 'roles:admin,editor'])
    ->post('/articles', ArticleController::class);

// Require specific privilege
Route::middleware(['auth:sanctum', 'privilege:reports.run'])
    ->get('/reports', ReportController::class);
```

## 📚 Documentation

### Default Roles

Astroxs seeds these roles by default:

| Name | Slug | Description |
|------|------|-------------|
| Super Admin | super-admin | Full system access with all privileges |
| Administrator | admin | Administrative access to manage users and roles |
| Editor | editor | Content management privileges |
| User | user | Default role for new registrations |
| Customer | customer | Customer-specific access |

### HasAstroxsRoles Trait Methods

Add to your User model:

```php
use Shrasel\Astroxs\Traits\HasAstroxsRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasAstroxsRoles;
}
```

**Role Methods:**
- `roles(): BelongsToMany` - Relationship for roles
- `assignRole(Role $role): void` - Assign a role
- `removeRole(Role $role): void` - Remove a role
- `hasRole(string $role): bool` - Check if user has role
- `hasRoles(array $roles): bool` - Check if user has all roles
- `astroxsRoleSlugs(): array` - Get all role slugs (cached)

**Privilege Methods:**
- `privileges(): Collection` - Get all privileges through roles
- `hasPrivilege(string $privilege): bool` - Check privilege
- `hasPrivileges(array $privileges): bool` - Check multiple privileges
- `astroxsPrivilegeSlugs(): array` - Get all privilege slugs (cached)
- `can($ability, $args = []): bool` - Check privilege, role, then Gate

**Suspension Methods:**
- `suspend(?string $reason = null): void` - Suspend user and revoke tokens
- `unsuspend(): void` - Clear suspension
- `isSuspended(): bool` - Check suspension status
- `getSuspensionReason(): ?string` - Get suspension reason

### Middleware

| Middleware | Usage | Description |
|------------|-------|-------------|
| `ability` | `ability:admin,reports.run` | Require ALL abilities |
| `abilities` | `abilities:admin,editor` | Allow ANY ability |
| `role` | `role:admin` | Require ALL roles |
| `roles` | `roles:admin,editor` | Allow ANY role |
| `privilege` | `privilege:reports.run` | Require ALL privileges |
| `privileges` | `privileges:billing.view` | Allow ANY privilege |
| `astroxs.log` | `astroxs.log` | Log requests/responses |

### Blade Directives

- `@usercan('ability')` - Check if user can (via `can()` method)
- `@hasrole('admin')` - Check specific role
- `@hasanyrole('admin', 'editor')` - Check any of roles
- `@hasroles('admin', 'super-admin')` - Check all roles
- `@hasprivilege('delete-users')` - Check specific privilege
- `@hasanyprivilege('edit-posts', 'delete-posts')` - Check any privilege
- `@hasprivileges('create-invoices', 'approve-invoices')` - Check all privileges

All directives automatically return `false` if no user is authenticated.

## 🖥️ Artisan Commands

Astroxs provides 40+ powerful CLI commands:

### Setup & Installation (5 commands)

```bash
php artisan astroxs:install                 # Install Astroxs
php artisan astroxs:prepare-user-model      # Add traits to User model
php artisan astroxs:seed                    # Seed roles and privileges
php artisan astroxs:publish-config          # Publish config file
php artisan astroxs:publish-migrations      # Publish migrations
```

### User Management (9 commands)

```bash
php artisan astroxs:create-user             # Create new user
php artisan astroxs:users                   # List all users
php artisan astroxs:users-with-roles        # List users with roles
php artisan astroxs:suspend-user            # Suspend user account
php artisan astroxs:unsuspend-user          # Unsuspend user
php artisan astroxs:suspended-users         # Show suspended users
php artisan astroxs:update-user             # Update user details
php artisan astroxs:delete-user             # Delete user
php artisan astroxs:user-privileges         # Show user privileges
```

### Role Management (11 commands)

```bash
php artisan astroxs:roles                   # List all roles
php artisan astroxs:roles-with-privileges   # List roles with privileges
php artisan astroxs:create-role             # Create new role
php artisan astroxs:update-role             # Update role
php artisan astroxs:delete-role             # Delete role
php artisan astroxs:assign-role             # Assign role to user
php artisan astroxs:delete-user-role        # Remove role from user
php artisan astroxs:role-users              # List users with role
php artisan astroxs:user-roles              # Show user's roles
php artisan astroxs:seed-roles              # Re-seed roles
php artisan astroxs:flush-roles             # Truncate roles
```

### Privilege Management (7 commands)

```bash
php artisan astroxs:privileges              # List all privileges
php artisan astroxs:add-privilege           # Create privilege
php artisan astroxs:update-privilege        # Update privilege
php artisan astroxs:delete-privilege        # Delete privilege
php artisan astroxs:attach-privilege        # Attach privilege to role
php artisan astroxs:detach-privilege        # Detach privilege from role
php artisan astroxs:seed-privileges         # Re-seed privileges
php artisan astroxs:purge-privileges        # Remove all privileges
```

### Token Management (6 commands)

```bash
php artisan astroxs:login                   # Generate token with password
php artisan astroxs:quick-token             # Generate token without password
php artisan astroxs:me                      # Inspect token
php artisan astroxs:logout                  # Revoke single token
php artisan astroxs:logout-all              # Revoke user's tokens
php artisan astroxs:logout-all-users        # Revoke all tokens
```

### Utilities (7 commands)

```bash
php artisan astroxs:version                 # Show version
php artisan astroxs:postman-collection      # Get Postman collection
php artisan astroxs:star                    # Star on GitHub
php artisan astroxs:doc                     # Open documentation
php artisan astroxs:about                   # About Astroxs
```

## 📡 API Endpoints

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/login | Authenticate and get token |
| POST | /api/users | Register new user |
| GET | /api/astroxs | Get package info |
| GET | /api/astroxs/version | Get version |

### Authenticated Endpoints

**User Management** (requires auth:sanctum)

| Method | Endpoint | Middleware |
|--------|----------|------------|
| GET | /api/me | auth:sanctum |
| GET | /api/users | ability:admin,super-admin |
| GET | /api/users/{user} | ability:admin,super-admin |
| PUT/PATCH | /api/users/{user} | auth:sanctum (own profile) |
| DELETE | /api/users/{user} | ability:admin,super-admin |
| POST | /api/users/{user}/suspend | ability:admin,super-admin |
| DELETE | /api/users/{user}/suspend | ability:admin,super-admin |

**Role Management** (requires admin abilities)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/roles | List roles |
| POST | /api/roles | Create role |
| GET | /api/roles/{role} | Show role |
| PUT/PATCH | /api/roles/{role} | Update role |
| DELETE | /api/roles/{role} | Delete role |
| GET | /api/users/{user}/roles | User's roles |
| POST | /api/users/{user}/roles | Assign role |
| DELETE | /api/users/{user}/roles/{role} | Remove role |

**Privilege Management** (requires admin abilities)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/privileges | List privileges |
| POST | /api/privileges | Create privilege |
| GET | /api/privileges/{privilege} | Show privilege |
| PUT/PATCH | /api/privileges/{privilege} | Update privilege |
| DELETE | /api/privileges/{privilege} | Delete privilege |
| GET | /api/roles/{role}/privileges | Role's privileges |
| POST | /api/roles/{role}/privileges | Attach privilege |
| DELETE | /api/roles/{role}/privileges/{privilege} | Detach privilege |

## ⚙️ Configuration

Publish the config file:

```bash
php artisan astroxs:publish-config
```

**config/astroxs.php:**

```php
return [
    'guard' => env('ASTROXS_GUARD', 'sanctum'),
    'route_prefix' => env('ASTROXS_ROUTE_PREFIX', 'api'),
    'default_user_role_slug' => env('ASTROXS_DEFAULT_ROLE', 'user'),
    
    'cache' => [
        'enabled' => env('ASTROXS_CACHE_ENABLED', true),
        'ttl' => env('ASTROXS_CACHE_TTL', 3600),
    ],
    
    'disable_api' => env('ASTROXS_DISABLE_API', false),
    'disable_commands' => env('ASTROXS_DISABLE_COMMANDS', false),
    
    'protected_roles' => ['super-admin', 'admin'],
];
```

### Environment Variables

```env
ASTROXS_GUARD=sanctum
ASTROXS_ROUTE_PREFIX=api
ASTROXS_DEFAULT_ROLE=user
ASTROXS_CACHE_ENABLED=true
ASTROXS_CACHE_TTL=3600
ASTROXS_DISABLE_API=false
ASTROXS_DISABLE_COMMANDS=false
```

## 🔒 User Suspension

Suspend users to freeze accounts without deleting them:

**Via CLI:**
```bash
php artisan astroxs:suspend-user --user=user@example.com --reason="Policy violation"
php artisan astroxs:unsuspend-user --user=user@example.com
```

**Via Code:**
```php
// Suspend user
$user->suspend('Policy violation');

// Check suspension
if ($user->isSuspended()) {
    $reason = $user->getSuspensionReason();
    return response()->json(['error' => $reason], 423);
}

// Unsuspend
$user->unsuspend();
```

⚠️ **Important:** Suspending automatically revokes all Sanctum tokens and prevents login.

## 🎨 Usage Examples

### Protecting Controller Actions

```php
class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Check privilege
        if (!$request->user()->can('reports.run')) {
            abort(403, 'Missing reports privilege');
        }
        
        // Check multiple privileges
        if (!$request->user()->hasPrivileges(['reports.run', 'billing.view'])) {
            abort(403, 'Missing required privileges');
        }
        
        return view('reports.index');
    }
}
```

### Creating Custom Roles and Privileges

```php
use Astroxs\Astroxs\Models\Role;
use Astroxs\Astroxs\Models\Privilege;

// Create role
$managerRole = Role::create([
    'name' => 'Manager',
    'slug' => 'manager',
    'description' => 'Department manager',
]);

// Create privilege
$approvePrivilege = Privilege::create([
    'name' => 'Approve Requests',
    'slug' => 'requests.approve',
]);

// Attach privilege to role
$managerRole->privileges()->attach($approvePrivilege->id);

// Assign role to user
$user->assignRole($managerRole);
```

### Using in Views

```blade
<nav>
    @hasrole('admin')
        <a href="/admin/dashboard">Admin Panel</a>
    @endhasrole
    
    @hasanyrole('editor', 'author')
        <a href="/content">Content Management</a>
    @endhasanyrole
    
    @hasprivilege('view-reports')
        <a href="/reports">Reports</a>
    @endhasprivilege
</nav>

@hasanyprivilege('edit-posts', 'delete-posts', 'publish-posts')
    <div class="post-actions">
        @hasprivilege('edit-posts')
            <button>Edit</button>
        @endhasprivilege
        
        @hasprivilege('delete-posts')
            <button>Delete</button>
        @endhasprivilege
        
        @hasprivilege('publish-posts')
            <button>Publish</button>
        @endhasprivilege
    </div>
@endhasanyprivilege
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

Astroxs is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

Astroxs is inspired by Laravel's authorization system and built on top of Laravel Sanctum. Special thanks to the Laravel community.

## 📞 Support

- **GitHub Issues:** [Report bugs or request features](https://github.com/shrasel/astroxs/issues)
- **Documentation:** [Full documentation](https://github.com/shrasel/astroxs#readme)

---

**Built with ❤️ for the Laravel community**

⭐ If Astroxs saves you time, please star the repository!
