<?php

namespace AdminUI\AdminUIUserBase;

use Illuminate\Support\ServiceProvider;
use AdminUI\AdminUIUserBase\Commands\Republish;

class AdminUIUserBaseProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load user_base views
        $this->loadViewsFrom(__DIR__ . '/views', 'userbase');

        // Load user_base routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Republish::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publish();
    }

    public function publish()
    {
        // Publish the config public folder
        $this->publishes([
            __DIR__ . '/../Publish/Config/' => config_path('')
        ]);

        // Publish Resource
        $this->publishes([
            __DIR__ . '/../Publish/Resources/' => resource_path('/vendor/user_base')
        ]);

        // Publish the view
        $this->publishes([
            __DIR__ . '/../Publish/View/' => resource_path('/views/content/userbase')
        ]);
    }
}
