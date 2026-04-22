<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Concerns;

use Centrex\LaravelRatings\Exceptions\CannotBeReviewedException;
use Centrex\LaravelRatings\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Throwable;

trait InterectsWithReview
{
    /** Get the rating for a Model. */
    public function reviews(): MorphMany
    {
        return $this->morphMany(related: Review::class, name: 'reviewable');
    }

    /**
     * Rate a Model.
     *
     * @return Model|false
     *
     * @throws \Centrex\Rating\Exceptions\CannotBeRatedException
     * @throws Throwable
     */
    public function review(string $comment): Model|bool
    {
        throw_if(condition: $this->alreadyReviewed(), exception: CannotBeReviewedException::class);

        $review = new Review();

        $review->{$this->userColumn()} = auth()->id();
        $review->review = $comment;

        return $this->reviews()->save(model: $review);
    }

    public function unreview(): bool
    {
        if (auth()->id() === null) {
            return false;
        }

        return $this->reviews()
            ->where(column: $this->userColumn(), operator: '=', value: auth()->id())
            ->delete();
    }

    /** A check to see if the User has already rated the Model. */
    public function alreadyReviewed(): bool
    {
        if (auth()->id() === null) {
            return false;
        }

        return $this->reviews()
            ->where($this->userColumn(), auth()->id())
            ->exists();
    }

    /** The amount of times a Model has been rated by Users'. */
    protected function reviewedByUsers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('reviews')
                ? $this->reviews
                    ->whereNotNull($this->userColumn())
                    ->pluck($this->userColumn())
                    ->filter()
                    ->unique()
                    ->count()
                : $this->reviews()
                    ->whereNotNull($this->userColumn())
                    ->distinct($this->userColumn())
                    ->count($this->userColumn()),
        );
    }

    private function userColumn(): string
    {
        return (string) config('ratings.users.primary_key', 'user_id');
    }
}
