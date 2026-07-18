<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;

/*
|--------------------------------------------------------------------------
| Web Routes - Supply Chain Management (Countries Module)
|--------------------------------------------------------------------------
*/

// Alur Utama & CRUD Manual
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/create', [CountryController::class, 'create']);
Route::post('/countries', [CountryController::class, 'store']);

// Fitur Intelijen API & Data Science
Route::get('/countries/{id}/dashboard', [CountryController::class, 'dashboard'])->name('countries.dashboard');
Route::get('/countries/{id}/news', [CountryController::class, 'news'])->name('countries.news');

// Catatan: Jika kamu masih membutuhkan rute Edit & Hapus bawaan rute manualmu nanti, 
// kamu bisa menambahkannya di sini setelah form tambah berhasil diselesaikan.