<?php

namespace App\Providers;

use App\Interfaces\Reposities\UserReposityInterface;
use App\Interfaces\Services\UserServiceInterface;
use App\Reposities\UserReposity;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvicer extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserReposityInterface::class,UserReposity::class);
        $this->app->bind(UserServiceInterface::class,UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
