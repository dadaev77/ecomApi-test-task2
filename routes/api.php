<?php

use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\OrderController;

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

Route::prefix('v1')->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::post('/create-order', [OrderController::class, 'createOrder']);
    Route::post('/approve-order', [OrderController::class, 'approveOrder']);
});

