<?php

use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation.index');
Route::get('/recommendation/search', [RecommendationController::class, 'search'])->name('recommendation.search');
Route::get('/recommendation/hybrid', [RecommendationController::class, 'searchHybrid'])->name('recommendation.hybrid');
