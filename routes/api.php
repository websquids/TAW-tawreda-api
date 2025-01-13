<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:api')->group(function () {
  // Route::get('/user', function (Request $request) {
    //     return $request->user();
  // });
  Route::apiResource('products', App\Http\Controllers\ProductController::class);
  Route::post('products/bulk-delete', [App\Http\Controllers\ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
  Route::post('products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

  Route::apiResource('brands', App\Http\Controllers\BrandController::class);
  Route::post('brands/bulk-delete', [App\Http\Controllers\BrandController::class, 'bulkDelete'])->name('brands.bulkDelete');
  Route::post('brands/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('brands.update');
  Route::apiResource('units', App\Http\Controllers\UnitController::class);
  Route::post('units/bulk-delete', [App\Http\Controllers\UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
  Route::post('units/{unit}', [App\Http\Controllers\UnitController::class, 'update'])->name('units.update');
  Route::apiResource('categories', App\Http\Controllers\CategoryController::class);
  Route::post('categories/bulk-delete', [App\Http\Controllers\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
  Route::post('categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
});

Route::group(['prefix' => 'customer_app'], function () {
  Route::post('/auth/login', [AuthController::class, 'customerLogin']);
  Route::post('/auth/register', [AuthController::class, 'register']);
  Route::get('/categories', [App\Http\Controllers\CustomerApp\CategoryController::class, 'index']);
  Route::get('/products', [App\Http\Controllers\CustomerApp\ProductController::class, 'index']);
  Route::get('/products/{id}', [App\Http\Controllers\CustomerApp\ProductController::class, 'show']);

  Route::group(['middleware' => ['role:customer', 'auth:api']], function () {
    Route::get('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'index']);
    Route::post('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'store']);
    Route::delete('/carts', [App\Http\Controllers\CustomerApp\CartController::class, 'destroy']);
    Route::delete('/carts/items', [App\Http\Controllers\CustomerApp\CartController::class, 'removeItem']);
    Route::post('/addresses', [App\Http\Controllers\CustomerApp\AddressController::class, 'store']);
    Route::get('/addresses', [App\Http\Controllers\CustomerApp\AddressController::class, 'index']);
    Route::put('/addresses/{id}', [App\Http\Controllers\CustomerApp\AddressController::class, 'update']);
    Route::post('/fcm', [App\Http\Controllers\CustomerApp\FcmTokenController::class, 'edit']);
  });
});


// Route::prefix('customer_app')->group(function () {
// });

// Route::apiResource('products', App\Http\Controllers\ProductController::class);
// Route::post('products/bulk-delete', [App\Http\Controllers\ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
// Route::post('products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

// Route::apiResource('brands', App\Http\Controllers\BrandController::class);
// Route::post('brands/bulk-delete', [App\Http\Controllers\BrandController::class, 'bulkDelete'])->name('brands.bulkDelete');
// Route::post('brands/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('brands.update');
// Route::apiResource('units', App\Http\Controllers\UnitController::class);
// Route::post('units/bulk-delete', [App\Http\Controllers\UnitController::class, 'bulkDelete'])->name('units.bulkDelete');
// Route::post('units/{unit}', [App\Http\Controllers\UnitController::class, 'update'])->name('units.update');
// Route::apiResource('categories', App\Http\Controllers\CategoryController::class);
// Route::post('categories/bulk-delete', [App\Http\Controllers\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
// Route::post('categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
