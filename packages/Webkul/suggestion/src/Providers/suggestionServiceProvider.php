<?php

namespace Webkul\suggestion\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;


class suggestionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Http/shop-routes.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'suggestion');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'suggestion');

        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('vendor/webkul/suggestion/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/layouts/header/index.blade.php' => resource_path('themes/velocity/views/layouts/header/index.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/layouts/particals/search-bar.blade.php' => resource_path('themes/velocity/views/layouts/particals/search-bar.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/header/index.blade.php' => resource_path('themes/default/views/layouts/header/index.blade.php'),
        ]);

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}