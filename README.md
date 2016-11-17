# Laravel Cities

[![Latest Stable Version](https://poser.pugx.org/ijeffro/laravel-cities/v/stable)](https://packagist.org/packages/ijeffro/laravel-cities)
[![Total Downloads](https://poser.pugx.org/ijeffro/laravel-cities/downloads)](https://packagist.org/packages/ijeffro/laravel-cities)
[![Latest Unstable Version](https://poser.pugx.org/ijeffro/laravel-cities/v/unstable)](https://packagist.org/packages/ijeffro/laravel-cities)
[![License](https://poser.pugx.org/ijeffro/laravel-cities/license)](https://packagist.org/packages/ijeffro/laravel-cities)

Laravel Cities is a bundle for Laravel, providing Iata Code ISO 3166_3 and country codes for all the cities.

**Please note that the dev-master version is for Laravel 5 only**

## Installation

Run `composer require ijeffro/laravel-cities dev-master` in your Laravel root directory to install the latest version.

Or add `ijeffro/laravel-cities` to `composer.json`.

    "ijeffro/laravel-cities": "dev-master"

Run `composer update` to pull down the latest version of City List.

Edit `app/config/app.php` and add the `provider` and `filter`

    'providers' => [
        ijeffro\Cities\CitiesServiceProvider::class,
    ]

Now add the alias.

    'aliases' => [
        'Cities' => ijeffro\Cities\CitiesFacade::class,
    ]


## Model

You can start by publishing the configuration. This is an optional step, it contains the table name and does not need to be altered. If the default name `cities` suits you, leave it. Otherwise run the following command

    $ php artisan vendor:publish

Next generate the migration file:

    $ php artisan cities:migration

It will generate the `<timestamp>_setup_cities_table.php` migration and the `CitiesSeeder.php` seeder. To make sure the data is seeded insert the following code in the `seeds/DatabaseSeeder.php`

    //Seed the cities
    $this->call('CitiesSeeder');
    $this->command->info('Seeded the cities!');

You may now run it with the artisan migrate command:

    $ php artisan migrate --seed

After running this command the filled cities table will be available
