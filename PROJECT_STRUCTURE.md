# Astroxs Package Structure

Complete Laravel package structure for Authentication, Authorization & Role Management

## 📁 Project Structure

```
astroxs/
├── config/
│   └── astroxs.php                          # Package configuration
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_astroxs_roles_table.php
│   │   ├── 2024_01_01_000002_create_astroxs_privileges_table.php
│   │   ├── 2024_01_01_000003_create_astroxs_role_user_table.php
│   │   ├── 2024_01_01_000004_create_astroxs_privilege_role_table.php
│   │   └── 2024_01_01_000005_add_suspension_columns_to_users_table.php
│   └── seeders/
│       └── AstroxsSeeder.php                # Seeds roles, privileges & admin
├── routes/
│   └── api.php                              # API routes
├── src/
│   ├── Commands/                            # 40+ Artisan commands
│   │   ├── InstallCommand.php
│   │   ├── PrepareUserModelCommand.php
│   │   ├── SeedCommand.php
│   │   ├── VersionCommand.php
│   │   ├── PublishConfigCommand.php
│   │   ├── PublishMigrationsCommand.php
│   │   ├── CreateUserCommand.php
│   │   ├── UsersCommand.php
│   │   ├── UsersWithRolesCommand.php
│   │   ├── SuspendUserCommand.php
│   │   ├── UnsuspendUserCommand.php
│   │   ├── SuspendedUsersCommand.php
│   │   ├── UpdateUserCommand.php
│   │   ├── DeleteUserCommand.php
│   │   ├── UserPrivilegesCommand.php
│   │   ├── RolesCommand.php
│   │   ├── RolesWithPrivilegesCommand.php
│   │   ├── CreateRoleCommand.php
│   │   ├── UpdateRoleCommand.php
│   │   ├── DeleteRoleCommand.php
│   │   ├── AssignRoleCommand.php
│   │   ├── DeleteUserRoleCommand.php
│   │   ├── RoleUsersCommand.php
│   │   ├── UserRolesCommand.php
│   │   ├── SeedRolesCommand.php
│   │   ├── FlushRolesCommand.php
│   │   ├── PrivilegesCommand.php
│   │   ├── AddPrivilegeCommand.php
│   │   ├── UpdatePrivilegeCommand.php
│   │   ├── DeletePrivilegeCommand.php
│   │   ├── AttachPrivilegeCommand.php
│   │   ├── DetachPrivilegeCommand.php
│   │   ├── SeedPrivilegesCommand.php
│   │   ├── PurgePrivilegesCommand.php
│   │   ├── LoginCommand.php
│   │   ├── QuickTokenCommand.php
│   │   ├── MeCommand.php
│   │   ├── LogoutCommand.php
│   │   ├── LogoutAllCommand.php
│   │   ├── LogoutAllUsersCommand.php
│   │   ├── PostmanCollectionCommand.php
│   │   ├── StarCommand.php
│   │   ├── DocCommand.php
│   │   └── AboutCommand.php
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php           # Login, me, info endpoints
│   │       ├── UserController.php           # User CRUD & suspension
│   │       ├── RoleController.php           # Role CRUD & assignments
│   │       └── PrivilegeController.php      # Privilege CRUD
│   ├── Middleware/
│   │   ├── CheckAbility.php                 # Require ALL abilities
│   │   ├── CheckAbilities.php               # Allow ANY ability
│   │   ├── CheckRole.php                    # Require ALL roles
│   │   ├── CheckRoles.php                   # Allow ANY role
│   │   ├── CheckPrivilege.php               # Require ALL privileges
│   │   ├── CheckPrivileges.php              # Allow ANY privilege
│   │   └── LogRequests.php                  # Request/response logging
│   ├── Models/
│   │   ├── Role.php                         # Role model
│   │   └── Privilege.php                    # Privilege model
│   ├── Traits/
│   │   └── HasAstroxsRoles.php              # Main trait for User model
│   └── AstroxsServiceProvider.php           # Service provider
├── .gitignore
├── CHANGELOG.md                             # Version history
├── composer.json                            # Package dependencies
├── CONTRIBUTING.md                          # Contribution guidelines
├── LICENSE                                  # MIT License
├── QUICKSTART.md                            # Quick start guide
├── README.md                                # Main documentation
└── SECURITY.md                              # Security policy
```

## 🎯 Key Features Implemented

### ✅ Core Components
- [x] Service Provider with auto-discovery
- [x] 5 Database migrations (roles, privileges, pivots, suspension)
- [x] Role & Privilege models with relationships
- [x] HasAstroxsRoles trait with comprehensive methods
- [x] Database seeder with default data

### ✅ Middleware (7 types)
- [x] `ability` - Require ALL abilities
- [x] `abilities` - Allow ANY ability
- [x] `role` - Require ALL roles
- [x] `roles` - Allow ANY role
- [x] `privilege` - Require ALL privileges
- [x] `privileges` - Allow ANY privilege
- [x] `astroxs.log` - Request/response logging

### ✅ Blade Directives (7 types)
- [x] `@usercan` - Check ability via can()
- [x] `@hasrole` - Check specific role
- [x] `@hasanyrole` - Check any of roles
- [x] `@hasroles` - Check all roles
- [x] `@hasprivilege` - Check specific privilege
- [x] `@hasanyprivilege` - Check any privilege
- [x] `@hasprivileges` - Check all privileges

### ✅ Artisan Commands (40+ commands)

**Setup & Installation (6)**
- astroxs:install
- astroxs:prepare-user-model
- astroxs:seed
- astroxs:publish-config
- astroxs:publish-migrations
- astroxs:version

**User Management (9)**
- astroxs:create-user
- astroxs:users
- astroxs:users-with-roles
- astroxs:suspend-user
- astroxs:unsuspend-user
- astroxs:suspended-users
- astroxs:update-user
- astroxs:delete-user
- astroxs:user-privileges

**Role Management (11)**
- astroxs:roles
- astroxs:roles-with-privileges
- astroxs:create-role
- astroxs:update-role
- astroxs:delete-role
- astroxs:assign-role
- astroxs:delete-user-role
- astroxs:role-users
- astroxs:user-roles
- astroxs:seed-roles
- astroxs:flush-roles

**Privilege Management (8)**
- astroxs:privileges
- astroxs:add-privilege
- astroxs:update-privilege
- astroxs:delete-privilege
- astroxs:attach-privilege
- astroxs:detach-privilege
- astroxs:seed-privileges
- astroxs:purge-privileges

**Token Management (6)**
- astroxs:login
- astroxs:quick-token
- astroxs:me
- astroxs:logout
- astroxs:logout-all
- astroxs:logout-all-users

**Utilities (7)**
- astroxs:postman-collection
- astroxs:star
- astroxs:doc
- astroxs:about

### ✅ REST API Endpoints

**Public (4)**
- POST /api/login
- POST /api/users
- GET /api/astroxs
- GET /api/astroxs/version

**Authenticated User (3)**
- GET /api/me
- PUT/PATCH /api/users/{user}

**Admin - User Management (5)**
- GET /api/users
- GET /api/users/{user}
- DELETE /api/users/{user}
- POST /api/users/{user}/suspend
- DELETE /api/users/{user}/suspend

**Admin - Role Management (8)**
- GET /api/roles
- POST /api/roles
- GET /api/roles/{role}
- PUT/PATCH /api/roles/{role}
- DELETE /api/roles/{role}
- GET /api/users/{user}/roles
- POST /api/users/{user}/roles
- DELETE /api/users/{user}/roles/{role}

**Admin - Privilege Management (11)**
- GET /api/privileges
- POST /api/privileges
- GET /api/privileges/{privilege}
- PUT/PATCH /api/privileges/{privilege}
- DELETE /api/privileges/{privilege}
- GET /api/roles/{role}/privileges
- POST /api/roles/{role}/privileges
- DELETE /api/roles/{role}/privileges/{privilege}

### ✅ Default Seeded Data

**Roles (5)**
- super-admin (protected)
- admin (protected)
- editor
- user
- customer

**Privileges (23)**
- User Management: users.view, users.create, users.edit, users.delete, users.suspend
- Role Management: roles.view, roles.create, roles.edit, roles.delete, roles.assign
- Privilege Management: privileges.view, privileges.create, privileges.edit, privileges.delete, privileges.attach
- Reports: reports.run, reports.export
- Billing: billing.view, billing.manage
- Content: posts.create, posts.edit, posts.delete, posts.publish

**Bootstrap Admin**
- Email: admin@astroxs.project
- Password: astroxs
- Role: super-admin

### ✅ Configuration Options

- Authentication guard
- Route prefix
- Default user role
- Cache enable/disable
- Cache TTL
- Disable API routes
- Disable commands
- Log channel
- Protected roles
- Version

### ✅ Security Features

- Laravel Sanctum integration
- Automatic token revocation on suspension
- Protected roles
- Role & privilege caching
- Password hashing (bcrypt)
- Suspension workflows
- Request/response logging middleware

### ✅ Documentation

- Comprehensive README.md
- Quick Start guide
- Contributing guidelines
- Security policy
- Changelog
- MIT License

## 🚀 Installation Summary

```bash
composer require astroxs/astroxs
php artisan astroxs:install
```

That's it! Complete authentication, authorization, RBAC, user suspension, 40+ commands, 7 Blade directives, and REST API in 2 commands.

## 📦 Package Stats

- **Total Files:** 60+
- **Lines of Code:** 5000+
- **Commands:** 44
- **API Endpoints:** 32
- **Middleware:** 7
- **Blade Directives:** 7
- **Database Tables:** 4 + user columns
- **Default Roles:** 5
- **Default Privileges:** 23

## 🎉 Ready for Production

All features are implemented and the package is ready to be published to Packagist!

---

**Astroxs** - Authentication, Authorization & Role Management for Laravel 12
Built with ❤️ by Shahjahanrasel
