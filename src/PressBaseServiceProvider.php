<?php

namespace RoniEstein\Press;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RoniEstein\Press\Facades\Press;

class PressBaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Run migrations only for testing
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\ProcessCommand::class,
        ]);
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    private function registerResources()
    {
        
        if($this->app->runningUnitTests()){
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->loadMigrationsFrom(__DIR__.'/../tests/database/migrations');
        }
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'press');

        $this->registerFacades();
        $this->registerRoutes();
        $this->registerFields();
    }
    
    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
            __DIR__.'/../config/press.php' => config_path('press.php'),
            __DIR__.'/Console/stubs/PressServiceProvider.stub' => app_path('Providers/PressServiceProvider.php'),
            ],
            'press');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Get the Press route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'prefix' => Press::uri(),
            'namespace' => 'RoniEstein\Press\Http\Controllers',
        ];
    }

    /**
     * Register any bindings to the app.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->singleton('Press', function ($app) {
            return new \RoniEstein\Press\Press();
        });
    }

    /**
     * Register any default fields to the app.
     *
     * @return void
     */
    private function registerFields()
    {
        Press::fields([
            Fields\Body::class,
            Fields\Date::class,
            Fields\Description::class,
            Fields\Extra::class,
            Fields\Title::class,
            Fields\PublishedAt::class,
            Fields\Tags::class,
            Fields\Author::class,
            Fields\HeaderImage::class,
            
        ]);
    }
}