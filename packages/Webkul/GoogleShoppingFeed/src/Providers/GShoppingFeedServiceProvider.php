<?php

namespace Webkul\GoogleShoppingFeed\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class GShoppingFeedServiceProvider extends ServiceProvider
{
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
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'googleFeed');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'googleFeed');

        $this->app->register(ModuleServiceProvider::class);

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/admin/assets'),
        ], 'public');

        // $this->publishes([
        //     __DIR__ . '/../Resources/views/admin/catalog/products/index.blade.php' => resource_path('views/vendor/admin/catalog/products/index.blade.php'),
        // ]);

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('googleFeed::admin.layouts.style');
        });

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}
