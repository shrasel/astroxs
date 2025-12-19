# Changelog

All notable changes to `astroxs` will be documented in this file.

## [1.0.0] - 2024-12-18

### Added
- Initial release
- Complete authentication with Laravel Sanctum
- Role-based access control (RBAC)
- Fine-grained privilege system
- User suspension workflows
- 40+ CLI commands for management
- 7 Blade directives (@usercan, @hasrole, @hasanyrole, @hasroles, @hasprivilege, @hasanyprivilege, @hasprivileges)
- 7 middleware (ability, abilities, role, roles, privilege, privileges, astroxs.log)
- Complete REST API endpoints
- HasAstroxsRoles trait with comprehensive methods
- Default roles and privileges seeding
- Performance caching for roles and privileges
- Configurable via config/astroxs.php
- Protected roles feature
- Automatic token revocation on suspension
- Laravel Gate integration

### Features
- Works with Laravel 12 and PHP 8.2+
- Compatible with MySQL, PostgreSQL, SQLite, and SQL Server
- API and web application support
- Automatic User model preparation
- Comprehensive documentation
- Production-ready security features
