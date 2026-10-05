<?php

namespace FLAIRUK\Cities\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection<string, \FLAIRUK\Cities\Data\City> all()
 * @method static \FLAIRUK\Cities\Data\City|null find(string $code)
 * @method static \FLAIRUK\Cities\Data\City findOrFail(string $code)
 * @method static \FLAIRUK\Cities\Data\City|null findById(int $id)
 * @method static bool exists(string $code)
 * @method static \Illuminate\Support\Collection<string, \FLAIRUK\Cities\Data\City> inCountry(string $countryCode)
 * @method static \Illuminate\Support\Collection<string, \FLAIRUK\Cities\Data\City> search(string $term)
 * @method static \Illuminate\Support\Collection<int|string, string> options(string $key = 'code', string $label = 'name')
 * @method static list<string> codes()
 *
 * @see \FLAIRUK\Cities\Cities
 */
class Cities extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \FLAIRUK\Cities\Cities::class;
    }
}
