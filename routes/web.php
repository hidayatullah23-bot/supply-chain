<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;

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

// Route bawaan welcome page
Route::get('/', function () {
    return view('welcome');
});

// Route Baru: Menampilkan halaman analisis sentimen berita berdasarkan negara
Route::get('/countries/{id}/news', [NewsController::class, 'showNewsPage'])->name('countries.news');