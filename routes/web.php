<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/main', function () {
    return view('maintenance_page');
});

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/welcome', function () {
    return view('welcome');
});


Route::controller(UserController::class)->group(function () {
    
    Route::get('/users','index')->name('users');
    Route::get('/add-user','add_user')->name('add-user');
    Route::post('/create-user','create_user')->name('create-user');
    Route::get('/edit-user/{id}','edit_user')->name('edit-user');
    Route::put('/users/{id}', 'updateUser')->name('update-user');
    Route::get('/delete-user/{id}','delete_user')->name('delete-user');
    Route::get('/delete-user/{id}','delete_user')->name('delete-user');
    
});

Route::controller(ProductController::class)->group(function () {
    
    Route::get('/category','category')->name('category');
    
});

Route::controller(StoreController::class)->group(function () {
    
    Route::get('/store-list','storeList')->name('store-list');
    Route::get('/add- store','addStore')->name('add- store');
    Route::post('/create-store','createStore')->name('create-store');
    
});

Route::controller(DeliveryPartnerController::class)->group(function () {
    
    Route::get('/delivery-partner-list','DeliveryPartnerList')->name('delivery-partner-list');
    
});

require __DIR__.'/auth.php';