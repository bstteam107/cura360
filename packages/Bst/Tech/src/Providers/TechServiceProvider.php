<?php

namespace Bst\Tech\Providers;
 
use Illuminate\Support\ServiceProvider;
 
/**
 * Tech service provider
 *
 * @author    Jane Doe <janedoe@gmail.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TechServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
   

    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'tech');
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
 
    }
}