<?php

namespace FLAIRUK\Cities\Database;

use FLAIRUK\Cities\Cities;
use FLAIRUK\Cities\Data\City as CityData;
use FLAIRUK\Cities\Models\City;
use Illuminate\Database\Seeder;

/**
 * Upserts the city dataset into the cities table. Safe to run repeatedly.
 */
class CitiesSeeder extends Seeder
{
    public function run(Cities $cities): void
    {
        $cities->all()
            ->map(fn (CityData $city) => $city->toArray())
            ->chunk(500)
            ->each(fn ($chunk) => City::query()->upsert(
                $chunk->values()->all(),
                ['id'],
                ['code', 'name', 'country_code'],
            ));
    }
}
