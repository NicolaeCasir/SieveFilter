<?php

namespace Nicolae\Sieve;

use Illuminate\Support\ServiceProvider;

class SieveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../routes/web.php';
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sieve');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([__DIR__ . '/../publishable/assets' => public_path('assets')]);
    }
}
