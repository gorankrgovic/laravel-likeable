<?php
/*
 * This file is part of Laravel Likeable.
 *
 * (c) Goran Krgovic <gorankrgovic1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Gox\Laravel\Likeable\Providers;

use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Contracts\Likeable\LikeCounter\Models\LikeCounter as LikeCounterContract;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;
use Gox\Laravel\Likeable\Console\Commands\RecountCommand;
use Gox\Laravel\Likeable\Like\Models\Like;
use Gox\Laravel\Likeable\Like\Observers\LikeObserver;
use Gox\Laravel\Likeable\Likeable\Services\LikeableService;
use Gox\Laravel\Likeable\LikeCounter\Models\LikeCounter;
use Illuminate\Support\ServiceProvider;

class LikeableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConsoleCommands();
        $this->registerObservers();
        $this->registerPublishes();
        $this->registerMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContracts();
    }

    /**
     * Register models observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        $this->app->make(LikeContract::class)->observe(LikeObserver::class);
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerConsoleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RecountCommand::class,
            ]);
        }
    }

    /**
     * Register classes in the container.
     *
     * @return void
     */
    protected function registerContracts()
    {
        $this->app->bind(LikeContract::class, Like::class);
        $this->app->bind(LikeCounterContract::class, LikeCounter::class);
        $this->app->singleton(LikeableServiceContract::class, LikeableService::class);
    }

    /**
     * Setup the resource publishing groups
     *
     * @return void
     */
    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the Subscribe migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}