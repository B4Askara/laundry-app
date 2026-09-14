<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RewardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::resource('pelanggan', PelangganController::class)
    ->middleware('auth');

Route::resource('layanan', LayananController::class)
    ->middleware('auth');

Route::resource('pemesanan', PemesananController::class)
    ->middleware('auth');

Route::resource('reward', RewardController::class);