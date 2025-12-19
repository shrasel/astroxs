<?php

namespace Shrasel\Astroxs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

class AstroxsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/astroxs.php', 'astroxs'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/astroxs.php' => config_path('astroxs.php'),
        ], 'astroxs-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'astroxs-migrations');

        // Register commands
        if ($this->app->runningInConsole() && !config('astroxs.disable_commands', false)) {
            $this->commands([
                Commands\InstallCommand::class,
                Commands\PrepareUserModelCommand::class,
                Commands\SeedCommand::class,
                Commands\PublishConfigCommand::class,
                Commands\PublishMigrationsCommand::class,
                Commands\VersionCommand::class,
                
                // User Management
                Commands\CreateUserCommand::class,
                Commands\UsersCommand::class,
                Commands\UsersWithRolesCommand::class,
                Commands\SuspendUserCommand::class,
                Commands\UnsuspendUserCommand::class,
                Commands\SuspendedUsersCommand::class,
                Commands\UpdateUserCommand::class,
                Commands\DeleteUserCommand::class,
                Commands\UserPrivilegesCommand::class,
                
                // Role Management
                Commands\RolesCommand::class,
                Commands\RolesWithPrivilegesCommand::class,
                Commands\CreateRoleCommand::class,
                Commands\UpdateRoleCommand::class,
                Commands\DeleteRoleCommand::class,
                Commands\AssignRoleCommand::class,
                Commands\DeleteUserRoleCommand::class,
                Commands\RoleUsersCommand::class,
                Commands\UserRolesCommand::class,
                Commands\SeedRolesCommand::class,
                Commands\FlushRolesCommand::class,
                
                // Privilege Management
                Commands\PrivilegesCommand::class,
                Commands\AddPrivilegeCommand::class,
                Commands\UpdatePrivilegeCommand::class,
                Commands\DeletePrivilegeCommand::class,
                Commands\AttachPrivilegeCommand::class,
                Commands\DetachPrivilegeCommand::class,
                Commands\SeedPrivilegesCommand::class,
                Commands\PurgePrivilegesCommand::class,
                
                // Token Management
                Commands\LoginCommand::class,
                Commands\QuickTokenCommand::class,
                Commands\MeCommand::class,
                Commands\LogoutCommand::class,
                Commands\LogoutAllCommand::class,
                Commands\LogoutAllUsersCommand::class,
                
                // Utilities
                Commands\PostmanCollectionCommand::class,
                Commands\StarCommand::class,
                Commands\DocCommand::class,
                Commands\AboutCommand::class,
            ]);
        }

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register middleware
        $this->registerMiddleware();

        // Register API routes
        if (!config('astroxs.disable_api', false)) {
            $this->registerApiRoutes();
        }

        // Register Gate for privilege checking
        $this->registerGate();
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @usercan directive
        Blade::if('usercan', function ($ability) {
            return auth()->check() && auth()->user()->can($ability);
        });

        // @hasrole directive
        Blade::if('hasrole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @hasanyrole directive
        Blade::if('hasanyrole', function (...$roles) {
            if (!auth()->check()) return false;
            $user = auth()->user();
            foreach ($roles as $role) {
                if ($user->hasRole($role)) return true;
            }
            return false;
        });

        // @hasroles directive
        Blade::if('hasroles', function (...$roles) {
            if (!auth()->check()) return false;
            $user = auth()->user();
            foreach ($roles as $role) {
                if (!$user->hasRole($role)) return false;
            }
            return true;
        });

        // @hasprivilege directive
        Blade::if('hasprivilege', function ($privilege) {
            return auth()->check() && auth()->user()->hasPrivilege($privilege);
        });

        // @hasanyprivilege directive
        Blade::if('hasanyprivilege', function (...$privileges) {
            if (!auth()->check()) return false;
            $user = auth()->user();
            foreach ($privileges as $privilege) {
                if ($user->hasPrivilege($privilege)) return true;
            }
            return false;
        });

        // @hasprivileges directive
        Blade::if('hasprivileges', function (...$privileges) {
            if (!auth()->check()) return false;
            $user = auth()->user();
            foreach ($privileges as $privilege) {
                if (!$user->hasPrivilege($privilege)) return false;
            }
            return true;
        });
    }

    /**
     * Register middleware.
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];
        
        $router->aliasMiddleware('ability', Middleware\CheckAbility::class);
        $router->aliasMiddleware('abilities', Middleware\CheckAbilities::class);
        $router->aliasMiddleware('role', Middleware\CheckRole::class);
        $router->aliasMiddleware('roles', Middleware\CheckRoles::class);
        $router->aliasMiddleware('privilege', Middleware\CheckPrivilege::class);
        $router->aliasMiddleware('privileges', Middleware\CheckPrivileges::class);
        $router->aliasMiddleware('astroxs.log', Middleware\LogRequests::class);
    }

    /**
     * Register API routes.
     */
    protected function registerApiRoutes(): void
    {
        Route::group([
            'prefix' => config('astroxs.route_prefix', 'api'),
            'middleware' => config('astroxs.route_middleware', ['api']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register Gate for privilege checking.
     */
    protected function registerGate(): void
    {
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasPrivilege') && $user->hasPrivilege($ability)) {
                return true;
            }
            if (method_exists($user, 'hasRole') && $user->hasRole($ability)) {
                return true;
            }
            return null;
        });
    }
}
