# Add ratings and reviews to any Eloquent model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-ratings.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-ratings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-ratings/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-ratings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-ratings/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-ratings/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-ratings?style=flat-square)](https://packagist.org/packages/centrex/laravel-ratings)

Polymorphic rating and review system for any Eloquent model. Each authenticated user can rate or review a model once. Provides average, sum, and percentage accessors out of the box.

## Installation

```bash
composer require centrex/laravel-ratings
php artisan vendor:publish --tag="laravel-ratings-migrations"
php artisan migrate
```

## Usage

### 1. Add the traits to your model

```php
use Centrex\LaravelRatings\Concerns\InterectsWithRating;
use Centrex\LaravelRatings\Concerns\InterectsWithReview;

class Product extends Model
{
    use InterectsWithRating;
    use InterectsWithReview;
}
```

### 2. Ratings

```php
// Rate (throws CannotBeRatedException if already rated)
$product->rate(5);

// Remove rating
$product->unrate();

// Check if current user already rated
$product->alreadyRated();  // bool

// Aggregates (computed via Eloquent accessors)
$product->average_rating;          // avg score
$product->sum_rating;              // total score
$product->rated_in_total;          // number of ratings
$product->rated_by_users;          // number of unique users who rated
$product->average_rating_by_user;  // current user's average rating
$product->average_sum_of_user;     // current user's total score

// Percentage out of max (default max from config)
$product->ratingPercent();          // out of config max
$product->ratingPercent(10);        // out of 10
```

### 3. Reviews

```php
// Review (throws CannotBeReviewedException if already reviewed)
$product->review('Excellent build quality, highly recommend!');

// Remove review
$product->unreview();

// Check if current user already reviewed
$product->alreadyReviewed();  // bool

// Get all reviews
$product->reviews;

// Count unique reviewers
$product->reviewed_by_users;
```

### 4. Config

```bash
php artisan vendor:publish --tag="laravel-ratings-config"
```

```php
// config/rating.php
'max_rating' => 5,
'users' => [
    'primary_key' => 'user_id',
],
```

## Testing

```bash
composer test        # full suite
composer test:unit   # pest only
composer test:types  # phpstan
composer lint        # pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [centrex](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
