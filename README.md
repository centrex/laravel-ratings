# Add rating to any model in laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/ratings.svg?style=flat-square)](https://packagist.org/packages/centrex/ratings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/ratings/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/ratings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/ratings/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/ratings/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/ratings?style=flat-square)](https://packagist.org/packages/centrex/ratings)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Contents

- [Installation](#installation)
- [Usage Examples](#usage)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require centrex/ratings
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="ratings-config"
```

This is the contents of the published config file:

```php
return [
    'users' => [
        'table' => 'users',
        'primary_key' => 'user_id',
    ],

    'max_rating' => 5,
    
    'undo_rating' => true,
];
```
## Usage

Add a `InterectsWithRating` trait to the Model you want to be ratable.

```php
use Centrex\Ratings\Concerns\InterectsWithRating;

class Product extends Model
{
    use InterectsWithRating;
    
    // ...
}
```

Now you can rate any Model.

**Rate the Model**

```php
$product = Product::find(1);
```

```php
$product->rate(4);
```
or
```php
$product->rate(score: 2);
```

**View Models' ratings**

```php
$product->ratings;
```

You can get an overall percentage of the amount of Users' who have rated a Model:

Imagine you want a five-star rating system, and you have a Model that has been rated a `3` and a `5` by two Users'

```php
$product->ratingPercent(maxLength: 5);
```

This will equate to 80%. A float is returned. Changing the `maxLength` will recalculate the percentage.

You could then use this percentage for the `score` attribute of the component.

> **Note**
> 
> By default, the `maxLength` is determined by a config option. You can override this by passing a value to the method.

**Unrating Models**

By default, you can unrate a Model. If you don't want Users' to unrate Models, set the `undo_rating` config option to true.

To unrate a Model, you can use the `unrate` method:

```php
$product->unrate();
```

The package comes with a bunch of Attributes that you can use. _The results of these are based off a single Model been rated by two Users' with a `3` and ` 5` rating._

```php
$product->averageRating; // "4.0000"
$product->averageRatingByUser; // "5.0000"
$product->averageSumOfUser; // 5
$product->ratedByUsers; // 2
$product->ratedInTotal; // 2
$product->sumRating; // "8" 
```

### Livewire Component

To see the ratings in action, you can use the Livewire component. This allows you to show the ratings on the front-end statically and let the User's rate the Model by clicking on the stars.

> **Warning**
> 
> You must have both Tailwind CSS and Font Awesome installed, though Font Awesome can be replaced with your own preferred icon set

**Use the component**

```html
<livewire:rating size="text-7xl" score="55" :model="$product" />
```

The component has customisable attributes, including:

```php
public string $iconBgColor = 'text-yellow-300';
public string $iconFgColor = 'text-yellow-400';
public float $score = 0;
public string $size = 'text-base';
public bool $static = false;
```

If you have the config for unrating a Model set to `true`, an icon shows that allows you to unrate the Model. 

## Testing

🧹 Keep a modern codebase with **Pint**:
```bash
composer lint
```

✅ Run refactors using **Rector**
```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:
```bash
composer test:types
```

✅ Run unit tests using **PEST**
```bash
composer test:unit
```

🚀 Run the entire test suite:
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [centrex](https://github.com/centrex)
- [All Contributors](../../contributors)
- [cjmellor/rating](https://github.com/cjmellor/rating)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
