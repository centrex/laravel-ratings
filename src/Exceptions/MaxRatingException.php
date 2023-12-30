<?php

declare(strict_types = 1);

namespace Centrex\Ratings\Exceptions;

use Exception;

final class MaxRatingException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'Maximum rating cannot be more than ' . config('rating.max_rating'));
    }
}
