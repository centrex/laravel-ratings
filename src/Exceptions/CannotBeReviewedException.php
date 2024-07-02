<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Exceptions;

use Exception;

final class CannotBeReviewedException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'Cannot be reviewed more than once');
    }
}
