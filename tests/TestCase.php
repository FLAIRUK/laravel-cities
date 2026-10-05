<?php

namespace FLAIRUK\Cities\Tests;

use FLAIRUK\Cities\CitiesServiceProvider;
use FLAIRUK\Cities\Facades\Cities;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [CitiesServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Cities' => Cities::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
    }
}
