<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/create/charges', 'Webkul\Paytomorrow\Http\Controllers\PaytomorrowController@createCharge')->name('paytomorrow.make.payment');
    });
});