# Changelog

## 2.0.0 - Unreleased

Complete rewrite for Laravel 12 and 13 (PHP 8.2+). See the upgrade guide in the README.

- In-memory lookup API (`find`, `findOrFail`, `exists`, `inCountry`, `search`, `options`, …) returning readonly `City` objects.
- `CityCode` validation rule.
- Package auto-discovery; `FLAIRUK\Cities` namespace.
- Optional publishable migration, Eloquent model, idempotent seeder, `cities:install` and `cities:seed` commands.
- `iso_3166_3` renamed to `code` (it is an IATA city code).
- Test suite and GitHub Actions CI.
