# agents.md

## Agent Guidance — laravel-ratings

### Package Purpose
Adds a `Rateable` trait to any Eloquent model, allowing users (or any model) to submit numeric star ratings. Includes a Livewire star-rating component for the UI.

### Before Making Changes
- Read `src/Concerns/` — the `Rateable` trait and its public methods
- Read `src/Models/` — the `Rating` model (stores rater, rateable, score)
- Read `src/Livewire/` — the star-rating Livewire component
- Check `src/Exceptions/` — invalid rating values throw typed exceptions

### Common Tasks

**Changing the rating scale**
- The valid range (e.g., 1–5) is defined in config — don't hardcode it
- Validation in the Livewire component and any `setRating()` method must read from config
- Updating the scale requires a data migration if existing ratings are out of range

**Adding weighted averages**
- Add a `weightedAverage()` method to the `Rateable` trait
- Weight logic belongs in a separate method, not inlined in `averageRating()`
- Add tests with known inputs and expected weighted outputs

**Preventing duplicate ratings**
- One rater per rateable per rater model type (e.g., one User rating per Product)
- Use `updateOrCreate` in the rating submission logic
- Expose a `hasRated($rater)` method on the trait for UI conditionals

**Updating the Livewire component**
- The component lives in `src/Livewire/` with its view in `resources/views/livewire/`
- Keep component state minimal — emit an event and let the host app respond if needed
- Test via Livewire's `assertSet`, `assertSee`, `call` helpers

### Testing
```sh
composer test:unit        # pest
composer test:types       # phpstan
composer test:lint        # pint
```

Test average rating calculation:
```php
$product->rate($user1, 4);
$product->rate($user2, 2);
expect($product->averageRating())->toBe(3.0);
```

### Safe Operations
- Adding new methods to the `Rateable` trait
- Adding Livewire component props/features
- Adding nullable migration columns
- Adding tests

### Risky Operations — Confirm Before Doing
- Changing `rateable_type` / `rateable_id` / `rater_type` / `rater_id` column names
- Changing the rating value column type (int → float changes stored precision)
- Changing `averageRating()` return type

### Do Not
- Allow ratings outside the configured min/max range — validate strictly
- Store fractional ratings if the config specifies integer-only
- Skip `declare(strict_types=1)` in any new file
