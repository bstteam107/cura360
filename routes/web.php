<?php
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\OnepageController;
//use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::post('admin/catalog/product/store',[ProductController::class, 'store'])->name('admin.catalog.product.store'); 
Route::post('/products/create', [ProductController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.product.edit',
        ])->name('admin.catalog.product.store');
Route::post('cura_url/catalog/categories/stores',[CategoryController::class, 'store'])->name('admin.catalog.categories.stores'); 
//Route::get('getcategorydetail',[CategoryController::class, 'getDetail'])->name('getcategorydetail'); 
Route::put('/products/edit/{id}', [ProductController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.index',
        ])->name('admin.catalog.product.update');
Route::get('cura_url/catalog/product/edit/{id}', [ProductController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.products.edit',
        ])->name('admin.catalog.product.edit');
Route::post('cura_url/catalog/product/deleteitem', [ProductController::class, 'deleteitem']);
Route::post('cura_url/catalog/product/deleterowold', [ProductController::class, 'deleterowold']);
Route::post('mycart/add/{id}',[CartController::class, 'add'])->name('mycart.add');
Route::get('checkout/cart/removes/{id}', [CartController::class, 'remove'])->name('shop.checkout.cart.removes');
Route::post('shop/checkout/cart/updates', [CartController::class, 'updateBeforeCheckout'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.updates');
Route::post('shop/checkout/saveorders', [OnepageController::class, 'saveOrder'])->name('shop.checkout.saveorders');
Route::get('cura_url/catalog/product/index', [ProductController::class, 'index'])->defaults('_config', [
    'view' => 'admin::catalog.products.index',
])->name('admin.catalog.product.index');
Route::get('cura_url/catalog/product/copy/{id}', [ProductController::class, 'copy'])->defaults('_config', [
            'view' => 'admin::catalog.products.edit',
        ])->name('admin.catalog.product.copy');
Route::post('customer/register/creates', [RegistrationController::class, 'create'])->defaults('_config', [
                'redirect' => 'customer.session.index',
            ])->name('customer.register.creates');
Route::post('customer/callback/create', [RegistrationController::class, 'callback'])->defaults('_config', [
                'redirect' => 'customer.session.index',
            ])->name('customer.callback.create');
Route::post('customer/mysubscribe',[RegistrationController::class, 'subscribe'])->name('customer/mysubscribe');
Route::get('sitemap', [SitemapController::class, 'index'])->name('sitemap');
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {

Route::get('checkout/paytomorrow/success', [OnepageController::class, 'paysucess'])->defaults('_config', [
            'view' => 'paytomorrow::success',
        ]);
Route::post('notify', [OnepageController::class, 'paynotify'])->defaults('_config', [
            'view' => 'paytomorrow::notify',
        ]);
		        
});
Route::get('mycheck', [OnepageController::class, 'notifymail'])->name('mycheck');