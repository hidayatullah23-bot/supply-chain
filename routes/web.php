<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama / Welcome
Route::get('/', function () {
    return view('welcome');
});

// Route Resource untuk Manajemen CRUD Negara (Countries)
Route::resource('countries', CountryController::class);

// Route Resource untuk Manajemen CRUD Pemasok (Suppliers)
Route::resource('suppliers', SupplierController::class);

// Route Resource untuk Manajemen CRUD Gudang (Warehouses)
Route::resource('warehouses', WarehouseController::class);