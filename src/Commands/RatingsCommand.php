<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Commands;

use Illuminate\Console\Command;

final class RatingsCommand extends Command
{
    public $signature = 'ratings';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
