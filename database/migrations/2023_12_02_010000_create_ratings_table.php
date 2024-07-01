<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->morphs('rateable');
            $table->foreignId((string) config(key: 'ratings.users.primary_key', default: 'user_id'))->nullable()->constrained()->nullOnDelete();
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable');
            $table->foreignId((string) config(key: 'ratings.users.primary_key', default: 'user_id'))->nullable()->constrained()->nullOnDelete();
            $table->text('review');
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('reviews');
    }
};
