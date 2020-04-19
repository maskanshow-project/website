<?php

namespace SmaaT\EstateBot;

use App;
use Illuminate\Support\ServiceProvider;
use SmaaT\EstateBot\EstateBot;

class EstateBotProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        define('DS', DIRECTORY_SEPARATOR);

        $this->loadMigrationsFrom(__DIR__ . DS . '..' . DS . 'database' . DS . 'migrations');
    }
}
