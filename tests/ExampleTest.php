<?php

declare(strict_types = 1);

use Centrex\LaravelRatings\Exceptions\CannotBeRatedException;
use Centrex\LaravelRatings\Tests\{TestPost, TestUser};

it('prevents duplicate ratings from the same authenticated user', function (): void {
    $user = TestUser::query()->create(['name' => 'Alice']);
    $post = TestPost::query()->create(['title' => 'Hello']);

    $this->be($user);

    expect($post->rate(4))->not->toBeFalse()
        ->and($post->alreadyRated())->toBeTrue();

    expect(fn () => $post->rate(5))->toThrow(CannotBeRatedException::class);
});

it('calculates aggregates correctly when ratings are eager loaded', function (): void {
    $first = TestUser::query()->create(['name' => 'Alice']);
    $second = TestUser::query()->create(['name' => 'Bob']);
    $post = TestPost::query()->create(['title' => 'Hello']);

    $this->be($first);
    $post->rate(4);

    $this->be($second);
    $post->rate(2);

    $post->load('ratings');

    expect($post->rated_in_total)->toBe(2)
        ->and($post->rated_by_users)->toBe(2)
        ->and($post->sum_rating)->toBe(6)
        ->and($post->average_rating)->toBe(3.0)
        ->and($post->ratingPercent())->toBe(60.0);
});
