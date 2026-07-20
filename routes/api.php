<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/news/{country_id}', [NewsController::class, 'getAnalyzedNews']);

use App\Http\Controllers\Api\PortController;
use App\Http\Controllers\Api\CountryController;

Route::get('/countries', [CountryController::class, 'apiCountries']);

Route::get('/risk', [CountryController::class, 'apiRiskScores']);

Route::get('/ports', [PortController::class, 'apiPorts']);

Route::get('/news', [CountryController::class, 'apiNews']);

Route::get('/currency', [CountryController::class, 'apiCurrency']);