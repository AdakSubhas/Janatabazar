<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DeliveryPartnerController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/main', function () {
    return view('maintenance_page');
});

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/welcome', function () {
    return view('welcome');
});


Route::controller(UserController::class)->group(function () {

    Route::get('/users','index')->name('users');
    
});

Route::controller(ProductController::class)->group(function () {

    Route::get('/category','category')->name('category');
    
});

Route::controller(StoreController::class)->group(function () {

    Route::get('/store-list','storeList')->name('store-list');
    
});

Route::controller(DeliveryPartnerController::class)->group(function () {

    Route::get('/delivery-partner-list','DeliveryPartnerList')->name('delivery-partner-list');
    
});

