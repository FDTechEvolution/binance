<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\FiltersController;

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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'authenticate'])->name('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function() { return view('dashboard'); })->name('dashboard');

    Route::prefix('/features')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('features');
        Route::post('/', [FeatureController::class, 'create'])->name('feature-create');
        Route::post('/delete', [FeatureController::class, 'delete'])->name('feature-delete');
        Route::post('/edit', [FeatureController::class, 'update'])->name('feature-edit');
    });

    Route::prefix('/status')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('status');
        Route::get('/close/{coin}', [StatusController::class, 'setCloseStatus'])->name('close-status');
    });

    Route::prefix('/history')->group(function () {
        Route::get('/', [OrderHistoryController::class, 'index'])->name('history');
        Route::post('/', [OrderHistoryController::class, 'getHistory'])->name('get-history');
    });

    Route::prefix('/filters')->group(function () {
        Route::get('/', [FiltersController::class, 'index'])->name('filters');
    });
});