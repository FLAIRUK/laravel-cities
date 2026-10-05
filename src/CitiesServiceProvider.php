<?php

namespace FLAIRUK\Cities;

use Illuminate\Support\ServiceProvider;

class CitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cities.php', 'cities');

        $this->app->singleton(Cities::class);
        $this->app->alias(Cities::class, 'cities');
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/cities.php' => config_path('cities.php'),
        ], 'cities-config');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'cities-migrations');

        $this->commands([
            Console\InstallCommand::class,
            Console\SeedCommand::class,
        ]);
    }
}
