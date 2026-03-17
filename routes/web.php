<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AdminController;

// ============ PUBLIC ROUTES ============
Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============ CLIENT ROUTES ============
Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PageController::class, 'clientDashboard'])->name('dashboard');
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'clientOrders'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/track', [OrderController::class, 'track'])->name('track');
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
    
    // Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('/transaction/{transaction}', [WalletController::class, 'showTransaction'])->name('transaction.show');
        Route::get('/topup', [WalletController::class, 'showTopUp'])->name('topup');
        Route::post('/topup', [WalletController::class, 'topUp'])->name('topup.process');
        Route::get('/statement', [WalletController::class, 'exportStatement'])->name('statement');
        
        // API Routes (for AJAX)
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/balance', [WalletController::class, 'getBalance'])->name('balance');
            Route::get('/transactions', [WalletController::class, 'getTransactions'])->name('transactions');
            Route::get('/transaction/{transaction}', [WalletController::class, 'getTransaction'])->name('transaction');
        });
    });
    
    // Locations
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::post('/', [LocationController::class, 'store'])->name('store');
        Route::put('/{location}', [LocationController::class, 'update'])->name('update');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
        Route::put('/{location}/default', [LocationController::class, 'setDefault'])->name('default');
    });
    
    // Utility
    Route::get('/calculate-price', [OrderController::class, 'calculatePrice'])->name('calculate-price');
});

// ============ DRIVER ROUTES ============
Route::prefix('driver')->name('driver.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'driverDashboard'])->name('dashboard');
    
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [DriverController::class, 'orders'])->name('index');
        Route::get('/{order}', [DriverController::class, 'show'])->name('show');
        Route::post('/{order}/accept', [DriverController::class, 'accept'])->name('accept');
        Route::post('/{order}/collected', [DriverController::class, 'markCollected'])->name('collected');
        Route::post('/{order}/delivered', [DriverController::class, 'markDelivered'])->name('delivered');
    });
    
    Route::post('/location/update', [DriverController::class, 'updateLocation'])->name('location.update');
    Route::get('/location/{driver}/get', [DriverController::class, 'getLocation'])->name('location.get');
    
    // Earnings routes
    Route::get('/earnings', [DriverController::class, 'earnings'])->name('earnings');
    Route::get('/earnings/export', [DriverController::class, 'exportEarnings'])->name('earnings.export'); // Add this line
    
    // Schedule route
    Route::get('/schedule', [DriverController::class, 'schedule'])->name('schedule');
    
    // Profile routes
    Route::get('/profile', [DriverController::class, 'profile'])->name('profile');
    Route::put('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
});
// ============ ADMIN ROUTES ============
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'orders'])->name('index');
        Route::get('/{order}', [AdminController::class, 'showOrder'])->name('show');
        Route::put('/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('status');
        Route::post('/{order}/assign-driver', [AdminController::class, 'assignDriver'])->name('assign-driver');
    });
    
    // Drivers Management
    Route::prefix('drivers')->name('drivers.')->group(function () {
        Route::get('/', [AdminController::class, 'drivers'])->name('index');
        Route::get('/{driver}', [AdminController::class, 'showDriver'])->name('show');
        Route::post('/', [AdminController::class, 'storeDriver'])->name('store');
        Route::put('/{driver}', [AdminController::class, 'updateDriver'])->name('update');
        Route::delete('/{driver}', [AdminController::class, 'deleteDriver'])->name('delete');
    });
    
    // Clients Management
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [AdminController::class, 'clients'])->name('index');
        Route::get('/{client}', [AdminController::class, 'showClient'])->name('show');
        Route::put('/{client}/status', [AdminController::class, 'updateClientStatus'])->name('status');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::get('/revenue', [AdminController::class, 'revenueReport'])->name('revenue');
        Route::get('/orders', [AdminController::class, 'ordersReport'])->name('orders');
        Route::get('/drivers', [AdminController::class, 'driversReport'])->name('drivers');
        Route::get('/export/{type}', [AdminController::class, 'exportReport'])->name('export');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::put('/general', [AdminController::class, 'updateGeneralSettings'])->name('general');
        Route::put('/pricing', [AdminController::class, 'updatePricing'])->name('pricing');
        Route::put('/payment', [AdminController::class, 'updatePaymentSettings'])->name('payment');
    });
    
    // Basket Management
    Route::resource('baskets', App\Http\Controllers\Admin\BasketController::class);
    
    // Special Items Management
    Route::resource('special-items', App\Http\Controllers\Admin\SpecialItemController::class);
});

Route::get('/earnings/export', [DriverController::class, 'exportEarnings'])->name('earnings.export');
Route::get('/profile', [DriverController::class, 'profile'])->name('profile');
Route::put('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');