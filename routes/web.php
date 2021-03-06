<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/admin',function(){
    return redirect('/admin/login');
});
Route::middleware(['auth', 'verified','authorized:user'])->group(function () {
    Route::get('/home', 'ProductController@index')->name('home');
    Route::any('/pick/{id}', 'ProductController@pick')->name('product.pick');
    Route::delete('/removepick/{id}', 'ProductController@removepick')->name('product.removepick');
});

Route::get('/admin/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin-login-view');
Route::post('/admin/login', 'Admin\Auth\LoginController@login')->name('admin-login');
Route::middleware(['auth', 'verified','authorized:admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', 'Admin\DashboardController@index')->name('dashboard');
    Route::resource('products', 'Admin\ProductController');
});