<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CountryController::class, 'index']);
Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::post('/login',[AuthController::class,'login']);
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
    Route::post('/register',[AuthController::class,'register']);
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');

// Rute Master Negara & Perbandingan
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/countries/compare', [CountryController::class, 'compare'])->name('countries.compare');
Route::get('/global-map', [CountryController::class, 'globalMap'])->name('global.map');
Route::get('/weather-map', [CountryController::class, 'weatherMap'])->name('weather.map');
Route::get('/countries/{id}', [CountryController::class, 'dashboard'])->name('countries.dashboard');
Route::post('/countries/{id}/sync', [CountryController::class, 'sync'])->name('countries.sync');
Route::get('/countries/{id}/news', [CountryController::class, 'news'])->name('countries.news');
Route::get('/countries/{id}/currency', [CountryController::class, 'currencyChart'])->name('countries.currency');
Route::get('/countries/{id}/report', [CountryController::class, 'exportReport'])->name('countries.report');
Route::post('/countries/{id}/calculate-risk', [CountryController::class, 'calculateRisk']);

Route::middleware('auth')->group(function(){
    Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');
    Route::post('/watchlists', [WatchlistController::class, 'store'])->name('watchlists.store');
    Route::delete('/watchlists/{id}', [WatchlistController::class, 'destroy'])->name('watchlists.destroy');
});

// Rute Admin Dashboard
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/ports', [AdminController::class, 'storePort'])->name('ports.store');
    Route::put('/ports/{port}', [AdminController::class, 'updatePort'])->name('ports.update');
    Route::delete('/ports/{port}', [AdminController::class, 'destroyPort'])->name('ports.destroy');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/articles', [AdminController::class, 'storeArticle'])->name('articles.store');
    Route::put('/articles/{article}', [AdminController::class, 'updateArticle'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminController::class, 'destroyArticle'])->name('articles.destroy');
});

// Rute Dinamis untuk Dashboard Masing-Masing Fitur (2 sampai 7)
Route::get('/{slug}', [FeatureController::class, 'index'])
    ->where('slug', 'ports|suppliers|warehouses|risk-scores|articles|sentiments')
    ->name('feature.show');

foreach (['ports', 'suppliers', 'warehouses', 'risk-scores', 'articles', 'sentiments'] as $feature) {
    Route::get('/'.$feature, [FeatureController::class, 'index'])
        ->defaults('slug', $feature)
        ->name($feature.'.index');
}
