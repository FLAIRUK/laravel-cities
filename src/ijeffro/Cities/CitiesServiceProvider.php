<?php

namespace ijeffro\Cities;

use Illuminate\Support\ServiceProvider;

/**
 * CountryListServiceProvider
 *
 */

class CitiesServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
    * Bootstrap the application.
    *
    * @return void
    */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('cities.php')]);

        // Append the country settings
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'cities'
        );
        /*$this->app['config']['database.connections'] = array_merge(
            $this->app['config']['database.connections'],
            \Config::get('career.library.database.connections')
        );*/
    }

    /**
     * Register everything.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCities();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerCities()
    {
        $this->app->bind('cities', function($app)
        {
            return new Cities();
        });
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.cities.migration', function ($app) {
            return new MigrationCommand($app);
        });
        $this->commands('command.cities.migration');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('cities');
    }
}
