<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Concerns;

use Centrex\LaravelRatings\Exceptions\CannotBeReviewedException;
use Centrex\LaravelRatings\Models\Review;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\{Builder, Model};
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
        // a User cannot rate the same Model twice...
        throw_if(condition: $this->alreadyReviewed(), exception: CannotBeReviewedException::class);

        $review = new Review();

        $review->user_id = auth()->id();
        $review->review = $comment;

        return $this->reviews()->save(model: $review);
    }

    public function unreview(): bool
    {
        return $this->reviews()
            ->where(column: 'user_id', operator: '=', value: auth()->id())
            ->delete();
    }

    /** A check to see if the User has already rated the Model. */
    public function alreadyReviewed(): bool
    {
        return $this->reviews()->whereHasMorph(
            relation: 'reviewable',
            types: '*',
            callback: fn (Builder $query): Builder => $query->where(column: 'user_id', operator: '=', value: auth()->id()),
        )->exists();
    }

    /** The amount of times a Model has been rated by Users'. */
    protected function reviewedByUsers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews()
                ->whereNotNull(config(key: 'rating.users.primary_key', default: 'user_id'))
                ->groupBy(config(key: 'rating.users.primary_key', default: 'user_id'))
                ->pluck(config(key: 'rating.users.primary_key', default: 'user_id'))
                ->count(),
        );
    }
}
