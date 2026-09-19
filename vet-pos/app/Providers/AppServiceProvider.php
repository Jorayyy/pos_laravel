<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerBladeDirectives();
        $this->registerGates();
    }

    private function registerBladeDirectives(): void
    {
        Blade::if('role', function (string $role): bool {
            return auth()->check() && auth()->user()->role?->slug === $role;
        });

        Blade::if('can', function (string $permission): bool {
            if (!auth()->check()) {
                return false;
            }

            $role = auth()->user()->role;

            return $role && is_array($role->permissions) && in_array($permission, $role->permissions);
        });
    }

    private function registerGates(): void
    {
        $roles = ['admin', 'manager', 'cashier', 'vet_staff'];

        foreach ($roles as $role) {
            Gate::define("role:{$role}", function (User $user) use ($role) {
                return $user->role?->slug === $role;
            });
        }

        Gate::before(function (User $user) {
            if ($user->role?->slug === 'admin') {
                return true;
            }

            return null;
        });
    }
}
