<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DeliveryPartnerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\BillDeskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PriceListController;
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
//---------------------------------------------------START---------------------------------------------------
Route::get('/migrate-database', function () {
    try {
        // Run migrations
        Artisan::call('migrate', ['--force' => true]);

        // Return success message
        return 'Database migrated successfully!';
    } catch (\Exception $e) {
        // Return error message
        return 'Error migrating database: ' . $e->getMessage();
    }
});
Route::get('/migrate-and-seed', function () {
    Artisan::call('migrate:fresh --seed');
    return 'Migration and seeding completed.';
});
Route::get('cache-clear', function(){
    echo $exitcode = Artisan::call('cache:clear');
});
Route::get('/clear-optimize', function () {
    $exitCode = Artisan::call('optimize:clear');
    return 'Cache cleared successfully';
});
Route::get('view-clear', function(){
    echo $exitcode = Artisan::call('view:clear');
});
Route::get('route-clear', function(){
    echo $exitcode = Artisan::call('route:clear');
});
Route::get('config-clear', function(){
    echo $exitcode = Artisan::call('config:clear');
});
Route::get('clear-all', function(){
    $commands = [
        'cache:clear',
        'view:clear',
        'route:clear',
        'config:clear',
        // Add more commands if needed
    ];

    $exitCodes = [];

    foreach ($commands as $command) {
        $exitCodes[$command] = Artisan::call($command);
    }

    return response()->json($exitCodes);
});
Route::get('/runCmd', function(){
    echo $exitcode = Artisan::call('storage:link');
    //php artisan storage:link
});
Route::get('/seed-database', function () {
    Artisan::call('db:seed');
    return 'Database seeded successfully!';
});

Route::get('/single-migrate-and-seed', function () {
    // Run migrations
    // Artisan::call('migrate', ['--path' => 'database/migrations/2024_05_05_011156_create_expense_categorys_table.php']);

    // Run seeder for a specific table
    // Artisan::call('db:seed', ['--class' => 'expense_category_Seeder']);
    // Artisan::call('db:seed', ['--class' => 'routineSeeder']);

    return 'Migration and seeding completed.';
});

//---------------------------------------------------END---------------------------------------------------

Route::get('/dashboard', function () {
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

Route::post('/billdesk/order', [TestController::class, 'createOrder']);

Route::get('/welcome', function () {
    return view('dashboard');
});

Route::get('/payment', [BillDeskController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/initiate', [BillDeskController::class, 'initiatePayment'])->name('payment.initiate');
Route::post('/payment/callback', [BillDeskController::class, 'handleCallback'])->name('payment.callback');

Route::get('/daily-price-list', [PriceListController::class, 'DailyPriceList'])->name('daily-price-list');
Route::post('/upload-csv', [PriceListController::class, 'uploadCsv'])->name('upload.csv');

Route::post('/request-store',[StoreController::class, 'requestStore'])->name('request-store');
Route::get('/', function () {
    return view('website.index');
});
Route::get('/about', function () {
    return view('website.about');
});
Route::get('/blog', function () {
    return view('website.index');
});
Route::get('/blog-details', function () {
    return view('website.index');
});
Route::get('/testimonial', function () {
    return view('website.testimonial');
});
Route::get('/privacy-policy', function () {
    return view('website.privacy-policy');
});
Route::get('/price-guide', function () {
    return view('website.price-guide');
});
Route::get('/groceries-for-a-healthy-and-happy-home', function () {
    return view('website.groceries-for-a-healthy-and-happy-home');
});
Route::get('/franchise', function () {
    return view('website.franchise');
});
Route::get('/fish-meat-for-a-healthy-lisfestyle', function () {
    return view('website.fish-meat-for-a-healthy-lisfestyle');
});
Route::get('/contact', function () {
    return view('website.contact');
});
Route::get('/terms-conditation', function () {
    return view('website.terms-conditation');
});
Route::get('/refund-policy', function () {
    return view('website.refund-policy');
});

require __DIR__.'/auth.php';