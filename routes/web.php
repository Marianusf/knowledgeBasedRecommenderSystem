<?php

use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\PhoneController;
// use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});


Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation.index');
Route::get('/recommendation/search', [RecommendationController::class, 'search'])->name('recommendation.search');
Route::get('/recommendation/hybrid', [RecommendationController::class, 'searchHybrid'])->name('recommendation.hybrid');


// Route::prefix('admin')->group(function () {
Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->prefix('phones')->name('admin.phones.')->group(function () {
    Route::get('/', [PhoneController::class, 'index'])->name('index');
    Route::get('/create', [PhoneController::class, 'create'])->name('create');
    Route::post('/', [PhoneController::class, 'store'])->name('store');
    Route::get('/{phone}/edit', [PhoneController::class, 'edit'])->name('edit');
    Route::put('/{phone}', [PhoneController::class, 'update'])->name('update');
    Route::delete('/{phone}', [PhoneController::class, 'destroy'])->name('destroy');
});
// });
