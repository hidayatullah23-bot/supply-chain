<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;

Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');

Route::get('/countries/{id}/dashboard', [CountryController::class, 'dashboard'])->name('countries.dashboard');

Route::get('/countries/{id}/news', [CountryController::class, 'news'])->name('countries.news');

Route::get('/countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');

Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update');

Route::get('/countries/{id}/calculate-risk', [CountryController::class, 'calculateRisk']);

Route::get('/countries/{id}/fetch-weather', [CountryController::class, 'fetchWeatherRisk']);

Route::get('/countries/{id}/fetch-inflation', [CountryController::class, 'fetchInflationRisk']);

Route::get('/countries/{id}/fetch-sentiment', [CountryController::class, 'fetchNewsSentiment']);