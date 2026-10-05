<?php

namespace FLAIRUK\Cities\Console;

use FLAIRUK\Cities\Cities;
use FLAIRUK\Cities\Database\CitiesSeeder;
use FLAIRUK\Cities\Models\City;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cities:seed')]
class SeedCommand extends Command
{
    protected $signature = 'cities:seed
                            {--prune : Delete rows that are no longer in the dataset}';

    protected $description = 'Insert or update the cities table from the bundled dataset';

    public function handle(Cities $cities): int
    {
        $this->laravel->call([$this->laravel->make(CitiesSeeder::class), 'run']);

        if ($this->option('prune')) {
            $pruned = City::query()->whereNotIn('id', $cities->all()->pluck('id'))->delete();
            $this->components->info("Pruned {$pruned} stale cities.");
        }

        $this->components->info("Seeded {$cities->all()->count()} cities.");

        return self::SUCCESS;
    }
}
