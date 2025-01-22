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
    Route::post('Customer-Address-Add', 'CustomerAddressAdd');
    Route::post('Customer-Address-Edit', 'CustomerAddressEdit');
    Route::post('Customer-Address-Delete', 'CustomerAddressDelete');
    Route::post('Customer-Address-List', 'CustomerAddressList');
    Route::post('Customer-Password-Change', 'CustomerPasswordChange');
    Route::post('Customer-Profile-Edit', 'CustomerProfileEdit');
});
Route::controller(CPCAPI::class)->group(function () {
    Route::post('Product-List', 'ProductList');
    Route::get('Product-Categories', 'ProductCategories');
});
Route::controller(CATCCAPI::class)->group(function () {
    Route::post('Add-To-Cart-List', 'AddToCartList');
    Route::post('Add-To-Cart', 'AddToCart');
    Route::post('Add-To-Cart-List-Quantity-Update', 'AddToCartListItemQuantityUpdate');
    Route::post('Add-To-Cart-List-Item-Delete', 'AddToCartListItemDelete');
});