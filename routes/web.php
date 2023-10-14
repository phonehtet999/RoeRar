<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(DashboardController::class)->group(function() {
    Route::get('/home', 'home')->name('home')->middleware('auth');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/', 'login');
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::resource('users', UserController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::post('products/add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
    Route::post('products/remove-from-cart', [ProductController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::resource('products', ProductController::class);
    Route::post('purchases/create-second', [PurchaseController::class , 'createSecond'])->name('purchases.create-second');
    Route::resource('purchases', PurchaseController::class);
    Route::resource('staffs', StaffController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('carts', CartController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('deliveries', DeliveryController::class);
    Route::resource('payments', PaymentController::class);
});
