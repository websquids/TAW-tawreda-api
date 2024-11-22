<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
