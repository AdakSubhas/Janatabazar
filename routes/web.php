<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DeliveryPartnerController;
use App\Http\Controllers\ProductCategoryController;

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

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(UserController::class)->group(function () {
    
        Route::get('/users','index')->name('users');
        Route::get('/add-user','add_user')->name('add-user');
        Route::post('/create-user','create_user')->name('create-user');
        Route::get('/edit-user/{id}','edit_user')->name('edit-user');
        Route::put('/users/{id}', 'updateUser')->name('update-user');
        Route::get('/delete-user/{id}','delete_user')->name('delete-user');
        
    });
    
    Route::controller(ProductCategoryController::class)->group(function () {
        
        Route::get('/category','category')->name('category');
        
    });
    
    Route::controller(StoreController::class)->group(function () {
        
        Route::get('/store-list','storeList')->name('store-list');
        Route::get('/add- store','addStore')->name('add-store');
        Route::post('/create-store','createStore')->name('create-store');
        Route::get('/edit-store/{id}','edit_store')->name('edit-store');
        Route::put('/store/{id}', 'updatestore')->name('update-store');
        Route::get('/delete-store/{id}','delete_store')->name('delete-store');
        
    });
    
    Route::controller(DeliveryPartnerController::class)->group(function () {
        
        Route::get('/delivery-partner-list','DeliveryPartnerList')->name('delivery-partner-list');
        
    });

});


Route::get('/main', function () {
    return view('maintenance_page');
});



Route::get('/welcome', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';