<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/categories', 'CategoryController@index')->name('category');
Route::get('/categories/{id}', 'CategoryController@detail')->name('category-detail');

Route::get('/details/{id}', 'DetailController@index')->name('detail');
Route::post('/details/{id}', 'DetailController@add')->name('detail-add');

Route::post('/checkout/callback', 'CheckoutController@callback')->name('midtrans-callback');

Route::get('/success', 'CartController@success')->name('success');

Route::get('/register/success', 'Auth\RegisterController@success')->name('register-success');

Route::group(['middleware' => ['auth']], function(){
    Route::post('/checkout', 'CheckoutController@process')->name('checkout');
    Route::get('/cart', 'CartController@index')->name('cart');
    Route::delete('/cart/{id}', 'CartController@delete')->name('cart-delete');

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/dashboard/products', 'DashboardProductController@index')->name('dashboard-products');
    Route::get('/dashboard/products/create', 'DashboardProductController@create')->name('dashboard-products-create');

    Route::post('/dashboard/products', 'DashboardProductController@store')->name('dashboard-products-store');
    Route::post('/dashboard/products/{id}', 'DashboardProductController@update')->name('dashboard-products-update');

    Route::post('/dashboard/products/gallery/upload', 'DashboardProductController@uploadGallery')->name('dashboard-products-gallery-upload');
    Route::get('/dashboard/products/gallery/delete/{id}', 'DashboardProductController@DeleteGallery')->name('dashboard-products-delete');
    Route::get('/dashboard/products/{id}', 'DashboardProductController@detail')->name('dashboard-product-details');

    Route::get('/dashboard/transactions', 'DashboardTransactionController@index')->name('dashboard-transactions');
    Route::get('/dashboard/transactions/{id}', 'DashboardTransactionController@details')->name('dashboard-transaction-details');
    Route::post('/dashboard/transactions/{id}', 'DashboardTransactionController@update')->name('dashboard-transactions-update');

    Route::get('/dashboard/settings', 'DashboardSettingController@store')->name('dashboard-settings');
    Route::get('/dashboard/account', 'DashboardSettingController@account')->name('dashboard-account');
    Route::post('/dashboard/account/{redirect}', 'DashboardSettingController@update')->name('dashboard-account-redirect');


});

Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth', 'admin'])
    ->group(function() {
        Route::get('/', 'DashboardController@index')->name('admin-dashboard');
        Route::resource('category', 'CategoryController');
        Route::resource('user', 'UserController');
        Route::resource('product', 'ProductController');
        Route::resource('product-gallery', 'ProductGalleryController');
    });

Auth::routes(); 


