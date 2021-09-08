<?php

namespace AdminUI\AdminUIUserBase;

use AdminUI\AdminUIFramework\Provider;
use AdminUI\AdminUIUserBase\Commands\Republish;

class AdminUIUserBaseProvider extends Provider
{
    public $viewPrefix = 'auiuserbase';
    public $dir        = __DIR__;
    public $namespace  = __NAMESPACE__;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function _boot()
    {
        // // Load user_base views
        // $this->loadViewsFrom(__DIR__ . '/views', 'userbase');

        // // Load user_base routes
        // $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

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
    public function _register()
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
