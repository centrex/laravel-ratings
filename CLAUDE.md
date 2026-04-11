# CLAUDE.md

## Package Overview

`centrex/laravel-ratings` — Rateable trait and rating model for any Eloquent model, with optional Livewire UI.

Namespace: `Centrex\LaravelRatings\`  
Service Provider: `RatingsServiceProvider`

## Commands

Run from inside this directory (`cd laravel-ratings`):

```sh
composer install          # install dependencies
composer test             # full suite: rector dry-run, pint check, phpstan, pest
composer test:unit        # pest tests only
composer test:lint        # pint style check (read-only)
composer test:types       # phpstan static analysis
composer test:refacto     # rector refactor check (read-only)
composer lint             # apply pint formatting
composer refacto          # apply rector refactors
composer analyse          # phpstan (alias)
composer build            # prepare testbench workbench
composer start            # build + serve testbench dev server
```

Run a single test:
```sh
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Structure

```
src/
  RatingsServiceProvider.php
  Commands/
  Concerns/                     # Rateable trait
  Exceptions/
  Livewire/                     # Livewire rating components
  Models/                       # Rating model
config/config.php
database/migrations/
tests/
workbench/
```

## Key Concepts

- `Concerns/` trait: add `Rateable` to any model to allow users to rate it
- Ratings are stored polymorphically (rateable_type, rateable_id)
- Livewire component provides a star-rating UI widget
- Supports average rating calculation and rating counts

## Conventions

- PHP 8.2+, `declare(strict_types=1)` in all files
- Pest for tests, snake_case test names
- Pint with `laravel` preset
- Rector targeting PHP 8.3 with `CODE_QUALITY`, `DEAD_CODE`, `EARLY_RETURN`, `TYPE_DECLARATION`, `PRIVATIZATION` sets
- PHPStan at level `max` with Larastan
