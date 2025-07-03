<?php

namespace Eugenefvdm\MultiTenancyPWA;

use Illuminate\Support\ServiceProvider;

class MultiTenancyPWAServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/services.php', 'services'
        );
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // $this->publishesMigrations([
        //     __DIR__.'/../database/migrations' => database_path('migrations'),
        // ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'multi-tenancy-pwa');
        
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
} 