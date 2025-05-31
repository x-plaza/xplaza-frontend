<?php

use Illuminate\Support\Facades\Route;

// This is a frontend-only Laravel project consuming APIs from a separate backend.
// All controllers should only act as API consumers, not as business logic providers.
// Removed Auth::routes() and backend-auth routes, as authentication is handled by the backend API.

Route::get('/', [App\Http\Controllers\InitController::class, 'index'])->name('home');

// Group all homepage-related routes for clarity and maintainability
Route::prefix('homepage')->name('homepage.')->group(function () {
    // Shop/cart/product routes
    Route::post('/shop-selection', [App\Http\Controllers\HomepageController::class, 'shopSelection'])->name('shopSelection');
    Route::post('/item-add-to-cart', [App\Http\Controllers\HomepageController::class, 'addToCart'])->name('addToCart');
    Route::post('/item-add-to-cart-by-id', [App\Http\Controllers\HomepageController::class, 'addToCartById'])->name('addToCartById');
    Route::post('/open-cart-list', [App\Http\Controllers\HomepageController::class, 'openCartList'])->name('openCartList');
    Route::post('/remove-item-from-cart-sitebar', [App\Http\Controllers\HomepageController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/get-trending-product-list/selected-shop', [App\Http\Controllers\HomepageController::class, 'trendingProductListForShop'])->name('trendingProductListForShop');
    Route::post('/get-trending-product-list/all-data', [App\Http\Controllers\HomepageController::class, 'trendingProductListAllData'])->name('trendingProductListAllData');
    Route::post('/get-product-list/by-sub-cat', [App\Http\Controllers\HomepageController::class, 'ProductListBySubCat'])->name('ProductListBySubCat');
    Route::get('/product-search-data', [App\Http\Controllers\HomepageController::class, 'searchProductList'])->name('searchProductList');
    Route::get('/item-details/{item_id}', [App\Http\Controllers\HomepageController::class, 'itemDetails'])->name('itemDetails');
    Route::post('/topber-item-counter', [App\Http\Controllers\HomepageController::class, 'itemCounter'])->name('itemCounter');
    Route::post('/item-quantity-add-from-checkout', [App\Http\Controllers\HomepageController::class, 'addQuantity'])->name('addQuantity');
    Route::post('/item-quantity-add-from-sitebar', [App\Http\Controllers\HomepageController::class, 'addQuantityFromSitebar'])->name('addQuantityFromSitebar');
    Route::post('/item-quantity-minus-from-sitebar', [App\Http\Controllers\HomepageController::class, 'minusQuantityFromSitebar'])->name('minusQuantityFromSitebar');
    Route::post('/item-quantity-remove-from-checkout', [App\Http\Controllers\HomepageController::class, 'removeQuantity'])->name('removeQuantity');
    Route::post('/item-remove-from-checkout', [App\Http\Controllers\HomepageController::class, 'removeItem'])->name('removeItem');
    Route::post('/get-location-data', [App\Http\Controllers\HomepageController::class, 'locationData'])->name('locationData');
    Route::post('/get-shop-data', [App\Http\Controllers\HomepageController::class, 'shopData'])->name('shopData');
    Route::post('/get-reg-otp', [App\Http\Controllers\HomepageController::class, 'getOtp'])->name('getOtp');
    // Product and category routes (now under /homepage prefix)
    Route::get('/all-trending-products', [App\Http\Controllers\HomepageController::class, 'allTrendingProducts'])->name('allTrendingProducts');
    Route::get('/product-by-category/sub/{cat_id}', [App\Http\Controllers\HomepageController::class, 'productByCategory'])->name('productByCategory');
    // Checkout routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/place-order', [App\Http\Controllers\CheckoutController::class, 'placeOrder'])->name('placeOrder');
    Route::post('/get-delivery-timeslot', [App\Http\Controllers\CheckoutController::class, 'deliveryTimeSlot'])->name('deliveryTimeSlot');
    Route::post('/validate-coupon', [App\Http\Controllers\CheckoutController::class, 'validateCoupon'])->name('validateCoupon');
    // Auth and registration handled by backend API, not locally.
    Route::post('/init-registration', [App\Http\Controllers\AuthController::class, 'registration'])->name('registration');
    Route::post('/init-login', [App\Http\Controllers\AuthController::class, 'initLogin'])->name('initLogin');
    Route::post('/init-forgot-pass', [App\Http\Controllers\ForgotPasswordController::class, 'setForgotPassword'])->name('setForgotPassword');
});

// API-based login/logout and forgot password
Route::post('/apiBasedLogin', [App\Http\Controllers\AuthController::class, 'loginAttempt'])->name('apiBasedLogin');
Route::get('/apiBasedLogOut', [App\Http\Controllers\AuthController::class, 'logOutAttempt'])->name('apiBasedLogOut');
Route::post('/forgot-password/get-otp', [App\Http\Controllers\ForgotPasswordController::class, 'getOtp'])->name('forgotPassword.getOtp');
Route::post('/forgot-password/set-new-password', [App\Http\Controllers\ForgotPasswordController::class, 'setNewPassword'])->name('forgotPassword.setNewPassword');

// UserDashboard routes (protected)
Route::middleware(['ACL'])->group(function () {
    Route::get('/my-dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/get-my-order-list', [App\Http\Controllers\UserDashboardController::class, 'myOrderList'])->name('dashboard.myOrderList');
    Route::post('/get-my-profile-data', [App\Http\Controllers\UserDashboardController::class, 'myProfile'])->name('dashboard.myProfile');
    Route::post('/update-profile-data', [App\Http\Controllers\UserDashboardController::class, 'updateProfile'])->name('dashboard.updateProfile');
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name'),
        'environment' => app()->environment(),
        'debug' => config('app.debug'),
    ]);
})->name('health');
