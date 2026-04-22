<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Concerns;

use Centrex\LaravelRatings\Exceptions\{CannotBeRatedException, MaxRatingException};
use Centrex\LaravelRatings\Models\Rating;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Throwable;

trait InterectsWithRating
{
    /** Get the rating for a Model. */
    public function ratings(): MorphMany
    {
        return $this->morphMany(related: Rating::class, name: 'rateable');
    }

    /**
     * Rate a Model.
     *
     * @return Model|false
     *
     * @throws \Centrex\Rating\Exceptions\CannotBeRatedException
     * @throws Throwable
     */
    public function rate(int $score): Model|bool
    {
        throw_if($score < 1 || $score > $this->maxRating(), MaxRatingException::class);
        throw_if(condition: $this->alreadyRated(), exception: CannotBeRatedException::class);

        $rating = new Rating();

        $rating->{$this->userColumn()} = auth()->id();
        $rating->rating = $score;

        return $this->ratings()->save(model: $rating);
    }

    public function unrate(): bool
    {
        if (auth()->id() === null) {
            return false;
        }

        return $this->ratings()
            ->where(column: $this->userColumn(), operator: '=', value: auth()->id())
            ->delete();
    }

    /** A check to see if the User has already rated the Model. */
    public function alreadyRated(): bool
    {
        if (auth()->id() === null) {
            return false;
        }

        return $this->ratings()
            ->where($this->userColumn(), auth()->id())
            ->exists();
    }

    /**
     * Get the all-round percentage of a rated Model.
     *
     *
     * @throws Throwable
     */
    public function ratingPercent($maxRating = null): float|int
    {
        $maxRating ??= $this->maxRating();

        throw_if(condition: $maxRating > $this->maxRating(), exception: MaxRatingException::class);

        return ($this->rated_in_total * $maxRating) > 0
            ? $this->sum_rating / (($this->rated_in_total * $maxRating) / 100)
            : 0;
    }

    /** The amount of times a Model has been rated by Users'. */
    protected function ratedByUsers(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('ratings')
                ? $this->ratings
                    ->whereNotNull($this->userColumn())
                    ->pluck($this->userColumn())
                    ->filter()
                    ->unique()
                    ->count()
                : $this->ratings()
                    ->whereNotNull($this->userColumn())
                    ->distinct($this->userColumn())
                    ->count($this->userColumn()),
        );
    }

    /** The amount of times a Model has been rated in total. */
    protected function ratedInTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('ratings') ? $this->ratings->count() : $this->ratings()->count(),
        );
    }

    /** Get the average rating for a Model */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('ratings') ? (float) $this->ratings->avg('rating') : (float) $this->ratings()->avg(column: 'rating'),
        );
    }

    /** Get the rating sum for a Model */
    protected function sumRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('ratings') ? $this->ratings->sum('rating') : $this->ratings()->sum(column: 'rating'),
        );
    }

    /** Get the average rating for a Model rated by Users. */
    protected function averageRatingByUser(): Attribute
    {
        return Attribute::make(
            get: fn () => auth()->id() === null
                ? 0
                : (
                    $this->relationLoaded('ratings')
                        ? (float) $this->ratings->where($this->userColumn(), auth()->id())->avg('rating')
                        : (float) $this->ratings()->where($this->userColumn(), auth()->id())->avg(column: 'rating')
                ),
        );
    }

    /** Get the rating sum for a Model rated by Users. */
    protected function averageSumOfUser(): Attribute
    {
        return Attribute::make(
            get: fn () => auth()->id() === null
                ? 0
                : (
                    $this->relationLoaded('ratings')
                        ? $this->ratings->where($this->userColumn(), auth()->id())->sum('rating')
                        : $this->ratings()->where($this->userColumn(), auth()->id())->sum(column: 'rating')
                ),
        );
    }

    private function userColumn(): string
    {
        return (string) config('ratings.users.primary_key', 'user_id');
    }

    private function maxRating(): int
    {
        return (int) config('ratings.max_rating', 5);
    }
}
