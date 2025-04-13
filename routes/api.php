<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:api', 'setLanguage')->group(function () {
  // Route::get('/user', function (Request $request) {
    //     return $request->user();
  // });
  Route::apiResource('products', App\Http\Controllers\ProductController::class);
  Route::post('products/bulk-delete', [App\Http\Controllers\ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
  Route::post('products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
  Route::put('/products/{product}/quantity', [App\Http\Controllers\ProductController::class, 'updateQuantity']);

  Route::apiResource('brands', App\Http\Controllers\BrandController::class);
  Route::post('brands/bulk-delete', [App\Http\Controllers\BrandController::class, 'bulkDelete'])->name('brands.bulkDelete');
  Route::post('brands/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('brands.update');
  Route::apiResource('units', App\Http\Controllers\UnitController::class);
  Route::post('units/bulk-delete', [App\Http\Controllers\UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
  Route::post('units/{unit}', [App\Http\Controllers\UnitController::class, 'update'])->name('units.update');
  Route::apiResource('categories', App\Http\Controllers\CategoryController::class);
  Route::post('categories/bulk-delete', [App\Http\Controllers\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
  Route::post('categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
  Route::get('users', [App\Http\Controllers\UserController::class, 'getUsers']);
  Route::get('orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
  Route::get('/orders/order-statuses', [App\Http\Controllers\OrderController::class, 'getAllOrderStatus']);
  Route::post('orders/status-update', [App\Http\Controllers\OrderController::class, 'bulkUpdateOrderStatus'])->name('orders.updateOrderStatus');
  Route::get('orders/{order}', [App\Http\Controllers\OrderController::class, 'show']);
  Route::delete('orders/{order}', [App\Http\Controllers\OrderController::class, 'destroy']);
  Route::get('cart', [App\Http\Controllers\CartController::class, 'index']);
  Route::post('sliders', [App\Http\Controllers\SliderController::class, 'store'])->name('sliders.store');
  Route::get('sliders', [App\Http\Controllers\SliderController::class, 'index'])->name('sliders.index');
  Route::put('sliders/{slider}', [App\Http\Controllers\SliderController::class, 'edit'])->name('sliders.edit');
  Route::post('app-settings', [App\Http\Controllers\AppSettingsController::class, 'store'])->name('app_settings.store');
  Route::get('app-settings', [App\Http\Controllers\AppSettingsController::class, 'index'])->name('app_settings.index');
  Route::get('app-settings/{appSetting}', [App\Http\Controllers\AppSettingsController::class, 'show']);
  Route::put('app-settings/{appSetting}', [App\Http\Controllers\AppSettingsController::class, 'edit'])->name('app_settings.edit');
});

Route::group(['prefix' => 'customer_app','namespace' => 'customer_app', 'middleware' => ['setLanguage']], function () {
  Route::post('/auth/login', [AuthController::class, 'customerLogin']);
  Route::post('/auth/register', [AuthController::class, 'register']);
  Route::post('/auth/forget-password', [AuthController::class, 'forgetPassword']);
  Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
  Route::get('/categories', [App\Http\Controllers\CustomerApp\CategoryController::class, 'index']);
  Route::get('/products', [App\Http\Controllers\CustomerApp\ProductController::class, 'index']);
  Route::get('/products/{id}', [App\Http\Controllers\CustomerApp\ProductController::class, 'show']);
  Route::get('/sliders', [App\Http\Controllers\CustomerApp\SliderController::class, 'index']);
  Route::get('/brands', [App\Http\Controllers\CustomerApp\BrandController::class, 'index']);
  
  Route::group(['middleware' => ['role:customer', 'auth:api']], function () {
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::delete('/delete-account', [AuthController::class, 'deleteUser']);
    Route::get('/products/min-max-price', [App\Http\Controllers\CustomerApp\ProductController::class, 'getMinMaxPrice']);
    Route::get('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'index']);
    Route::post('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'store']);
    Route::delete('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'destroy']);
    Route::delete('/carts/items', [App\Http\Controllers\CustomerApp\CartController::class, 'removeItem']);
    Route::post('/addresses', [App\Http\Controllers\CustomerApp\AddressController::class, 'store']);
    Route::get('/addresses', [App\Http\Controllers\CustomerApp\AddressController::class, 'index']);
    Route::put('/addresses/{id}', [App\Http\Controllers\CustomerApp\AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [App\Http\Controllers\CustomerApp\AddressController::class, 'destroy']);
    Route::post('/fcm', [App\Http\Controllers\CustomerApp\FcmTokenController::class, 'edit']);
    Route::post('/orders', [App\Http\Controllers\CustomerApp\OrderController::class, 'store']);
    Route::get('/orders', [App\Http\Controllers\CustomerApp\OrderController::class, 'index'])->name('customer_app.orders.index');
    Route::get('/orders/order-statuses', [App\Http\Controllers\OrderController::class, 'getAllOrderStatus']);
    Route::get('/orders/{id}', [App\Http\Controllers\CustomerApp\OrderController::class, 'show']);
    Route::post('/orders/{id}/resale', [App\Http\Controllers\CustomerApp\OrderController::class, 'requestResale']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::post('/auth/logout', [AuthController::class, 'customerLogout']);
    Route::get('/app-settings', [App\Http\Controllers\CustomerApp\AppSettingsController::class, 'index']);
  });
  Route::post('/auth/verify-otp/{chanel}', [AuthController::class, 'verifyOTP']);
});

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
