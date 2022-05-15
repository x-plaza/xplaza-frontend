<?php

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


Route::get('/', function () {
    return redirect('/');
});

Auth::routes();

Route::get('/', 'homeController@index')->name('home');
Route::post('/website/shop-selection', 'websiteController@shopSelection');
Route::post('/website/item-add-to-cart', 'websiteController@addToCart');
Route::post('/website/item-add-to-cart-by-id', 'websiteController@addToCartById');
Route::post('/website/open-cart-list', 'websiteController@openCartList');
Route::post('/website/remove-item-from-cart-sitebar', 'websiteController@removeFromCart');
Route::post('/website/get-trending-product-list/selected-shop', 'websiteController@trendingProductListForShop');
Route::post('/website/get-trending-product-list/all-data', 'websiteController@trendingProductListAllData');
Route::post('/website/get-product-list/by-sub-cat', 'websiteController@ProductListBySubCat');
Route::get('/website/product-search-data', 'websiteController@searchProductList');
Route::get('/website/item-details/{item_id}', 'websiteController@itemDetails');

Route::post('/website/topber-item-counter', 'websiteController@itemCounter');
Route::post('/website/item-quantity-add-from-checkout', 'websiteController@addQuantity');
Route::post('/website/item-quantity-add-from-sitebar', 'websiteController@addQuantityFromSitebar');
Route::post('/website/item-quantity-minus-from-sitebar', 'websiteController@minusQuantityFromSitebar');
Route::post('/website/item-quantity-remove-from-checkout', 'websiteController@removeQuantity');
Route::post('/website/item-remove-from-checkout', 'websiteController@removeItem');
Route::post('/website/get-location-data', 'websiteController@locationData');
Route::post('/website/get-shop-data', 'websiteController@shopData');
Route::post('/website/get-reg-otp', 'websiteController@getOtp');
Route::get('/all-trending-products', 'websiteController@allTrendingProducts');
Route::get('/product-by-category/sub/{cat_id}', 'websiteController@productByCategory');

Route::get('/checkout', 'checkoutController@index');
Route::post('/website/place-order', 'checkoutController@placeOrder');
Route::post('/website/get-delivery-timeslot', 'checkoutController@deliveryTimeSlot');
Route::post('/website/validate-coupon', 'checkoutController@validateCoupon');

Route::get('/sign-out', 'apiAuthenticationsController@logOutAttempt');
Route::post('/sign-up', 'apiAuthenticationsController@loginAttempt');
Route::post('/website/init-registration', 'apiAuthenticationsController@registration');
Route::post('/website/init-login', 'apiAuthenticationsController@initLogin');


Route::post('/apiBasedLogin', 'apiAuthenticationsController@loginAttempt');
Route::get('/apiBasedLogOut', 'apiAuthenticationsController@logOutAttempt');
Route::post('/forgot-password/get-otp', 'forgotPassController@getOtp');
Route::post('/forgot-password/set-new-password', 'forgotPassController@setNewPass');
Route::post('/website/init-forgot-pass', 'forgotPassController@setForgotPass');


Route::group(array('middleware' => ['authAndAcl']), function() {
    Route::get('/my-dashboard', 'dashboardController@index');
    Route::post('/get-my-order-list', 'dashboardController@myOrderList');
    Route::post('/get-my-profile-data', 'dashboardController@myProfile');
    Route::post('/update-profile-data', 'dashboardController@updateProfile');
});

