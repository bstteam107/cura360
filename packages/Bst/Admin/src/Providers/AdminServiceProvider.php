<?php

namespace Bst\Admin\Providers;
 
use Illuminate\Support\ServiceProvider;
 
/**
 * Admin service provider
 *
 * @author    Jane Doe <janedoe@gmail.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AdminServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'admin');
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