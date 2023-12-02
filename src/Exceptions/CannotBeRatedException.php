<?php

namespace Centrex\Ratings\Exceptions;

use Exception;

class CannotBeRatedException extends Exception
{
    public function __construct()
    {
        parent::__construct(message: 'Cannot be rated more than once');
    }
}
