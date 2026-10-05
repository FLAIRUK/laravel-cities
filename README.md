<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="art/logo-dark.svg">
    <img src="art/logo-light.svg" alt="Laravel Cities" width="420">
  </picture>
</p>

<h2 align="center">
  <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2+"></a>&nbsp;
  <a href="https://laravel.com/docs/" target="_blank"><img src="https://img.shields.io/badge/Laravel-12%20%7C%2013-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12 or 13"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/laravel-cities/actions/workflows/tests.yml" target="_blank"><img src="https://img.shields.io/badge/Lint-%E2%9C%93-2EA043?style=flat&logo=githubactions&logoColor=white" alt="Lint"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/laravel-cities/actions/workflows/tests.yml" target="_blank"><img src="https://img.shields.io/badge/Tests-%E2%9C%93-2EA043?style=flat&logo=githubactions&logoColor=white" alt="Tests"></a>&nbsp;
  <a href="https://packagist.org/packages/flairuk/laravel-cities" target="_blank"><img src="https://img.shields.io/packagist/dt/flairuk/laravel-cities?style=flat&logo=packagist&logoColor=white&label=Downloads&color=F28D1A" alt="Downloads on Packagist"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/laravel-cities/blob/master/LICENSE" target="_blank"><img src="https://img.shields.io/github/license/FLAIRUK/laravel-cities?style=flat&label=License&color=3DA639" alt="MIT licence"></a>&nbsp;
  <a href="https://www.iata.org/en/publications/directories/code-search/" target="_blank"><img src="https://img.shields.io/badge/Data-IATA-0F766E?style=flat" alt="IATA"></a>&nbsp;
  <br>&nbsp;
</h2>

**Laravel Cities** — More than 9,000 IATA city codes (`LON`, `NYC`, `PAR`, …) for Laravel 12 and 13. A city code groups every airport that serves a city. For example, `LON` covers Heathrow, Gatwick, Stansted and others.

- **No database required.** Look cities up through a facade backed by an in-memory dataset.
- **Typed results.** Every lookup returns readonly `City` objects in Laravel collections keyed by code.
- **Validation rule.** `new CityCode` accepts known codes only.
- **Optional table.** Publish a migration and seed a `cities` table when other tables need to reference cities.

<p align="center">
  📦&nbsp;<a href="#-installation">Installation</a> ·
  🚀&nbsp;<a href="#-usage">Usage</a> ·
  💾&nbsp;<a href="#-database-table-optional">Database table</a> ·
  🔄&nbsp;<a href="#-upgrading-from-dev-master">Upgrading</a>
</p>

<br><br>

## 📦 Installation

```bash
composer require flairuk/laravel-cities
```

Laravel discovers the service provider and the `Cities` facade automatically.

<br><br>

## 🚀 Usage

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

<br><br>

## 💾 Database table (optional)

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

<br><br>

## 🔄 Upgrading from dev-master

Version 1.0 is a rewrite. Breaking changes:

| dev-master | 1.0 |
| --- | --- |
| Package `ijeffro/laravel-cities` | `flairuk/laravel-cities` |
| `ijeffro\Cities\…` namespace | `FLAIRUK\Cities\…` |
| Facade `ijeffro\Cities\CitiesFacade` | `FLAIRUK\Cities\Facades\Cities` (auto-discovered) |
| `Cities::getList($sort)` (array) | `Cities::all()->sortBy($sort)` (Collection of `City`) |
| `Cities::getOne($id)` | `Cities::findById($id)` or `Cities::find($code)` |
| `Cities::getListForSelect()` (keyed by id) | `Cities::options('id')` |
| `php artisan cities:migration` | `php artisan cities:install` / `cities:seed` |
| Config key `cities.table_name` | `cities.table` |
| Field / column `iso_3166_3` | **`code`**. It was always an IATA city code, not an ISO 3166 code |

Row `id`s are unchanged. If you have an existing table, rename the column before re-seeding:

```php
Schema::table('cities', fn (Blueprint $table) => $table->renameColumn('iso_3166_3', 'code'));
```

<br><br>

## 🧪 Testing

```bash
composer test
```

<br><br>

## 📄 License

MIT. See [LICENSE](LICENSE).
