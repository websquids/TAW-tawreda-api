<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::get('user', [AuthenticatedSessionController::class, 'show'])->middleware('auth:sanctum');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('{id}', [RoleController::class, 'update']);
    Route::delete('{id}', [RoleController::class, 'destroy']);
});
Route::prefix('users/{userId}/roles')->group(function () {
    Route::post('assign', [UserController::class, 'assignRole']);
    Route::post('remove', [UserController::class, 'removeRole']);
});
Route::post('password/reset-link', [PasswordResetLinkController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', App\Http\Controllers\ProductController::class);
Route::post('products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

Route::apiResource('brands', App\Http\Controllers\BrandController::class);
Route::post('brands/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('brands.update');
Route::apiResource('units', App\Http\Controllers\UnitController::class);
Route::post('units/{unit}', [App\Http\Controllers\UnitController::class, 'update'])->name('units.update');
Route::apiResource('categories', App\Http\Controllers\CategoryController::class);
Route::post('categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
