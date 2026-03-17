<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\SpecialItemController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Locations
    Route::apiResource('locations', LocationController::class);
    
    // Baskets and Special Items (public read, admin write)
    Route::get('/baskets', [BasketController::class, 'index']);
    Route::get('/special-items', [SpecialItemController::class, 'index']);
    
    // Orders
    Route::get('/orders/calculate-price', [OrderController::class, 'calculatePrice']);
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{order}/assign-driver', [OrderController::class, 'assignDriver']);
    
    // Wallet
    Route::get('/wallet/balance', [WalletController::class, 'getBalance']);
    Route::get('/wallet/transactions', [WalletController::class, 'getTransactions']);
    Route::get('/wallet/transactions/{transaction}', [WalletController::class, 'getTransaction']);
    
    // Payments
    Route::post('/payments/{order}/initiate', [PaymentController::class, 'initiatePayment']);
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment']);
    Route::get('/payments/{payment}/status', [PaymentController::class, 'getPaymentStatus']);

    // Driver routes
    Route::middleware('role:driver')->prefix('driver')->group(function () {
        Route::get('/available-orders', [DriverController::class, 'getAvailableOrders']);
        Route::post('/orders/{order}/accept', [DriverController::class, 'acceptOrder']);
        Route::post('/location/update', [DriverController::class, 'updateLocation']);
        Route::get('/my-deliveries', [DriverController::class, 'getMyDeliveries']);
        Route::post('/orders/{order}/collected', [DriverController::class, 'markAsCollected']);
        Route::post('/orders/{order}/out-for-delivery', [DriverController::class, 'markAsOutForDelivery']);
        Route::post('/orders/{order}/delivered', [DriverController::class, 'markAsDelivered']);
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'getDashboardStats']);
        Route::get('/orders', [AdminController::class, 'getOrders']);
        Route::get('/drivers', [AdminController::class, 'getDrivers']);
        Route::get('/drivers/{driver}/performance', [AdminController::class, 'getDriverPerformance']); // Fixed: Changed -> to ::
        Route::get('/referrals/stats', [AdminController::class, 'getReferralStats']);
        
        // Management
        Route::get('/baskets', [AdminController::class, 'getBaskets']);
        Route::post('/baskets', [AdminController::class, 'storeBasket']);
        Route::put('/baskets/{basket}', [AdminController::class, 'updateBasket']);
        Route::delete('/baskets/{basket}', [AdminController::class, 'deleteBasket']);
        
        Route::get('/special-items', [AdminController::class, 'getSpecialItems']);
        Route::post('/special-items', [AdminController::class, 'storeSpecialItem']);
        Route::put('/special-items/{item}', [AdminController::class, 'updateSpecialItem']);
        Route::delete('/special-items/{item}', [AdminController::class, 'deleteSpecialItem']);
        
        // Reports
        Route::get('/orders/export', [AdminController::class, 'exportOrders']);
        Route::get('/orders/{order}/invoice', [AdminController::class, 'generateInvoice']);
    });
});





// Single page application entry point - all frontend routes go through Vue router
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

// Client stats
Route::middleware('auth:sanctum')->get('/client/stats', function() {
    $user = request()->user();
    $orders = $user->orders;
    
    return response()->json([
        'totalOrders' => $orders->count(),
        'completedOrders' => $orders->where('status', 'delivered')->count(),
        'totalSpent' => $orders->sum('total'),
        'referralEarnings' => $user->wallet->transactions()
            ->where('description', 'like', '%referral%')
            ->sum('amount')
    ]);
});

// Baskets list (public or authenticated)
Route::get('/baskets', function() {
    return App\Models\Basket::where('is_active', true)->get();
});

// Special items list
Route::get('/special-items', function() {
    return App\Models\SpecialItem::where('is_active', true)->get();
});

// Locations resource
Route::apiResource('locations', App\Http\Controllers\LocationController::class);
Route::put('/locations/{location}/default', [App\Http\Controllers\LocationController::class, 'setDefault']);