# Known Issues — laravel-ratings

_Last checked: 2026-08-02_

## Failing tests

No failing tests. `vendor/bin/pest -p` — 3 passed (11 assertions).

## Style / static-analysis debt

- `composer test` stops early: `rector --dry-run` (test:refacto) flags **3 files**:
  `src/Concerns/InterectsWithRating.php:109` (add explicit arrow-fn return types to
  `averageRating()`/`averageRatingByUser()` — `AddArrowFunctionReturnTypeRector`),
  `src/RatingsServiceProvider.php:55` (missing `#[\Override]` on `register()`), and
  `tests/TestCase.php:14` (missing `#[\Override]` on `setUp()`/`getPackageProviders()`/`getEnvironmentSetUp()`). Run `composer refacto` to apply.
- `vendor/bin/pint --test` — passes, no style issues.
- `vendor/bin/phpstan analyse` (level max) — **23 errors**. `phpstan-baseline.neon` exists but is empty (0 lines), so all 23 are unbaselined/live. Notable ones in `src/Livewire/Rating.php`: calls to undefined methods `alreadyRated()`, `rate()`, `ratingPercent()`, `unrate()` on `Illuminate\Database\Eloquent\Model` (lines 41, 46, 50, 57, 63) — these are trait methods added by `InterectsWithRating`/reviewable traits that PHPStan can't see through the generic `Model` type hint; plus missing property/param types (`$hoverValue`, `setRating($value)`, `getStarWidth($index)`) and a `float` returned where `int` is declared (line 81). `src/Models/Rating.php` and `src/Models/Review.php` both have unspecified generic types on `MorphTo`/`BelongsTo` relations and a `mixed`-to-`class-string` mismatch in `belongsTo()` calls. `database/migrations/2023_12_02_010000_create_ratings_table.php` has an anonymous migration class with no return type on `up()` and two `mixed`-to-`string` casts (lines 16, 24).

## TODO / FIXME markers

None found. Worth noting (not a TODO, but a naming inconsistency): the trait is
misspelled **`InterectsWithRating`** (and `InterectsWithReview`) instead of
`InteractsWithRating`/`InteractsWithReview` — consistently used across
`src/Concerns/InterectsWithRating.php`, `src/Concerns/InterectsWithReview.php`, and
`tests/TestCase.php`, so it isn't a broken reference, just a typo baked into the public
trait name.

## Open GitHub issues

Not checked — the `gh` CLI is not installed in this environment.
