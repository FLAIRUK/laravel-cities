<?php

namespace FLAIRUK\Cities\Tests;

use FLAIRUK\Cities\Cities as CitiesRepository;
use FLAIRUK\Cities\Data\City;
use FLAIRUK\Cities\Facades\Cities;
use FLAIRUK\Cities\Rules\CityCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ItemNotFoundException;
use PHPUnit\Framework\Attributes\Test;

class CitiesTest extends TestCase
{
    #[Test]
    public function it_resolves_a_singleton_through_the_facade_and_alias(): void
    {
        $this->assertSame(app(CitiesRepository::class), app('cities'));
        $this->assertInstanceOf(CitiesRepository::class, Cities::getFacadeRoot());
    }

    #[Test]
    public function the_dataset_is_well_formed(): void
    {
        $cities = Cities::all();

        $this->assertGreaterThan(9000, $cities->count());
        $this->assertContainsOnlyInstancesOf(City::class, $cities);
        $this->assertSame($cities->count(), $cities->pluck('id')->unique()->count(), 'ids must be unique');

        $cities->each(function (City $city, string $code) {
            $this->assertSame($code, $city->code);
            $this->assertMatchesRegularExpression('/^[A-Z]{3}$/', $city->code);
            $this->assertMatchesRegularExpression('/^[A-Z]{2}$/', $city->countryCode);
            $this->assertNotSame('', $city->name);
        });
    }

    #[Test]
    public function it_finds_cities_by_code_case_insensitively(): void
    {
        $city = Cities::find(' lon ');

        $this->assertSame('LON', $city->code);
        $this->assertSame('GB', $city->countryCode);
        $this->assertNull(Cities::find('ZZ9'));
        $this->assertTrue(Cities::exists('lon'));
        $this->assertFalse(Cities::exists('ZZ9'));
    }

    #[Test]
    public function find_or_fail_throws_for_unknown_codes(): void
    {
        $this->expectException(ItemNotFoundException::class);

        Cities::findOrFail('ZZ9');
    }

    #[Test]
    public function it_finds_by_id(): void
    {
        $first = Cities::all()->first();

        $this->assertSame($first, Cities::findById($first->id));
        $this->assertNull(Cities::findById(-1));
    }

    #[Test]
    public function it_filters_by_country(): void
    {
        $british = Cities::inCountry('gb');

        $this->assertTrue($british->has('LON'));
        $this->assertTrue($british->every(fn (City $a) => $a->countryCode === 'GB'));
    }

    #[Test]
    public function it_searches_by_name_and_ranks_exact_code_matches_first(): void
    {
        $this->assertTrue(Cities::search('london')->has('LON'));
        $this->assertSame('LON', Cities::search('lon')->first()->code);
        $this->assertCount(0, Cities::search(''));
    }

    #[Test]
    public function it_builds_select_options(): void
    {
        $options = Cities::options();

        $this->assertSame(Cities::all()->count(), $options->count());
        $this->assertSame(Cities::find('LON')->name, $options['LON']);
        $this->assertSame(Cities::find('LON')->name, Cities::options('id')[Cities::find('LON')->id]);
    }

    #[Test]
    public function data_objects_serialise_to_snake_case_arrays(): void
    {
        $this->assertSame(
            ['code', 'name', 'country_code'],
            array_keys(collect(Cities::find('LON')->toArray())->except('id')->all()),
        );
        $this->assertJson(json_encode(Cities::find('LON')));
    }

    #[Test]
    public function the_validation_rule_accepts_known_codes_only(): void
    {
        $this->assertTrue(Validator::make(['city' => 'lon'], ['city' => new CityCode])->passes());
        $this->assertFalse(Validator::make(['city' => 'ZZ9'], ['city' => new CityCode])->passes());
        $this->assertFalse(Validator::make(['city' => null], ['city' => new CityCode])->passes());
    }
}
