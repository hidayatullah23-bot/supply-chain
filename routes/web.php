<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Api\PortController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AdminController;

// --- Dashboard & Negara ---
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/countries/{id}/dashboard', [CountryController::class, 'dashboard'])->name('countries.dashboard');
Route::get('/countries/{id}/news', [CountryController::class, 'news'])->name('countries.news');
Route::get('/countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');
Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update');

// --- Risk Scoring & API Fetchers ---
Route::get('/countries/{id}/calculate-risk', [CountryController::class, 'calculateRisk']);
Route::get('/countries/{id}/fetch-weather', [CountryController::class, 'fetchWeatherRisk']);
Route::get('/countries/{id}/fetch-inflation', [CountryController::class, 'fetchInflationRisk']);
Route::get('/countries/{id}/fetch-sentiment', [CountryController::class, 'fetchNewsSentiment']);

// --- Perbandingan, Peta, Kurs, & Laporan ---
Route::get('/countries/compare', [CountryController::class, 'compare'])->name('countries.compare');
Route::get('/countries/map', [CountryController::class, 'mapView'])->name('countries.map');
Route::get('/countries/{id}/currency', [CountryController::class, 'currencyChart'])->name('countries.currency');
Route::get('/countries/{id}/export', [CountryController::class, 'exportReport'])->name('countries.export');

// --- Pelabuhan (Ports) ---
Route::get('/ports', [PortController::class, 'index'])->name('ports.index');

// --- Watchlist (Favorite Monitoring) ---
Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

// --- Admin Dashboard ---
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/ports', [AdminController::class, 'storePort'])->name('admin.port.store');
Route::delete('/admin/ports/{id}', [AdminController::class, 'destroyPort'])->name('admin.port.destroy');
Route::post('/admin/articles', [AdminController::class, 'storeArticle'])->name('admin.article.store');
Route::delete('/admin/articles/{id}', [AdminController::class, 'destroyArticle'])->name('admin.article.destroy');