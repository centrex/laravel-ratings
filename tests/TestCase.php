<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings\Tests;

use Centrex\LaravelRatings\Concerns\InterectsWithRating;
use Centrex\LaravelRatings\RatingsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Centrex\\LaravelRatings\\Database\\Factories\\' . class_basename($modelName) . 'Factory',
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            RatingsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        config()->set('auth.providers.users.model', TestUser::class);
    }
}

class TestUser extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}

class TestPost extends Model
{
    use InterectsWithRating;

    protected $table = 'posts';

    protected $guarded = [];
}
