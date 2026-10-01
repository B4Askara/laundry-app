<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\PembayaranController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

// DATA PELANGGAN
Route::resource('pelanggan', PelangganController::class)
    ->middleware('auth');

// DATA LAYANAN
Route::resource('layanan', LayananController::class)
    ->middleware('auth');

// ROUTE ALUR PEMESANAN
Route::get('/pemesanan/alur/pelanggan', [
    PemesananController::class,
    'alurPelanggan'
])->name('pemesanan.alur.pelanggan')->middleware('auth');

// SIMPAN DATA PELANGGAN ALUR
Route::post('/pemesanan/alur/pelanggan', [
    PemesananController::class,
    'simpanPelangganAlur'
])->name('pemesanan.alur.pelanggan.simpan')->middleware('auth');

// HALAMAN PILIH JENIS PELAYANAN
Route::get('/pemesanan/alur/layanan', [
    PemesananController::class,
    'alurLayanan'
])->name('pemesanan.alur.layanan')->middleware('auth');

// SIMPAN PILIHAN LAYANAN
Route::post('/pemesanan/alur/layanan', [
    PemesananController::class,
    'simpanLayananAlur'
])->name('pemesanan.alur.layanan.simpan')->middleware('auth');

// HALAMAN DETAIL LAYANAN
Route::get('/pemesanan/alur/detail', [
    PemesananController::class,
    'alurDetail'
])->name('pemesanan.alur.detail')->middleware('auth');

// SIMPAN DETAIL LAYANAN
Route::post('/pemesanan/alur/detail', [
    PemesananController::class,
    'simpanDetailAlur'
])->name('pemesanan.alur.detail.simpan')->middleware('auth');

// HALAMAN METODE PENGAMBILAN
Route::get('/pemesanan/alur/pengambilan', [
    PemesananController::class,
    'alurPengambilan'
])->name('pemesanan.alur.pengambilan')->middleware('auth');

// SIMPAN METODE PENGAMBILAN
Route::post('/pemesanan/alur/pengambilan', [
    PemesananController::class,
    'simpanPengambilanAlur'
])->name('pemesanan.alur.pengambilan.simpan')->middleware('auth');

// PENCARIAN PELANGGAN
Route::get('/pemesanan/cari-pelanggan', [
    PemesananController::class,
    'cariPelanggan'
])->name('pemesanan.cari-pelanggan')->middleware('auth');

// HALAMAN POIN / STEMPEL
Route::get('/pemesanan/alur/poin', [
    PemesananController::class,
    'alurPoin'
])->name('pemesanan.alur.poin')->middleware('auth');

// HALAMAN PILIH REWARD
Route::get('/pemesanan/alur/reward', [
    PemesananController::class,
    'alurReward'
])->name('pemesanan.alur.reward')->middleware('auth');

// SIMPAN PILIHAN REWARD
Route::post('/pemesanan/alur/reward', [
    PemesananController::class,
    'simpanRewardAlur'
])->name('pemesanan.alur.reward.simpan')->middleware('auth');

// PEMESANAN
Route::resource('pemesanan', PemesananController::class)
    ->middleware('auth');

// DATA REWARD
Route::resource('reward', RewardController::class);

// TRANSAKSI DAN PEMBAYARAN
Route::get('/pembayaran', [PembayaranController::class, 'index'])
    ->name('pembayaran.index')
    ->middleware('auth');

Route::get('/pembayaran/{id}/bayar', [PembayaranController::class, 'create'])
    ->name('pembayaran.create')
    ->middleware('auth');

Route::post('/pembayaran/{id}/bayar', [PembayaranController::class, 'store'])
    ->name('pembayaran.store')
    ->middleware('auth');
