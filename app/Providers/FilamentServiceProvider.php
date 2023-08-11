<?php

namespace App\Providers;

use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(
            function () {
                // dd(Auth::user());
                if (Auth::user()?->is_admin === 1 && Auth::user()->hasAnyRole(['super-admin', 'admin', 'moderator'])) {
                    Filament::registerUserMenuItems([
                        MenuItem::make()
                            ->label('Manage Users')
                            ->Url(UserResource::getUrl())
                            ->icon('heroicon-o-user'),
                        MenuItem::make()
                            ->label('Manage Roles')
                            ->Url(RoleResource::getUrl())
                            ->icon('heroicon-o-users'),
                        MenuItem::make()
                            ->label('Manage Permissions')
                            ->Url(PermissionResource::getUrl())
                            ->icon('heroicon-o-key'),
                    ]);
                }
            }
        );
    }
}
