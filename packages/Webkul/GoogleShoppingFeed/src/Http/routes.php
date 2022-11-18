<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix(config('app.admin_url'))->group(function () {

        // Map category routes
        Route::prefix('map/attribute')->group(function () {
            Route::get('/', 'Webkul\GoogleShoppingFeed\Http\Controllers\AttributeController@index')->defaults('_config', [
                'view' => 'googleFeed::attribute.index',
            ])->name('googleFeed.attribute.index');

            Route::post('/store', 'Webkul\GoogleShoppingFeed\Http\Controllers\AttributeController@store')->defaults('_config', [
                'redirect' => 'googleFeed.attribute.index',
            ])->name('googleFeed.attribute.index.store');

            Route::post('/update/{id}', 'Webkul\GoogleShoppingFeed\Http\Controllers\AttributeController@update')->defaults('_config', [
                'redirect' => 'googleFeed.attribute.index',
            ])->name('googleFeed.attribute.index.update');

            Route::get('/refresh/{id}', 'Webkul\GoogleShoppingFeed\Http\Controllers\AttributeController@destroy')->defaults('_config', [
                'redirect' => 'googleFeed.attribute.index',
            ])->name('googleFeed.attribute.index.refresh');

        });

        // Map category routes
        Route::prefix('map/category')->group(function () {
            Route::get('/', 'Webkul\GoogleShoppingFeed\Http\Controllers\CategoryController@index')->defaults('_config', [
                'view' => 'googleFeed::category.index',
            ])->name('googleFeed.category.index');

            Route::get('/create', 'Webkul\GoogleShoppingFeed\Http\Controllers\CategoryController@create')->defaults('_config', [
                'view' => 'googleFeed::category.create',
            ])->name('googleFeed.category.map.create');

            Route::post('/create', 'Webkul\GoogleShoppingFeed\Http\Controllers\CategoryController@store')->defaults('_config', [
                'redirect' => 'googleFeed.category.index',
            ])->name('googleFeed.category.map.store');

            Route::post('map-category/masssdelete', 'Webkul\GoogleShoppingFeed\Http\Controllers\CategoryController@massDestroy')->name('googleFeed.category.mass-delete');

        });

          // uploaded product routes
        Route::prefix('map/product')->group(function () {
            Route::get('/', 'Webkul\GoogleShoppingFeed\Http\Controllers\ProductController@exported')->defaults('_config', [
                'view' => 'googleFeed::product.index',
            ])->name('googleFeed.product.index');
        });

        Route::prefix('auth/google-shopping-feed')->group(function () {
            Route::get('/', 'Webkul\GoogleShoppingFeed\Http\Controllers\AccountController@index')
            ->defaults('_config', [
                'view' => 'googleFeed::account.index',
            ])->name('googleFeed.account.auth');

            Route::post('/authenticate', 'Webkul\GoogleShoppingFeed\Http\Controllers\AccountController@store')
            ->defaults('_config', [
                'redirect' => 'googleFeed.account.auth',
            ])->name('googleFeed.account.authenticate');

            Route::get('/redirect', 'Webkul\GoogleShoppingFeed\Http\Controllers\AccountController@redirect')
           ->name('googleFeed.account.auth.redirect');

           Route::get('/refresh/token', 'Webkul\GoogleShoppingFeed\Http\Controllers\AccountController@refresh')
           ->name('googleFeed.account.authenticate.refresh');
        });


        Route::get('/export/to/google/shop/index', 'Webkul\GoogleShoppingFeed\Http\Controllers\ProductController@index')->defaults('_config', [
            'view' => 'googleFeed::product.export',
        ])
        ->name('googleFeed.products.export.index');

        Route::get('/export/to/google/shop', 'Webkul\GoogleShoppingFeed\Http\Controllers\ProductController@export')
        ->name('googleFeed.products.export');

    });
});