<?php

namespace FLAIRUK\Cities\Tests;

use FLAIRUK\Cities\Database\CitiesSeeder;
use FLAIRUK\Cities\Facades\Cities;
use FLAIRUK\Cities\Models\City;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;

class DatabaseTest extends TestCase
{
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    #[Test]
    public function the_seed_command_fills_the_table_and_is_idempotent(): void
    {
        $this->artisan('cities:seed')->assertSuccessful();
        $this->artisan('cities:seed')->assertSuccessful();

        $this->assertSame(Cities::all()->count(), City::count());
        $this->assertSame('GB', City::code('lon')->first()->country_code);
        $this->assertTrue(City::inCountry('GB')->exists());
    }

    #[Test]
    public function the_seeder_can_be_called_from_an_application_seeder(): void
    {
        $this->seed(CitiesSeeder::class);

        $this->assertSame(Cities::all()->count(), City::count());
    }

    #[Test]
    public function prune_removes_rows_that_are_not_in_the_dataset(): void
    {
        City::create(['id' => 999999, 'code' => 'ZZ9', 'name' => 'Defunct City', 'country_code' => 'GB']);

        $this->artisan('cities:seed', ['--prune' => true])->assertSuccessful();

        $this->assertNull(City::find(999999));
        $this->assertSame(Cities::all()->count(), City::count());
    }

    #[Test]
    public function the_table_name_is_configurable(): void
    {
        config(['cities.table' => 'iata_cities']);
        (require __DIR__.'/../database/migrations/create_cities_table.php')->up();

        $this->artisan('cities:seed')->assertSuccessful();

        $this->assertTrue(Schema::hasTable('iata_cities'));
        $this->assertSame(Cities::all()->count(), City::count());
    }

    #[Test]
    public function install_publishes_the_config_and_a_timestamped_migration(): void
    {
        $migrations = database_path('migrations');
        File::delete(File::glob($migrations.'/*_create_cities_table.php'));
        File::delete(config_path('cities.php'));

        $this->artisan('cities:install')
            ->expectsConfirmation('Run the migration and seed the cities table now?', 'no')
            ->assertSuccessful();

        $this->assertFileExists(config_path('cities.php'));
        $this->assertCount(1, File::glob($migrations.'/*_create_cities_table.php'));

        File::delete(File::glob($migrations.'/*_create_cities_table.php'));
        File::delete(config_path('cities.php'));
    }
}
