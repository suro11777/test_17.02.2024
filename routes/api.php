<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);
    });
});
Route::group(['middleware' => ['auth:api', 'admin']],
    function () {

        Route::apiResource('products', \App\Http\Controllers\Admin\ProductController::class)->except('show');
        Route::get('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'get']);
        Route::apiResource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except('show');
        Route::get('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'get']);

    });

