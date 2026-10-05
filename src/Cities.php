<?php

namespace FLAIRUK\Cities;

use FLAIRUK\Cities\Data\City;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;

/**
 * In-memory lookup of IATA city codes.
 *
 * The dataset is loaded lazily on first use and kept for the lifetime of the
 * instance (bound as a singleton), so lookups never touch the database.
 */
class Cities
{
    /** @var Collection<string, City>|null */
    protected ?Collection $cities = null;

    public function __construct(
        protected string $path = __DIR__.'/../data/cities.php',
    ) {}

    /**
     * Every city, keyed by IATA code.
     *
     * @return Collection<string, City>
     */
    public function all(): Collection
    {
        return $this->cities ??= collect(require $this->path)
            ->mapWithKeys(fn (array $row) => [$row['code'] => City::fromArray($row)]);
    }

    /**
     * Find an city by its three-letter IATA city code, e.g. "LON".
     */
    public function find(string $code): ?City
    {
        return $this->all()->get(strtoupper(trim($code)));
    }

    /**
     * @throws ItemNotFoundException
     */
    public function findOrFail(string $code): City
    {
        return $this->find($code) ?? throw new ItemNotFoundException("Unknown city code [{$code}].");
    }

    public function findById(int $id): ?City
    {
        return $this->all()->firstWhere('id', $id);
    }

    public function exists(string $code): bool
    {
        return $this->all()->has(strtoupper(trim($code)));
    }

    /**
     * Cities in the given ISO 3166-1 alpha-2 country, e.g. "GB".
     *
     * @return Collection<string, City>
     */
    public function inCountry(string $countryCode): Collection
    {
        return $this->all()->where('countryCode', strtoupper($countryCode));
    }

    /**
     * Case-insensitive match against the code or name; an exact code match is ranked first.
     *
     * @return Collection<string, City>
     */
    public function search(string $term): Collection
    {
        $term = trim($term);

        if ($term === '') {
            return new Collection;
        }

        $exact = strtoupper($term);

        return $this->all()
            ->filter(fn (City $city) => $city->code === $exact || mb_stripos($city->name, $term) !== false)
            ->sortBy(fn (City $city) => $city->code === $exact ? 0 : 1);
    }

    /**
     * Key/label pairs for a <select>, sorted by label.
     *
     * @return Collection<int|string, string>
     */
    public function options(string $key = 'code', string $label = 'name'): Collection
    {
        return $this->all()->sortBy($label, SORT_NATURAL | SORT_FLAG_CASE)->pluck($label, $key);
    }

    /**
     * @return list<string>
     */
    public function codes(): array
    {
        return $this->all()->keys()->all();
    }
}
