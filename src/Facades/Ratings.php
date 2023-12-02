<?php

declare(strict_types=1);

namespace Centrex\Ratings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Centrex\Ratings\Ratings
 */
class Ratings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Centrex\Ratings\Models\Rating::class;
    }
}
