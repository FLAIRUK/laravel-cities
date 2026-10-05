<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="art/logo-dark.svg">
    <img src="art/logo-light.svg" alt="Laravel Cities" width="420">
  </picture>
</p>

[![Tests](https://github.com/FLAIRUK/laravel-cities/actions/workflows/tests.yml/badge.svg)](https://github.com/FLAIRUK/laravel-cities/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/ijeffro/laravel-cities/v/stable)](https://packagist.org/packages/ijeffro/laravel-cities)
[![License](https://poser.pugx.org/ijeffro/laravel-cities/license)](https://packagist.org/packages/ijeffro/laravel-cities)

More than 9,000 IATA city codes (`LON`, `NYC`, `PAR`, …) for Laravel 12 and 13. A city code groups every airport that serves a city. For example, `LON` covers Heathrow, Gatwick, Stansted and others.

- **No database required.** Look cities up through a facade backed by an in-memory dataset.
- **Typed results.** Every lookup returns readonly `City` objects in Laravel collections keyed by code.
- **Validation rule.** `new CityCode` accepts known codes only.
- **Optional table.** Publish a migration and seed a `cities` table when other tables need to reference cities.

## Installation

```bash
composer require ijeffro/laravel-cities
```

Laravel discovers the service provider and the `Cities` facade automatically.

## Usage

```php
use FLAIRUK\Cities\Facades\Cities;

Cities::find('lon');             // City { id: 4241, code: "LON", name: "London", countryCode: "GB" }
Cities::findOrFail('LON');       // throws ItemNotFoundException for unknown codes
Cities::exists('NYC');           // true
Cities::findById(4241);

Cities::all();                   // Collection<string, City> keyed by code
Cities::inCountry('FR');         // cities in France
Cities::search('london');        // matches on name or exact code
Cities::codes();
```

### Select options

```php
Cities::options();               // ['LON' => 'London', ...] sorted by name
Cities::options('id');           // [4241 => 'London', ...]
```

### Validation

```php
use FLAIRUK\Cities\Rules\CityCode;

$request->validate(['city' => ['required', new CityCode]]);
```

## Database table (optional)

```bash
php artisan cities:install         # publish config + migration, then migrate and seed
php artisan cities:seed            # insert / update (safe to re-run)
php artisan cities:seed --prune    # also delete rows no longer in the dataset
```

You can also call the seeder from your own `DatabaseSeeder`:

```php
$this->call(\FLAIRUK\Cities\Database\CitiesSeeder::class);
```

Query the table through the bundled Eloquent model:

```php
use FLAIRUK\Cities\Models\City;

City::code('LON')->first();
City::inCountry('GB')->orderBy('name')->get();
```

The table name and connection come from `CITIES_TABLE` and `CITIES_DB_CONNECTION`, or from the published config.

## Upgrading from 1.x / dev-master

Version 2 is a rewrite. Breaking changes:

| 1.x | 2.x |
| --- | --- |
| `ijeffro\Cities\…` namespace | `FLAIRUK\Cities\…` |
| Facade `ijeffro\Cities\CitiesFacade` | `FLAIRUK\Cities\Facades\Cities` (auto-discovered) |
| `Cities::getList($sort)` (array) | `Cities::all()->sortBy($sort)` (Collection of `City`) |
| `Cities::getOne($id)` | `Cities::findById($id)` or `Cities::find($code)` |
| `Cities::getListForSelect()` | `Cities::options()` |
| `php artisan cities:migration` | `php artisan cities:install` / `cities:seed` |
| Config key `cities.table_name` | `cities.table` |
| Field / column `iso_3166_3` | **`code`**. It was always an IATA city code, not an ISO 3166 code |

Row `id`s are unchanged. If you have an existing table, rename the column before re-seeding:

```php
Schema::table('cities', fn (Blueprint $table) => $table->renameColumn('iso_3166_3', 'code'));
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
