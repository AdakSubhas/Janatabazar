<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\CustomerAPI\LoginControllerAPI as CLCAPI;
use App\Http\Controllers\ApiController\CustomerAPI\ProductControllerAPI as CPCAPI;
use App\Http\Controllers\ApiController\CustomerAPI\AddToCartControllerAPI as CATCCAPI;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CLCAPI::class)->group(function () {
    Route::post('Customer-Registration', 'CustomerRegistration');
    Route::post('Customer-Login', 'CustomerLogin');
});
Route::controller(CPCAPI::class)->group(function () {
    Route::get('Product-List', 'ProductList');
    Route::get('Product-Categories', 'ProductCategories');
});
Route::controller(CATCCAPI::class)->group(function () {
    Route::post('Add-To-Cart-List', 'AddToCartList');
});