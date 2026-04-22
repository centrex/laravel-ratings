<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

final class Review extends Model
{
    protected $fillable = ['review', 'user_id'];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config(key: 'auth.providers.users.model'));
    }

    public function users(): BelongsTo
    {
        return $this->user();
    }
}
