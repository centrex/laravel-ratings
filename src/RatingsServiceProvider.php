<?php

declare(strict_types = 1);

namespace Centrex\LaravelRatings;

use Centrex\LaravelRatings\Livewire\Rating;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class RatingsServiceProvider extends ServiceProvider
{
    /** Bootstrap the application services. */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ratings');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ratings');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('ratings.php'),
            ], 'ratings-config');

            // Publishing the migrations.
            /*$this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'ratings-migrations');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/ratings'),
            ], 'ratings-views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/ratings'),
            ], 'ratings-assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/ratings'),
            ], 'ratings-lang');*/

            // Registering package commands.
            // $this->commands([]);

            Livewire::component('rating', Rating::class);
        }
    }

    /** Register the application services. */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ratings');
    }
}
