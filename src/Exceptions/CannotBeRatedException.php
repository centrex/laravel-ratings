<?php

declare(strict_types=1);

namespace Centrex\Ratings\Exceptions;

use Exception;

final class CannotBeRatedException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'Cannot be rated more than once');
    }
}
