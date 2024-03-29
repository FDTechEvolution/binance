<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\FeatureController;
use App\Http\Controllers\Api\V1\StatusController;
use App\Http\Controllers\Api\V1\OrderHistoryController;
use App\Http\Controllers\Api\V1\CryptoScreenerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/regis', [RegisterController::class, 'register']);

Route::prefix('/features')->group(function () {
    Route::get('/list', [FeatureController::class, 'list']);
});
Route::prefix('/status')->group(function () {
    Route::get('/coin', [StatusController::class, 'coin']);
});
Route::prefix('/crypto-screener')->group(function () {
    Route::get('/list', [CryptoScreenerController::class, 'getCryptoScreener']);
});

Route::get('/update-avg-price', [FeatureController::class, 'update_avg_price']);

Route::get('/update-crypto', [CryptoScreenerController::class, 'getPremiumIndex']);
Route::get('/update-crypto-screener', [CryptoScreenerController::class, 'indicators']);

Route::get('/orders/get-order', [OrderHistoryController::class, 'getOrder']);
Route::get('/orders/get-coin', [OrderHistoryController::class, 'getCoin']);