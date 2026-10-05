<?php

namespace FLAIRUK\Cities\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the optional cities table (see `php artisan cities:install`).
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $country_code
 */
class City extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return config('cities.table', 'cities');
    }

    public function getConnectionName(): ?string
    {
        return $this->connection ?? config('cities.connection');
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeCode(Builder $query, string $code): void
    {
        $query->where('code', strtoupper($code));
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeInCountry(Builder $query, string $countryCode): void
    {
        $query->where('country_code', strtoupper($countryCode));
    }
}
