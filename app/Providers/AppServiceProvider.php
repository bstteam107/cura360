<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
		$this->app->bind('cartcustom', 'App\CartCustom');
		$this->app->concord->registerModel(
                \Webkul\Category\Contracts\Category::class, \App\Models\Category\Category::class,
				\Webkul\Category\Contracts\CategoryTranslation::class, \App\Models\Category\CategoryTranslation::class
            );
		Schema::defaultStringLength(191);
		
		
		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
