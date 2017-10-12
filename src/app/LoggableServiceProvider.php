<?php

namespace Afrittella\LaravelLoggable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Afrittella\LaravelLoggable\Contracts\Logger as LoggerContract;

class LoggableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));

        $this->publishFiles();
    }

    public function register()
    {
        $config = $this->app->config['loggable'];

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__ . '/../config/loggable.php', 'loggable'
        );

        $this->app->bind(LoggerContract::class, Afrittella\LaravelLoggable\Logger::class);

        /*$this->app->singleton('logger', function ($app) {
            return new Logger();
        });*/
    }

    protected function publishFiles()
    {

        // publish config file
        $this->publishes([__DIR__ . '/../config/loggable.php' => config_path() . '/loggable.php'], 'config');

        // publish migrations
        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'migrations');
    }
}